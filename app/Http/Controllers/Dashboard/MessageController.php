<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Display the messages page with conversations list.
     */
    public function index()
    {
        $userId = Auth::id();

        // Get all conversations for the current user
        $conversations = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get all users except current user (for starting new conversation)
        $users = User::where('id', '!=', $userId)->get();

        return view('dashboard.messages.index', compact('conversations', 'users'));
    }

    /**
     * Get conversations list for sidebar (AJAX).
     */
    public function getConversations()
    {
        try {
            $userId = Auth::id();

            $conversations = Conversation::where('user_one_id', $userId)
                ->orWhere('user_two_id', $userId)
                ->with(['userOne', 'userTwo', 'lastMessage'])
                ->orderBy('last_message_at', 'desc')
                ->get();

            // Add unread count for each conversation
            $conversations->each(function ($conv) use ($userId) {
                $conv->unread_messages_count = $conv->messages()
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();
            });

            return response()->json([
                'success' => true,
                'conversations' => $conversations,
            ]);
        } catch (\Exception $e) {
            Log::error('getConversations error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get messages for a specific conversation (AJAX).
     */
    public function getMessages(Conversation $conversation)
    {
        try {
            $userId = Auth::id();

            // Check if user is part of this conversation
            if ($conversation->user_one_id != $userId && $conversation->user_two_id != $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Get messages
            $messages = $conversation->messages()
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark messages as read
            $conversation->messages()
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            // Update last_message_at
            $conversation->update(['last_message_at' => now()]);

            // Get other user
            $otherUser = $conversation->user_one_id == $userId
                ? $conversation->userTwo
                : $conversation->userOne;

            return response()->json([
                'success' => true,
                'messages' => $messages,
                'conversation_id' => $conversation->id,
                'other_user' => $otherUser,
            ]);
        } catch (\Exception $e) {
            Log::error('getMessages error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send a message (AJAX).
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'nullable|exists:conversations,id',
                'receiver_id' => 'required|exists:users,id',
                'message' => 'required|string|max:5000',
            ]);

            $senderId = Auth::id();
            $receiverId = $request->receiver_id;

            // Prevent sending message to self
            if ($senderId == $receiverId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot send message to yourself'
                ], 400);
            }

            // Get or create conversation
            if ($request->conversation_id) {
                $conversation = Conversation::find($request->conversation_id);

                // Verify user is part of conversation
                if ($conversation->user_one_id != $senderId && $conversation->user_two_id != $senderId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized'
                    ], 403);
                }
            } else {
                // Create new conversation
                $conversation = Conversation::where(function ($q) use ($senderId, $receiverId) {
                    $q->where('user_one_id', $senderId)->where('user_two_id', $receiverId);
                })->orWhere(function ($q) use ($senderId, $receiverId) {
                    $q->where('user_one_id', $receiverId)->where('user_two_id', $senderId);
                })->first();

                if (!$conversation) {
                    $conversation = Conversation::create([
                        'user_one_id' => min($senderId, $receiverId),
                        'user_two_id' => max($senderId, $receiverId),
                        'last_message_at' => now(),
                    ]);
                }
            }

            // Create message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'message' => $request->message,
                'is_read' => false,
            ]);

            // Update last_message_at
            $conversation->update(['last_message_at' => now()]);

            // Load sender and receiver relationships
            $message->load(['sender', 'receiver']);

            return response()->json([
                'success' => true,
                'message' => $message,
                'conversation_id' => $conversation->id,
            ]);
        } catch (\Exception $e) {
            Log::error('sendMessage error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Start a new conversation (AJAX).
     */
    public function startConversation(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $userId = Auth::id();
            $otherUserId = $request->user_id;

            // Prevent starting conversation with self
            if ($userId == $otherUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot start conversation with yourself'
                ], 400);
            }

            // Check if user exists
            $otherUser = User::find($otherUserId);
            if (!$otherUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Find or create conversation
            $conversation = Conversation::where(function ($q) use ($userId, $otherUserId) {
                $q->where('user_one_id', $userId)->where('user_two_id', $otherUserId);
            })->orWhere(function ($q) use ($userId, $otherUserId) {
                $q->where('user_one_id', $otherUserId)->where('user_two_id', $userId);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'user_one_id' => min($userId, $otherUserId),
                    'user_two_id' => max($userId, $otherUserId),
                    'last_message_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'other_user' => $otherUser,
            ]);
        } catch (\Exception $e) {
            Log::error('startConversation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get unread count (AJAX).
     */
    public function getUnreadCount()
    {
        try {
            $userId = Auth::id();

            $count = Message::where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0]);
        }
    }

    /**
     * Delete conversation.
     */
    public function deleteConversation(Conversation $conversation)
    {
        try {
            $userId = Auth::id();

            if ($conversation->user_one_id != $userId && $conversation->user_two_id != $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Delete all messages in conversation
            $conversation->messages()->delete();
            $conversation->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific message.
     */
    public function deleteMessage(Message $message)
    {
        try {
            $userId = Auth::id();

            if ($message->sender_id != $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $message->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all messages as read for the current user.
     */
    public function markAllRead()
    {
        try {
            $userId = Auth::id();

            Message::where('receiver_id', $userId)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
