<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only admin can see messages
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = Message::query();

        // Filter by search (name or email)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by read status
        if ($request->filled('status')) {
            $query->where('is_read', $request->status);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('dashboard.messages.index', compact('messages'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $message = Message::findOrFail($id);

        // Mark as read if not already
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return view('dashboard.messages.show', compact('message'));
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(Message $message)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $message->update(['is_read' => true]);

        return redirect()
            ->back()
            ->with('success', 'Message marked as read!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $message->delete();

        return redirect()
            ->route('dashboard.messages.index')
            ->with('success', 'Message deleted successfully!');
    }

    /**
     * Get unread count for badge.
     */
    public function unreadCount()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['count' => 0]);
        }

        $count = Message::where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }
}
