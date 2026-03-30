@extends('layouts.dashboard')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')
@section('page-subtitle', 'Manage your personal information and account settings')

@push('styles')
<style>
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        background: #f4f6fb;
    }

    .avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #2f7bff;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all .2s;
        border: 3px solid #fff;
        z-index: 10;
    }

    .avatar-upload-btn:hover {
        background: #1a5fcc;
        transform: scale(1.05);
    }

    .avatar-upload-btn i {
        font-size: 16px;
    }

    .info-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        margin-top: 24px;
    }

    .info-icon {
        width: 44px;
        height: 44px;
        background: rgba(47, 123, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #2f7bff;
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

    .btn-danger-dash {
        background: #ef4444;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all .2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-danger-dash:hover {
        background: #dc2626;
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

    .text-muted-custom {
        color: #7a869a;
        font-size: 12px;
    }
</style>
@endpush

@section('content')

<div class="row g-4">
    {{-- Left Column - Avatar & Info --}}
    <div class="col-lg-4 fade-up d1">
        <div class="card-box text-center">
            <div class="avatar-wrapper mb-3">
                @php
                    $avatarUrl = ($profile && $profile->avatar)
                        ? asset('storage/' . $profile->avatar)
                        : 'https://ui-avatars.com/api/?background=2f7bff&color=fff&size=120&name=' . urlencode($user->name);
                @endphp
                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="profile-avatar" id="avatarPreview">
                <label for="avatarInput" class="avatar-upload-btn" title="Change avatar">
                    <i class="bi bi-camera-fill"></i>
                </label>
            </div>

            <h4 style="font-weight: 800; color: #1a2035; margin-bottom: 4px;">{{ $user->name }}</h4>
            <p style="color: #7a869a; font-size: 13px; margin-bottom: 16px;">{{ $profile->title ?? 'Add your title' }}</p>

            <div class="info-card text-start">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="info-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <small style="color: #7a869a; font-size: 11px;">Email Address</small>
                        <p style="color: #1a2035; margin: 0; font-weight: 500;">{{ $user->email }}</p>
                    </div>
                </div>

                @if($profile && $profile->phone)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="info-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <small style="color: #7a869a; font-size: 11px;">Phone Number</small>
                        <p style="color: #1a2035; margin: 0; font-weight: 500;">{{ $profile->phone }}</p>
                    </div>
                </div>
                @endif

                @if($profile && $profile->location)
                <div class="d-flex align-items-center gap-3">
                    <div class="info-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <small style="color: #7a869a; font-size: 11px;">Location</small>
                        <p style="color: #1a2035; margin: 0; font-weight: 500;">{{ $profile->location }}</p>
                    </div>
                </div>
                @endif
            </div>

            @if($user->username)
            <div class="mt-3 pt-2">
                <small style="color: #7a869a;">Public CV URL</small>
                <p style="font-weight: 600; color: #2f7bff; margin: 0;">
                    <i class="bi bi-link-45deg"></i>
                    <a href="{{ route('cv.show', $user->username) }}" target="_blank" style="color: #2f7bff; text-decoration: none;">
                        {{ config('app.url') }}/cv/{{ $user->username }}
                    </a>
                </p>
            </div>
            @endif

            <div class="mt-3">
                <small style="color: #7a869a;">Member since</small>
                <p style="font-weight: 600; color: #1a2035; margin: 0;">{{ $user->created_at->format('F d, Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Right Column - Edit Forms --}}
    <div class="col-lg-8 fade-up d2">

        {{-- Personal Information Form --}}
        <div class="card-box mb-4">
            <div class="section-title">
                <i class="bi bi-person-fill"></i>
                <span>Personal Information</span>
            </div>

            <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-dash">Full Name *</label>
                        <input type="text" name="name" class="form-control-dash" value="{{ old('name', $user->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Username</label>
                        <input type="text" name="username" class="form-control-dash" value="{{ old('username', $user->username) }}" placeholder="e.g. momen-sarsour">
                        <small class="text-muted-custom">Used for your public CV URL (must be unique)</small>
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Email Address *</label>
                        <input type="email" name="email" class="form-control-dash" value="{{ old('email', $user->email) }}" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Phone Number</label>
                        <input type="text" name="phone" class="form-control-dash" value="{{ old('phone', $profile->phone ?? '') }}" placeholder="+966 5X XXX XXXX">
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label-dash">Professional Title</label>
                        <input type="text" name="title" class="form-control-dash" value="{{ old('title', $profile->title ?? '') }}" placeholder="e.g. Full Stack Developer">
                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label-dash">Location</label>
                        <input type="text" name="location" class="form-control-dash" value="{{ old('location', $profile->location ?? '') }}" placeholder="e.g. Al-Hofuf, Saudi Arabia">
                        @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label-dash">Bio / About Me</label>
                        <textarea name="bio" class="form-control-dash" rows="4" placeholder="Tell us about yourself, your experience, and what you do...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        @error('bio') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label-dash">Profile Picture</label>
                        <input type="file" name="avatar" id="avatarFileInput" class="form-control-dash" accept="image/*">
                        <small class="text-muted-custom">Supported: JPG, PNG, GIF. Max 2MB</small>
                        @error('avatar') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="submit" class="btn-primary-dash">
                        <i class="bi bi-check-lg me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Change Password Form --}}
        <div class="card-box mb-4">
            <div class="section-title">
                <i class="bi bi-shield-lock-fill"></i>
                <span>Change Password</span>
            </div>

            <form method="POST" action="{{ route('dashboard.profile.password') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label-dash">Current Password</label>
                        <input type="password" name="current_password" class="form-control-dash" placeholder="Enter your current password" required>
                        @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">New Password</label>
                        <input type="password" name="new_password" class="form-control-dash" placeholder="Enter new password" required>
                        @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control-dash" placeholder="Confirm new password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="submit" class="btn-primary-dash">
                        <i class="bi bi-key-fill me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Danger Zone --}}
        <div class="card-box" style="border-color: #fee2e2;">
            <div class="section-title">
                <i class="bi bi-exclamation-triangle-fill" style="color: #ef4444;"></i>
                <span style="color: #ef4444;">Danger Zone</span>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <p style="font-weight: 600; color: #1a2035; margin-bottom: 4px;">Delete Account</p>
                            <small style="color: #7a869a;">Once you delete your account, there is no going back. All your data will be permanently removed.</small>
                        </div>
                        <button type="button" class="btn-danger-dash" onclick="confirmAccountDelete()">
                            <i class="bi bi-trash-fill me-1"></i> Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Account Modal --}}
<div class="modal fade modal-dash" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-exclamation-triangle-fill me-2" style="color: #ef4444;"></i>Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dashboard.profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p style="font-size:14px;color:#1a2035;">Are you sure you want to delete your account?</p>
                    <p style="font-size:13px;color:#7a869a;margin-bottom:16px;">This action cannot be undone. All your data will be permanently removed.</p>
                    <div class="mb-3">
                        <label class="form-label-dash">Enter your password to confirm</label>
                        <input type="password" name="password" class="form-control-dash" placeholder="••••••••" required>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-danger-dash"><i class="bi bi-trash-fill me-1"></i> Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Preview avatar before upload
    const avatarFileInput = document.getElementById('avatarFileInput');
    const avatarPreview = document.getElementById('avatarPreview');

    if (avatarFileInput && avatarPreview) {
        avatarFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Trigger file input when clicking on upload button
    const uploadBtn = document.querySelector('.avatar-upload-btn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            document.getElementById('avatarFileInput').click();
        });
    }

    function confirmAccountDelete() {
        new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
    }

    // Auto-open modal if validation errors for delete
    @if($errors->has('password'))
        new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
    @endif
</script>
@endpush
