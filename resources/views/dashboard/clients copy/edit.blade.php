@extends('layouts.dashboard')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user information')

@push('styles')
    <style>
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            background: #f4f6fb;
        }

        .current-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e8edf5;
        }

        .form-section {
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 1px solid #e8edf5;
        }

        .form-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a2035;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            font-size: 20px;
            color: #2f7bff;
        }

        .form-label-dash {
            font-size: 13px;
            font-weight: 600;
            color: #1a2035;
            margin-bottom: 6px;
        }

        .form-control-dash {
            border: 1.5px solid #e8edf5;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color .2s;
            width: 100%;
            background: #fff;
            color: #1a2035;
        }

        .form-control-dash:focus {
            border-color: #2f7bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.1);
        }

        .btn-primary-dash {
            background: #2f7bff;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-primary-dash:hover {
            background: #1a5fcc;
            color: white;
            transform: translateY(-2px);
        }

        .btn-light-dash {
            background: #f4f6fb;
            color: #1a2035;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            transition: all .2s;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-light-dash:hover {
            background: #e8edf5;
            color: #1a2035;
        }

        .card-box {
            background: white;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #e8edf5;
        }

        .fade-up {
            animation: fadeUp 0.4s ease-out forwards;
            opacity: 0;
        }

        .d1 {
            animation-delay: 0.05s;
        }

        .d2 {
            animation-delay: 0.1s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto fade-up d1">
            <div class="card-box">
                <form method="POST" action="{{ route('dashboard.clients.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Account Information --}}
                    <div class="form-section">
                        <div class="section-title">
                            <i class="bi bi-person-fill"></i>
                            <span>Account Information</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-dash">Full Name *</label>
                                <input type="text" name="name" class="form-control-dash"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Username</label>
                                <input type="text" name="username" class="form-control-dash"
                                    value="{{ old('username', $user->username) }}" placeholder="e.g. john-doe">
                                <small class="text-muted-custom" style="font-size: 11px; color: #7a869a;">Used for public CV
                                    URL</small>
                                @error('username')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Email Address *</label>
                                <input type="email" name="email" class="form-control-dash"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Role *</label>
                                <select name="role" class="form-control-dash" required>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                                    </option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">New Password</label>
                                <input type="password" name="password" class="form-control-dash"
                                    placeholder="Leave empty to keep current">
                                <small class="text-muted-custom" style="font-size: 11px; color: #7a869a;">Only fill if you
                                    want to change password</small>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control-dash"
                                    placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>

                    {{-- Profile Information --}}
                    <div class="form-section">
                        <div class="section-title">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>Profile Information</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-dash">Professional Title</label>
                                <input type="text" name="title" class="form-control-dash"
                                    value="{{ old('title', $profile->title ?? '') }}"
                                    placeholder="e.g. Full Stack Developer">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Phone Number</label>
                                <input type="text" name="phone" class="form-control-dash"
                                    value="{{ old('phone', $profile->phone ?? '') }}" placeholder="+966 5X XXX XXXX">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label-dash">Location</label>
                                <input type="text" name="location" class="form-control-dash"
                                    value="{{ old('location', $profile->location ?? '') }}"
                                    placeholder="e.g. Al-Hofuf, Saudi Arabia">
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Bio / About</label>
                                <textarea name="bio" class="form-control-dash" rows="4" placeholder="Tell about this user...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Current Avatar</label>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @php
                                        $avatarUrl =
                                            $profile && $profile->avatar
                                                ? asset('storage/' . $profile->avatar)
                                                : 'https://ui-avatars.com/api/?background=2f7bff&color=fff&size=80&name=' .
                                                    urlencode($user->name);
                                    @endphp
                                    <img src="{{ $avatarUrl }}" class="current-avatar" alt="{{ $user->name }}">
                                    <div>
                                        <small class="text-muted-custom" style="font-size: 11px;">Current profile
                                            picture</small>
                                    </div>
                                </div>
                                <label class="form-label-dash">Change Avatar</label>
                                <input type="file" name="avatar" id="avatarInput" class="form-control-dash"
                                    accept="image/*">
                                <small class="text-muted-custom" style="font-size: 11px; color: #7a869a;">Supported: JPG,
                                    PNG, GIF. Max 2MB</small>
                                <div class="mt-3" id="avatarPreviewContainer" style="display: none;">
                                    <img id="avatarPreview" class="avatar-preview" src="" alt="Preview">
                                </div>
                                @error('avatar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('dashboard.clients.index') }}" class="btn-light-dash">Cancel</a>
                        <button type="submit" class="btn-primary-dash">
                            <i class="bi bi-check-lg me-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Preview avatar before upload
        const avatarInput = document.getElementById('avatarInput');
        const previewContainer = document.getElementById('avatarPreviewContainer');
        const avatarPreview = document.getElementById('avatarPreview');

        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.style.display = 'block';
                        avatarPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.style.display = 'none';
                    avatarPreview.src = '';
                }
            });
        }
    </script>
@endpush
