@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')
@section('page-subtitle', 'Chat with users')

@push('styles')
    <style>
        /* ============================================================
           CHAT LAYOUT - Fixed height
        ============================================================ */
        .chat-container {
            display: flex;
            height: calc(100vh - 200px);
            min-height: 450px;
            max-height: calc(100vh - 200px);
            background: white;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #e8edf5;
            position: relative;
        }

        /* ============================================================
           SIDEBAR - Conversations List
        ============================================================ */
        .chat-sidebar {
            width: 340px;
            min-width: 340px;
            max-width: 340px;
            border-right: 1px solid #e8edf5;
            display: flex;
            flex-direction: column;
            background: #fafbfc;
            height: 100%;
            overflow: hidden;
        }

        [data-theme="dark"] .chat-sidebar {
            background: #1e293b;
            border-right-color: #334155;
        }

        .chat-sidebar-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e8edf5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        [data-theme="dark"] .chat-sidebar-header {
            border-bottom-color: #334155;
        }

        .chat-sidebar-title {
            font-size: 16px;
            font-weight: 800;
            color: #1a2035;
        }

        [data-theme="dark"] .chat-sidebar-title {
            color: #f1f5f9;
        }

        .chat-sidebar-search {
            padding: 12px 16px;
            border-bottom: 1px solid #e8edf5;
            flex-shrink: 0;
        }

        [data-theme="dark"] .chat-sidebar-search {
            border-bottom-color: #334155;
        }

        .chat-search-input {
            width: 100%;
            padding: 8px 14px;
            border-radius: 12px;
            border: 1.5px solid #e8edf5;
            background: white;
            font-size: 13px;
            outline: none;
            transition: all 0.2s;
        }

        [data-theme="dark"] .chat-search-input {
            background: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }

        .chat-search-input:focus {
            border-color: #2f7bff;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.1);
        }

        .chat-conversations-list {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 4px 8px;
            min-height: 0;
        }

        .chat-conversations-list::-webkit-scrollbar {
            width: 4px;
        }

        .chat-conversations-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .chat-conversations-list::-webkit-scrollbar-track {
            background: transparent;
        }

        /* ============================================================
           CONVERSATION ITEM
        ============================================================ */
        .conv-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 4px;
            position: relative;
        }

        .conv-item:hover {
            background: #f1f5f9;
        }

        [data-theme="dark"] .conv-item:hover {
            background: #334155;
        }

        .conv-item.active {
            background: #e8f0ff;
        }

        [data-theme="dark"] .conv-item.active {
            background: #1e3a5f;
        }

        .conv-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            position: relative;
        }

        .conv-avatar .online-dot {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #22c55e;
            border: 2px solid white;
        }

        [data-theme="dark"] .conv-avatar .online-dot {
            border-color: #1e293b;
        }

        .conv-info {
            flex: 1;
            min-width: 0;
        }

        .conv-name {
            font-size: 14px;
            font-weight: 700;
            color: #1a2035;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        [data-theme="dark"] .conv-name {
            color: #f1f5f9;
        }

        .conv-time {
            font-size: 10px;
            color: #94a3b8;
            font-weight: 500;
        }

        .conv-last-message {
            font-size: 12px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        [data-theme="dark"] .conv-last-message {
            color: #94a3b8;
        }

        .conv-unread {
            background: #2f7bff;
            color: white;
            font-size: 10px;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
            flex-shrink: 0;
        }

        .conv-unread.zero {
            display: none;
        }

        /* ============================================================
           CHAT WINDOW
        ============================================================ */
        .chat-window {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
            height: 100%;
            overflow: hidden;
            min-width: 0;
        }

        [data-theme="dark"] .chat-window {
            background: #0f172a;
        }

        .chat-window-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100%;
            color: #94a3b8;
            padding: 20px;
        }

        .chat-window-empty i {
            font-size: 64px;
            opacity: 0.3;
            margin-bottom: 16px;
        }

        .chat-window-empty h4 {
            color: #1a2035;
            margin-bottom: 8px;
        }

        [data-theme="dark"] .chat-window-empty h4 {
            color: #f1f5f9;
        }

        .chat-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e8edf5;
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        [data-theme="dark"] .chat-header {
            border-bottom-color: #334155;
        }

        .chat-header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .chat-header-name {
            font-size: 16px;
            font-weight: 700;
            color: #1a2035;
        }

        [data-theme="dark"] .chat-header-name {
            color: #f1f5f9;
        }

        .chat-header-status {
            font-size: 12px;
            color: #94a3b8;
        }

        .chat-messages {
            flex: 1;
            padding: 20px 24px;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-height: 0;
        }

        .chat-messages::-webkit-scrollbar {
            width: 4px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        /* ============================================================
           MESSAGE BUBBLES
        ============================================================ */
        .message-bubble {
            max-width: 70%;
            padding: 10px 16px;
            border-radius: 16px;
            position: relative;
            word-wrap: break-word;
            font-size: 14px;
            line-height: 1.5;
            animation: fadeIn 0.2s ease;
        }

        .message-bubble.sent {
            align-self: flex-end;
            background: #2f7bff;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message-bubble.received {
            align-self: flex-start;
            background: #f1f5f9;
            color: #1a2035;
            border-bottom-left-radius: 4px;
        }

        [data-theme="dark"] .message-bubble.received {
            background: #1e293b;
            color: #f1f5f9;
        }

        .message-bubble .message-time {
            font-size: 10px;
            opacity: 0.6;
            margin-top: 4px;
            display: block;
            text-align: right;
        }

        .message-date-divider {
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            padding: 8px 0;
            position: relative;
        }

        .message-date-divider span {
            background: white;
            padding: 0 12px;
            position: relative;
            z-index: 1;
        }

        [data-theme="dark"] .message-date-divider span {
            background: #0f172a;
        }

        .message-date-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e8edf5;
        }

        [data-theme="dark"] .message-date-divider::before {
            background: #334155;
        }

        /* ============================================================
           CHAT INPUT
        ============================================================ */
        .chat-input-area {
            padding: 16px 24px;
            border-top: 1px solid #e8edf5;
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-shrink: 0;
            background: white;
        }

        [data-theme="dark"] .chat-input-area {
            border-top-color: #334155;
            background: #0f172a;
        }

        .chat-input {
            flex: 1;
            padding: 10px 16px;
            border-radius: 16px;
            border: 1.5px solid #e8edf5;
            resize: none;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
            background: white;
            color: #1a2035;
            max-height: 120px;
            min-height: 42px;
        }

        [data-theme="dark"] .chat-input {
            background: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }

        .chat-input:focus {
            border-color: #2f7bff;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.1);
        }

        .chat-input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .chat-send-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #2f7bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .chat-send-btn:hover {
            background: #1a5fcc;
            transform: scale(1.05);
        }

        .chat-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================================
           START CHAT MODAL
        ============================================================ */
        .user-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-list-item:hover {
            background: #f1f5f9;
        }

        [data-theme="dark"] .user-list-item:hover {
            background: #334155;
        }

        .user-list-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .user-list-name {
            font-weight: 600;
            color: #1a2035;
        }

        [data-theme="dark"] .user-list-name {
            color: #f1f5f9;
        }

        .user-list-email {
            font-size: 12px;
            color: #94a3b8;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 768px) {
            .chat-container {
                height: calc(100vh - 160px);
                min-height: 400px;
                flex-direction: column;
                border-radius: 16px;
            }

            .chat-sidebar {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                height: 50%;
                border-right: none;
                border-bottom: 1px solid #e8edf5;
            }

            [data-theme="dark"] .chat-sidebar {
                border-bottom-color: #334155;
            }

            .chat-window {
                height: 50%;
            }

            .chat-sidebar-header {
                padding: 12px 16px;
            }

            .chat-messages {
                padding: 12px 16px;
            }

            .chat-input-area {
                padding: 12px 16px;
            }

            .chat-header {
                padding: 12px 16px;
            }

            .message-bubble {
                max-width: 85%;
            }
        }

        @media (max-width: 480px) {
            .chat-container {
                height: calc(100vh - 140px);
                min-height: 350px;
                border-radius: 12px;
            }

            .chat-sidebar {
                height: 45%;
            }

            .chat-window {
                height: 55%;
            }

            .conv-item {
                padding: 10px 12px;
            }

            .conv-avatar {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            .message-bubble {
                max-width: 90%;
                padding: 8px 12px;
                font-size: 13px;
            }
        }
    </style>
@endpush

@section('content')

    <div class="chat-container">
        {{-- Sidebar --}}
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <span class="chat-sidebar-title">
                    <i class="bi bi-chat-dots-fill me-2" style="color: #2f7bff;"></i>
                    Chats
                    <span class="badge bg-primary ms-2" id="unreadBadgeSidebar">0</span>
                </span>
                <button class="btn btn-sm btn-primary" onclick="openStartChatModal()"
                    style="border-radius: 50%; width: 32px; height: 32px; padding: 0;">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

            <div class="chat-sidebar-search">
                <input type="text" class="chat-search-input" placeholder="Search conversations..."
                    id="searchConversation" oninput="filterConversations(this.value)">
            </div>

            <div class="chat-conversations-list" id="conversationsList">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-chat-dots" style="font-size: 32px; opacity: 0.3;"></i>
                    <p style="margin-top: 8px;">No conversations yet</p>
                    <p style="font-size: 12px;">Click the + button to start a new chat</p>
                </div>
            </div>
        </div>

        {{-- Chat Window --}}
        <div class="chat-window" id="chatWindow">
            {{-- Empty State --}}
            <div class="chat-window-empty" id="chatEmptyState">
                <i class="bi bi-chat-text-fill"></i>
                <h4>No chat selected</h4>
                <p>Select a conversation or start a new one</p>
            </div>

            {{-- Chat Content --}}
            <div id="chatContent" style="display: none; flex: 1; flex-direction: column; height: 100%; min-height: 0;">
                <div class="chat-header" id="chatHeader">
                    <div class="chat-header-avatar" id="chatHeaderAvatar">U</div>
                    <div style="flex: 1; min-width: 0;">
                        <div class="chat-header-name" id="chatHeaderName">User</div>
                        <div class="chat-header-status" id="chatHeaderStatus">Online</div>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteConversation()"
                            title="Delete conversation">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessages">
                    {{-- Messages will be rendered by JavaScript --}}
                </div>

                <div class="chat-input-area">
                    <textarea class="chat-input" id="chatInput" rows="1" placeholder="Type a message..."
                        onkeydown="handleKeyDown(event)" disabled></textarea>
                    <button class="chat-send-btn" id="sendBtn" onclick="sendMessage()" disabled>
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Start Chat Modal --}}
    <div class="modal fade modal-dash" id="startChatModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Start New Chat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control-dash mb-3" placeholder="Search users..."
                        id="searchUsersInput" oninput="filterUsers(this.value)">
                    <div id="usersList" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($users as $user)
                            <div class="user-list-item" onclick="startChat({{ $user->id }})"
                                data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                                <div class="user-list-avatar"
                                    style="background: {{ '#' . dechex(rand(0x000000, 0xffffff)) }};">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="user-list-name">{{ $user->name }}</div>
                                    <div class="user-list-email">{{ $user->email }}</div>
                                </div>
                                <span class="ms-auto text-muted" style="font-size: 12px;">Click to chat</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dash" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ============================================================
        // STATE
        // ============================================================
        let currentConversationId = null;
        let currentReceiverId = null;
        let currentOtherUser = null;
        let isSending = false;
        let isLoadingMessages = false;
        let pollingInterval = null;

        // ============================================================
        // INITIALIZE
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            loadConversations();
            updateUnreadCount();

            // Auto-polling for new messages (every 5 seconds)
            pollingInterval = setInterval(function() {
                if (currentConversationId) {
                    loadMessages(currentConversationId, true);
                }
                updateUnreadCount();
            }, 5000);

            // Auto-resize textarea
            const chatInput = document.getElementById('chatInput');
            chatInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });
        });

        // ============================================================
        // LOAD CONVERSATIONS
        // ============================================================
        function loadConversations() {
            fetch('{{ route('dashboard.messages.conversations') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderConversations(data.conversations);
                    }
                })
                .catch(error => {
                    console.error('Error loading conversations:', error);
                });
        }

        function renderConversations(conversations) {
            const container = document.getElementById('conversationsList');
            if (!conversations || conversations.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-dots" style="font-size: 32px; opacity: 0.3;"></i>
                        <p style="margin-top: 8px;">No conversations yet</p>
                        <p style="font-size: 12px;">Click the + button to start a new chat</p>
                    </div>
                `;
                return;
            }

            let html = '';
            conversations.forEach(conv => {
                const userId = {{ Auth::id() }};
                const otherUser = conv.user_one_id === userId ? conv.user_two : conv.user_one;
                const lastMsg = conv.last_message;
                const unreadCount = conv.unread_messages_count || 0;
                const initial = (otherUser.name || 'U').charAt(0).toUpperCase();

                const isActive = currentConversationId === conv.id ? 'active' : '';

                html += `
                    <div class="conv-item ${isActive}"
                         onclick="selectConversation(${conv.id}, ${otherUser.id})"
                         data-conv-id="${conv.id}">
                        <div class="conv-avatar" style="background: ${getColor(otherUser.id)}; position: relative;">
                            ${initial}
                            <span class="online-dot"></span>
                        </div>
                        <div class="conv-info">
                            <div class="conv-name">
                                ${otherUser.name}
                                <span class="conv-time">${lastMsg ? timeAgo(lastMsg.created_at) : ''}</span>
                            </div>
                            <div class="conv-last-message">
                                ${lastMsg ? lastMsg.message : 'No messages yet'}
                            </div>
                        </div>
                        ${unreadCount > 0 ? `<span class="conv-unread">${unreadCount}</span>` : '<span class="conv-unread zero">0</span>'}
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // ============================================================
        // SELECT CONVERSATION
        // ============================================================
        function selectConversation(convId, userId) {
            if (isLoadingMessages) return;

            currentConversationId = convId;
            currentReceiverId = userId;

            document.querySelectorAll('.conv-item').forEach(item => {
                item.classList.remove('active');
            });
            const activeItem = document.querySelector(`.conv-item[data-conv-id="${convId}"]`);
            if (activeItem) {
                activeItem.classList.add('active');
            }

            document.getElementById('chatInput').disabled = false;
            document.getElementById('sendBtn').disabled = false;
            document.getElementById('chatInput').focus();

            loadMessages(convId);
        }

        // ============================================================
        // LOAD MESSAGES
        // ============================================================
        function loadMessages(convId, isPolling = false) {
            if (isLoadingMessages && !isPolling) return;
            isLoadingMessages = true;

            fetch(`/dashboard/messages/${convId}/get`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentOtherUser = data.other_user;
                        renderMessages(data.messages, data.other_user);
                        showChatWindow(data.other_user);

                        document.getElementById('chatInput').disabled = false;
                        document.getElementById('sendBtn').disabled = false;
                        document.getElementById('chatInput').focus();

                        updateUnreadCount();

                        const container = document.getElementById('chatMessages');
                        setTimeout(() => {
                            container.scrollTop = container.scrollHeight;
                        }, 100);
                    }
                    isLoadingMessages = false;
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    isLoadingMessages = false;
                });
        }

        function renderMessages(messages, otherUser) {
            const container = document.getElementById('chatMessages');
            if (!messages || messages.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat" style="font-size: 32px; opacity: 0.3;"></i>
                        <p style="margin-top: 8px;">No messages yet. Say hello!</p>
                    </div>
                `;
                return;
            }

            let html = '';
            let lastDate = null;

            messages.forEach(msg => {
                const msgDate = new Date(msg.created_at).toLocaleDateString();
                if (lastDate !== msgDate) {
                    lastDate = msgDate;
                    html += `
                        <div class="message-date-divider">
                            <span>${formatDate(msg.created_at)}</span>
                        </div>
                    `;
                }

                const isSent = msg.sender_id === {{ Auth::id() }};
                const bubbleClass = isSent ? 'sent' : 'received';
                const time = new Date(msg.created_at).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                html += `
                    <div class="message-bubble ${bubbleClass}">
                        ${msg.message}
                        <span class="message-time">${time}</span>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function showChatWindow(otherUser) {
            document.getElementById('chatEmptyState').style.display = 'none';
            document.getElementById('chatContent').style.display = 'flex';

            document.getElementById('chatHeaderName').textContent = otherUser.name || 'User';
            const initial = (otherUser.name || 'U').charAt(0).toUpperCase();
            document.getElementById('chatHeaderAvatar').textContent = initial;
            document.getElementById('chatHeaderAvatar').style.background = getColor(otherUser.id);
            document.getElementById('chatHeaderStatus').textContent = 'Online';
        }

        // ============================================================
        // START NEW CHAT
        // ============================================================
        function startChat(userId) {
            const items = document.querySelectorAll('.user-list-item');
            items.forEach(item => {
                if (item.getAttribute('onclick')?.includes(`startChat(${userId})`)) {
                    item.style.opacity = '0.5';
                    item.style.cursor = 'wait';
                }
            });

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ route('dashboard.messages.start') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const modalEl = document.getElementById('startChatModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    } else {
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        modalEl.style.display = 'none';
                    }

                    selectConversation(data.conversation_id, userId);

                    setTimeout(() => {
                        loadConversations();
                    }, 500);
                } else {
                    alert(data.message || 'Failed to start conversation. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error starting chat:', error);
                alert('An error occurred. Please try again. Error: ' + error.message);
            })
            .finally(() => {
                document.querySelectorAll('.user-list-item').forEach(item => {
                    item.style.opacity = '1';
                    item.style.cursor = 'pointer';
                });
            });
        }

        function openStartChatModal() {
            new bootstrap.Modal(document.getElementById('startChatModal')).show();
        }

        // ============================================================
        // SEND MESSAGE
        // ============================================================
        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();

            if (!message || isSending || !currentConversationId) return;

            isSending = true;
            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;

            fetch('{{ route('dashboard.messages.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    conversation_id: currentConversationId,
                    receiver_id: currentReceiverId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    input.style.height = 'auto';
                    loadMessages(currentConversationId);
                    setTimeout(() => {
                        loadConversations();
                    }, 300);
                }
                isSending = false;
                sendBtn.disabled = false;
            })
            .catch(() => {
                isSending = false;
                sendBtn.disabled = false;
            });
        }

        function handleKeyDown(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        }

        // ============================================================
        // DELETE CONVERSATION
        // ============================================================
        function deleteConversation() {
            if (!currentConversationId) return;
            if (!confirm('Are you sure you want to delete this conversation?')) return;

            fetch(`/dashboard/messages/${currentConversationId}/delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentConversationId = null;
                    currentReceiverId = null;
                    document.getElementById('chatEmptyState').style.display = 'flex';
                    document.getElementById('chatContent').style.display = 'none';
                    document.getElementById('chatInput').disabled = true;
                    document.getElementById('sendBtn').disabled = true;
                    loadConversations();
                }
            });
        }

        // ============================================================
        // FILTER FUNCTIONS
        // ============================================================
        function filterConversations(query) {
            const items = document.querySelectorAll('.conv-item');
            query = query.toLowerCase().trim();
            items.forEach(item => {
                const name = item.querySelector('.conv-name')?.textContent?.toLowerCase() || '';
                const lastMsg = item.querySelector('.conv-last-message')?.textContent?.toLowerCase() || '';
                if (name.includes(query) || lastMsg.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filterUsers(query) {
            const items = document.querySelectorAll('.user-list-item');
            query = query.toLowerCase().trim();
            items.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                const email = item.getAttribute('data-email') || '';
                if (name.includes(query) || email.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // ============================================================
        // UPDATE UNREAD COUNT
        // ============================================================
        function updateUnreadCount() {
            fetch('{{ route('dashboard.messages.unread') }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('unreadBadgeSidebar');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }

                    const sidebarBadge = document.querySelector('.sidebar-nav a[href*="messages"] .nav-badge');
                    if (sidebarBadge) {
                        if (data.count > 0) {
                            sidebarBadge.textContent = data.count;
                            sidebarBadge.style.display = 'inline-flex';
                        } else {
                            sidebarBadge.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating unread count:', error);
                });
        }

        // ============================================================
        // HELPER FUNCTIONS
        // ============================================================
        function getColor(id) {
            const colors = ['#2f7bff', '#11998e', '#f59e0b', '#ef4444', '#8b5cf6', '#f97316', '#ec4899', '#14b8a6'];
            return colors[id % colors.length];
        }

        function timeAgo(date) {
            const diff = Math.floor((Date.now() - new Date(date).getTime()) / 1000);
            if (diff < 60) return 'Just now';
            if (diff < 3600) return Math.floor(diff / 60) + 'm';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h';
            return Math.floor(diff / 86400) + 'd';
        }

        function formatDate(date) {
            const d = new Date(date);
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);

            if (d.toDateString() === today.toDateString()) return 'Today';
            if (d.toDateString() === yesterday.toDateString()) return 'Yesterday';
            return d.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }
    </script>
@endpush
