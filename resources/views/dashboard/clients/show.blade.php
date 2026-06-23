@extends('layouts.dashboard')

@section('title', $user->name . ' - Profile')
@section('page-title', $user->name)
@section('page-subtitle', 'Complete user profile and portfolio')

@push('styles')
    <style>
        /* ============================================================
               PROFILE COVER
            ============================================================ */
        .profile-cover {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #2d3a5e 100%);
            border-radius: 28px;
            padding: 36px 36px 0 36px;
            position: relative;
            margin-bottom: 40px;
            /* overflow: hidden; */
        }

        .profile-cover::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(47, 123, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .profile-cover::after {
            content: '';
            position: absolute;
            bottom: 40px;
            left: 50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(99, 179, 237, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cover-bottom {
            height: 70px;
            position: relative;
        }

        .profile-avatar-lg {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            position: absolute;
            bottom: -45px;
            left: 6px;
            background: white;
            transition: transform 0.3s ease;
            z-index: 2;
        }

        .profile-avatar-lg:hover {
            transform: scale(1.04);
        }

        .profile-info {
            color: white;
            padding-bottom: 28px;
            position: relative;
            z-index: 1;
        }

        .profile-name {
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .profile-title {
            font-size: 15px;
            opacity: 0.85;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .profile-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 13px;
            opacity: 0.8;
        }

        .profile-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.08);
            padding: 5px 12px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }

        .profile-meta span:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* ============================================================
               STAT CARDS
            ============================================================ */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px 16px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1.5px solid #e8edf5;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #2f7bff, #63b3ed);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(47, 123, 255, 0.12);
            border-color: #c3d9ff;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-icon {
            font-size: 24px;
            color: #2f7bff;
            margin-bottom: 8px;
            opacity: 0.8;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 800;
            color: #2f7bff;
            line-height: 1.1;
        }

        .stat-label {
            font-size: 11px;
            color: #7a869a;
            margin-top: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* ============================================================
               SECTION CARDS
            ============================================================ */
        .section-card {
            background: white;
            border-radius: 24px;
            padding: 26px;
            margin-bottom: 24px;
            border: 1.5px solid #e8edf5;
            transition: box-shadow 0.3s ease;
        }

        .section-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            flex-wrap: wrap;
            gap: 12px;
            padding-bottom: 18px;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-title {
            font-size: 17px;
            font-weight: 800;
            color: #1a2035;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #2f7bff;
        }

        /* ============================================================
               BUTTON STYLES
            ============================================================ */
        .btn-group-action {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-add {
            background: linear-gradient(135deg, #2f7bff, #1a5fcc);
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.25s;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(47, 123, 255, 0.3);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(47, 123, 255, 0.4);
        }

        .btn-manage {
            background: #f4f6fb;
            color: #1a2035;
            border: 1.5px solid #e8edf5;
            padding: 8px 18px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-manage:hover {
            background: #e8edf5;
            transform: translateY(-2px);
            color: #1a2035;
        }

        /* ============================================================
               BADGES & TAGS
            ============================================================ */
        .skill-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1.5px solid #e2e8f0;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            margin: 5px;
            transition: all 0.2s;
            color: #1a2035;
        }

        .skill-badge:hover {
            background: #e8f0ff;
            border-color: #c3d9ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(47, 123, 255, 0.1);
        }

        .skill-level-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2f7bff;
            opacity: 0.6;
        }

        .tech-tag {
            display: inline-block;
            padding: 3px 10px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin: 3px;
            color: #2f7bff;
        }

        /* ============================================================
               ACTION BUTTONS
            ============================================================ */
        .action-btn-sm {
            padding: 7px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            background: transparent;
        }

        .btn-outline-primary {
            background: transparent;
            border: 1.5px solid #2f7bff;
            color: #2f7bff;
        }

        .btn-outline-primary:hover {
            background: #2f7bff;
            color: white;
            transform: translateY(-2px);
        }

        /* ============================================================
               QUICK ACTIONS SIDEBAR
            ============================================================ */
        .quick-actions {
            position: sticky;
            top: 90px;
            z-index: 10;
        }

        .quick-card {
            background: white;
            border-radius: 24px;
            padding: 20px;
            border: 1.5px solid #e8edf5;
            overflow: hidden;
        }

        .quick-title {
            font-size: 13px;
            font-weight: 800;
            color: #1a2035;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 14px;
            border-bottom: 2px solid #f1f5f9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quick-title i {
            color: #2f7bff;
            font-size: 16px;
        }

        .quick-section-label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 12px 0 6px 4px;
        }

        .quick-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #f8fafc;
            border-radius: 12px;
            text-decoration: none;
            color: #1a2035;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            border: 1.5px solid transparent;
            width: 100%;
            cursor: pointer;
            margin-bottom: 6px;
        }

        .quick-btn i {
            font-size: 15px;
            width: 22px;
            color: #2f7bff;
            text-align: center;
        }

        .quick-btn:hover {
            background: #2f7bff;
            color: white;
            border-color: #2f7bff;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(47, 123, 255, 0.25);
        }

        .quick-btn:hover i {
            color: white;
        }

        .quick-btn.danger {
            color: #ef4444;
        }

        .quick-btn.danger i {
            color: #ef4444;
        }

        .quick-btn.danger:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .quick-btn.danger:hover i {
            color: white;
        }

        /* ============================================================
               TIMELINE ITEMS
            ============================================================ */
        .timeline-item {
            margin-bottom: 20px;
            padding: 18px 20px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1.5px solid #f1f5f9;
            position: relative;
            transition: all 0.2s;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item:hover {
            border-color: #c3d9ff;
            background: #fafcff;
            box-shadow: 0 4px 16px rgba(47, 123, 255, 0.07);
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-bottom: 6px;
            gap: 8px;
            padding-right: 70px;
        }

        .item-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a2035;
        }

        .item-date {
            font-size: 11px;
            color: #94a3b8;
            background: white;
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            white-space: nowrap;
        }

        .item-subtitle {
            color: #2f7bff;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .item-description {
            font-size: 13px;
            color: #64748b;
            line-height: 1.7;
            margin: 0;
        }

        .item-actions {
            position: absolute;
            top: 16px;
            right: 16px;
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .timeline-item:hover .item-actions {
            opacity: 1;
        }

        .icon-btn {
            background: white;
            border: 1.5px solid #e2e8f0;
            padding: 6px 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 12px;
            line-height: 1;
        }

        .icon-btn.edit {
            color: #2f7bff;
        }

        .icon-btn.edit:hover {
            background: #2f7bff;
            border-color: #2f7bff;
            color: white;
        }

        .icon-btn.delete {
            color: #ef4444;
        }

        .icon-btn.delete:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }

        /* ============================================================
               PROJECT CARD
            ============================================================ */
        .project-card {
            background: white;
            border-radius: 18px;
            border: 1.5px solid #e8edf5;
            position: relative;
            transition: all 0.3s;
            height: 100%;
            overflow: hidden;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-color: #c3d9ff;
        }

        .project-card-body {
            padding: 16px;
        }

        .project-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a2035;
            margin-bottom: 8px;
            padding-right: 60px;
        }

        .project-description {
            font-size: 12px;
            color: #7a869a;
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .project-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .project-links a {
            font-size: 12px;
            text-decoration: none;
            color: #2f7bff;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            background: #eff6ff;
            border-radius: 20px;
            transition: all 0.2s;
        }

        .project-links a:hover {
            background: #2f7bff;
            color: white;
        }

        .card-actions {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 3;
        }

        .project-card:hover .card-actions {
            opacity: 1;
        }

        /* ============================================================
               SOCIAL LINK
            ============================================================ */
        .social-link-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            background: #f8fafc;
            border-radius: 40px;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s;
            position: relative;
        }

        .social-link-item:hover {
            background: #eff6ff;
            border-color: #c3d9ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(47, 123, 255, 0.1);
        }

        .social-link-item a {
            text-decoration: none;
            color: #1a2035;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 13px;
        }

        .social-actions {
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .social-link-item:hover .social-actions {
            opacity: 1;
        }

        /* ============================================================
               MODAL STYLES
            ============================================================ */
        .modal-dash .modal-content {
            border-radius: 22px;
            border: none;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .modal-dash .modal-header {
            background: linear-gradient(135deg, #0f172a, #1e3a5f);
            color: #fff;
            border-radius: 22px 22px 0 0;
            padding: 20px 26px;
            border: none;
        }

        .modal-dash .modal-header .btn-close {
            filter: invert(1);
            opacity: 0.7;
        }

        .modal-dash .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-dash .modal-body {
            padding: 28px;
            background: #fafbfc;
        }

        .modal-dash .modal-footer {
            padding: 16px 26px;
            border-top: 1.5px solid #e8edf5;
            background: white;
            border-radius: 0 0 22px 22px;
        }

        /* ============================================================
               FORM STYLES
            ============================================================ */
        .form-label-dash {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control-dash {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13.5px;
            transition: all 0.2s;
            width: 100%;
            background: white;
            color: #1a2035;
        }

        .form-control-dash:focus {
            border-color: #2f7bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.1);
            background: white;
        }

        textarea.form-control-dash {
            resize: vertical;
            min-height: 80px;
        }

        .btn-primary-dash {
            background: linear-gradient(135deg, #2f7bff, #1a5fcc);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(47, 123, 255, 0.3);
        }

        .btn-primary-dash:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(47, 123, 255, 0.4);
        }

        .btn-light-dash {
            background: #f4f6fb;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-light-dash:hover {
            background: #e8edf5;
            color: #1a2035;
        }

        .btn-danger-dash {
            background: #ef4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-danger-dash:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        /* ============================================================
               EMPTY STATE
            ============================================================ */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #94a3b8;
        }

        .empty-icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .empty-icon-wrap i {
            font-size: 32px;
            color: #cbd5e1;
        }

        .empty-state p {
            font-size: 13px;
            margin: 0;
            color: #94a3b8;
        }

        .empty-state h6 {
            font-size: 15px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 6px;
        }

        /* ============================================================
               CV TEMPLATE SELECTOR
            ============================================================ */
        .template-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1.5px solid #e2e8f0;
            margin-bottom: 10px;
            background: white;
        }

        .template-option:last-child {
            margin-bottom: 0;
        }

        .template-option:hover {
            border-color: #93c5fd;
            background: #f0f7ff;
        }

        .template-option.selected {
            background: linear-gradient(135deg, rgba(47, 123, 255, 0.06), rgba(47, 123, 255, 0.12));
            border-color: #2f7bff;
        }

        .template-name {
            font-weight: 600;
            color: #1a2035;
            font-size: 14px;
        }

        .template-badge {
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 20px;
            background: #2f7bff;
            color: white;
            font-weight: 700;
        }

        /* ============================================================
               SKILL BADGE ACTIONS
            ============================================================ */
        .skill-badge-wrap {
            position: relative;
            display: inline-flex;
        }

        .skill-badge-actions {
            display: none;
            position: absolute;
            top: -8px;
            right: -8px;
            gap: 3px;
        }

        .skill-badge-wrap:hover .skill-badge-actions {
            display: flex;
        }

        /* ============================================================
               RANGE INPUT CUSTOM
            ============================================================ */
        input[type="range"].form-control-dash {
            padding: 0;
            height: 6px;
            border-radius: 3px;
            background: #e2e8f0;
            border: none;
            accent-color: #2f7bff;
        }

        /* ============================================================
               ABOUT SECTION
            ============================================================ */
        .about-text {
            color: #475569;
            line-height: 1.8;
            font-size: 14px;
            padding: 16px 20px;
            background: #f8fafc;
            border-radius: 14px;
            border-left: 4px solid #2f7bff;
        }

        /* ============================================================
               PROGRESS MINI BAR (for skills)
            ============================================================ */
        .skill-progress {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .progress-mini {
            width: 40px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-mini-bar {
            height: 100%;
            background: linear-gradient(90deg, #2f7bff, #63b3ed);
            border-radius: 2px;
        }

        /* Toast notifications */
        .toast-container-custom {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
@endpush

@section('content')

    <div class="row g-4">
        {{-- Sidebar with Quick Actions --}}
        <div class="col-lg-3">
            <div class="quick-actions">
                <div class="quick-card">
                    <div class="quick-title">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span>Quick Actions</span>
                    </div>

                    <div class="quick-section-label">Profile</div>
                    <a href="{{ route('dashboard.clients.edit', $user->id) }}" class="quick-btn">
                        <i class="bi bi-pencil-fill"></i> Edit Profile
                    </a>
                    <button class="quick-btn" onclick="openModal('cvTemplateModal')">
                        <i class="bi bi-layout-three-columns"></i> CV Template
                    </button>

                    <div class="quick-section-label">Documents</div>
                    <a href="{{ route('dashboard.clients.preview-cv', $user->id) }}" target="_blank" class="quick-btn">
                        <i class="bi bi-eye-fill"></i> Preview CV
                    </a>
                    <a href="{{ route('dashboard.clients.download-cv', $user->id) }}" class="quick-btn">
                        <i class="bi bi-download"></i> Download CV
                    </a>

                    <div class="quick-section-label">Add New</div>
                    <button class="quick-btn" onclick="openModal('addSkillModal')">
                        <i class="bi bi-plus-circle-fill"></i> Add Skill
                    </button>
                    <button class="quick-btn" onclick="openModal('addExperienceModal')">
                        <i class="bi bi-plus-circle-fill"></i> Add Experience
                    </button>
                    <button class="quick-btn" onclick="openModal('addEducationModal')">
                        <i class="bi bi-plus-circle-fill"></i> Add Education
                    </button>
                    <button class="quick-btn" onclick="openModal('addProjectModal')">
                        <i class="bi bi-plus-circle-fill"></i> Add Project
                    </button>

                    @if ($user->id !== auth()->id())
                        <div style="height: 1px; background: #f1f5f9; margin: 12px 0;"></div>
                        <button class="quick-btn danger"
                            onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                            <i class="bi bi-trash-fill"></i> Delete User
                        </button>
                    @endif
                </div>

                {{-- Profile Completion --}}
                @php
                    $completion = 0;
                    if ($profile && $profile->bio) {
                        $completion += 20;
                    }
                    if ($profile && $profile->avatar) {
                        $completion += 10;
                    }
                    if ($skills->count() > 0) {
                        $completion += 20;
                    }
                    if ($experiences->count() > 0) {
                        $completion += 20;
                    }
                    if ($education->count() > 0) {
                        $completion += 15;
                    }
                    if ($projects->count() > 0) {
                        $completion += 15;
                    }
                @endphp
                <div class="quick-card mt-3">
                    <div class="quick-title">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Profile Score</span>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:13px; color:#64748b; font-weight:600;">Completion</span>
                            <span style="font-size:15px; font-weight:800; color:#2f7bff;">{{ $completion }}%</span>
                        </div>
                        <div style="height:8px; background:#f1f5f9; border-radius:4px; overflow:hidden;">
                            <div
                                style="height:100%; width:{{ $completion }}%; background:linear-gradient(90deg,#2f7bff,#63b3ed); border-radius:4px; transition:width 0.5s;">
                            </div>
                        </div>
                    </div>
                    <div style="font-size:12px; color:#94a3b8; line-height:1.6;">
                        @if ($completion < 100)
                            Add more info to reach 100% ✨
                        @else
                            Profile is fully complete! 🎉
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
            {{-- Profile Cover --}}
            <div class="profile-cover">
                @php
                    $avatarUrl =
                        $profile && $profile->avatar
                            ? asset('storage/' . $profile->avatar)
                            : 'https://ui-avatars.com/api/?background=2f7bff&color=ffffff&size=140&name=' .
                                urlencode($user->name);
                @endphp
                <div class="profile-info">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    @if ($profile && $profile->title)
                        <div class="profile-title"><i class="bi bi-briefcase me-1"></i>{{ $profile->title }}</div>
                    @endif
                    <div class="profile-meta">
                        @if ($profile && $profile->location)
                            <span><i class="bi bi-geo-alt-fill"></i> {{ $profile->location }} </span>
                        @endif
                        @if ($profile && $profile->email)
                            <span><i class="bi bi-envelope-fill"></i> {{ $profile->email }}</span>
                        @endif
                        @if ($profile && $profile->phone)
                            <span><i class="bi bi-telephone-fill"></i> {{ $profile->phone }}</span>
                        @endif
                        <span><i class="bi bi-calendar-fill"></i> {{ $user->created_at->format('M Y') }}</span>
                        @if ($user->username)
                            <span><i class="bi bi-link-45deg"></i> /cv/{{ $user->username }}</span>
                        @endif
                    </div>
                </div>
                <div class="cover-bottom">
                    <img src="{{ $avatarUrl }}" class="profile-avatar-lg" alt="{{ $user->name }}">
                </div>
            </div>

            {{-- Stats Row --}}
            <div class="row g-3 mb-4" style="margin-top: 10px;">
                <div class="col-md-3 col-6">
                    <a href="{{ route('dashboard.projects.index') }}?user_id={{ $user->id }}"
                        style="text-decoration: none;">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-folder2-open"></i></div>
                            <div class="stat-number">{{ $projects->count() }}</div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="{{ route('dashboard.skills.index') }}?user_id={{ $user->id }}"
                        style="text-decoration: none;">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                            <div class="stat-number">{{ $skills->count() }}</div>
                            <div class="stat-label">Skills</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="{{ route('dashboard.experiences.index') }}?user_id={{ $user->id }}"
                        style="text-decoration: none;">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div>
                            <div class="stat-number">{{ $experiences->count() }}</div>
                            <div class="stat-label">Experiences</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="{{ route('dashboard.education.index') }}?user_id={{ $user->id }}"
                        style="text-decoration: none;">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-mortarboard-fill"></i></div>
                            <div class="stat-number">{{ $education->count() }}</div>
                            <div class="stat-label">Education</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Bio Section --}}
            @if ($profile && $profile->bio)
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <div class="section-title-icon"><i class="bi bi-person-lines-fill"></i></div>
                            <span>About</span>
                        </div>
                        <a href="{{ route('dashboard.clients.edit', $user->id) }}"
                            class="action-btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </a>
                    </div>
                    <p class="about-text">{{ $profile->bio }}</p>
                </div>
            @endif

            {{-- Skills Section --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-title-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                        <span>Skills</span>
                        <span
                            style="font-size:12px; background:#eff6ff; color:#2f7bff; padding:3px 10px; border-radius:20px; font-weight:600;">{{ $skills->count() }}</span>
                    </div>
                    <div class="btn-group-action">
                        <button class="btn-add" onclick="openModal('addSkillModal')">
                            <i class="bi bi-plus-lg"></i> Add Skill
                        </button>
                        <a href="{{ route('dashboard.skills.index') }}?user_id={{ $user->id }}" class="btn-manage">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Manage All
                        </a>
                    </div>
                </div>
                @if ($skills->count() > 0)
                    <div>
                        @foreach ($skills as $skill)
                            <span class="skill-badge">
                                <span>{{ $skill->name }}</span>
                                @if ($skill->level)
                                    <span class="skill-progress">
                                        <div class="progress-mini">
                                            <div class="progress-mini-bar" style="width:{{ $skill->level }}%"></div>
                                        </div>
                                        <span
                                            style="color: #2f7bff; font-size: 11px; font-weight:700;">{{ $skill->level }}%</span>
                                    </span>
                                @endif
                                <span style="display:inline-flex; gap:3px; margin-left:4px;">
                                    <button class="icon-btn edit" onclick="editItem('skill', {{ $skill->id }})"
                                        title="Edit" style="padding:3px 6px;">
                                        <i class="bi bi-pencil-fill" style="font-size: 10px;"></i>
                                    </button>
                                    <button class="icon-btn delete"
                                        onclick="deleteItem('skill', {{ $skill->id }}, '{{ addslashes($skill->name) }}')"
                                        title="Delete" style="padding:3px 6px;">
                                        <i class="bi bi-trash-fill" style="font-size: 10px;"></i>
                                    </button>
                                </span>
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon-wrap"><i class="bi bi-lightning-charge"></i></div>
                        <h6>No Skills Yet</h6>
                        <p>Click "Add Skill" to showcase your expertise.</p>
                    </div>
                @endif
            </div>

            {{-- Experience Section --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-title-icon"><i class="bi bi-briefcase-fill"></i></div>
                        <span>Work Experience</span>
                        <span
                            style="font-size:12px; background:#eff6ff; color:#2f7bff; padding:3px 10px; border-radius:20px; font-weight:600;">{{ $experiences->count() }}</span>
                    </div>
                    <div class="btn-group-action">
                        <button class="btn-add" onclick="openModal('addExperienceModal')">
                            <i class="bi bi-plus-lg"></i> Add
                        </button>
                        <a href="{{ route('dashboard.experiences.index') }}?user_id={{ $user->id }}"
                            class="btn-manage">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Manage All
                        </a>
                    </div>
                </div>
                @if ($experiences->count() > 0)
                    @foreach ($experiences as $exp)
                        <div class="timeline-item">
                            <div class="item-actions">
                                <button class="icon-btn edit" onclick="editItem('experience', {{ $exp->id }})"
                                    title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="icon-btn delete"
                                    onclick="deleteItem('experience', {{ $exp->id }}, '{{ addslashes($exp->job_title) }}')"
                                    title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                            <div class="item-header">
                                <span class="item-title">{{ $exp->job_title }}</span>
                                <span class="item-date">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} —
                                    {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                                </span>
                            </div>
                            <div class="item-subtitle"><i class="bi bi-building"></i> {{ $exp->company }}</div>
                            @if ($exp->description)
                                <p class="item-description">{{ $exp->description }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-icon-wrap"><i class="bi bi-briefcase"></i></div>
                        <h6>No Experience Yet</h6>
                        <p>Click "Add" to add your work history.</p>
                    </div>
                @endif
            </div>

            {{-- Education Section --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-title-icon"><i class="bi bi-mortarboard-fill"></i></div>
                        <span>Education</span>
                        <span
                            style="font-size:12px; background:#eff6ff; color:#2f7bff; padding:3px 10px; border-radius:20px; font-weight:600;">{{ $education->count() }}</span>
                    </div>
                    <div class="btn-group-action">
                        <button class="btn-add" onclick="openModal('addEducationModal')">
                            <i class="bi bi-plus-lg"></i> Add
                        </button>
                        <a href="{{ route('dashboard.education.index') }}?user_id={{ $user->id }}"
                            class="btn-manage">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Manage All
                        </a>
                    </div>
                </div>
                @if ($education->count() > 0)
                    @foreach ($education as $edu)
                        <div class="timeline-item">
                            <div class="item-actions">
                                <button class="icon-btn edit" onclick="editItem('education', {{ $edu->id }})"
                                    title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="icon-btn delete"
                                    onclick="deleteItem('education', {{ $edu->id }}, '{{ addslashes($edu->degree) }}')"
                                    title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                            <div class="item-header">
                                <span class="item-title">{{ $edu->degree }}</span>
                                <span class="item-date">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} —
                                    {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
                                </span>
                            </div>
                            <div class="item-subtitle"><i class="bi bi-bank"></i> {{ $edu->university }}</div>
                            @if ($edu->description)
                                <p class="item-description">{{ $edu->description }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-icon-wrap"><i class="bi bi-mortarboard"></i></div>
                        <h6>No Education Yet</h6>
                        <p>Click "Add" to add your academic background.</p>
                    </div>
                @endif
            </div>

            {{-- Projects Section --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-title-icon"><i class="bi bi-folder2-open"></i></div>
                        <span>Projects</span>
                        <span
                            style="font-size:12px; background:#eff6ff; color:#2f7bff; padding:3px 10px; border-radius:20px; font-weight:600;">{{ $projects->count() }}</span>
                    </div>
                    <div class="btn-group-action">
                        <button class="btn-add" onclick="openModal('addProjectModal')">
                            <i class="bi bi-plus-lg"></i> Add
                        </button>
                        <a href="{{ route('dashboard.projects.index') }}?user_id={{ $user->id }}"
                            class="btn-manage">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Manage All
                        </a>
                    </div>
                </div>
                @if ($projects->count() > 0)
                    <div class="row g-3">
                        @foreach ($projects as $project)
                            <div class="col-md-6">
                                <div class="project-card">
                                    <div class="card-actions">
                                        <button class="icon-btn edit" onclick="editItem('project', {{ $project->id }})"
                                            title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="icon-btn delete"
                                            onclick="deleteItem('project', {{ $project->id }}, '{{ addslashes($project->title) }}')"
                                            title="Delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                    @if ($project->image)
                                        <img src="{{ asset('storage/' . $project->image) }}"
                                            style="width:100%; height:180px; object-fit:cover;"
                                            alt="{{ $project->title }}">
                                    @else
                                        <div
                                            style="height:180px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); display:flex; align-items:center; justify-content:center;">
                                            <i class="bi bi-folder2-open"
                                                style="font-size:40px; color:rgba(255,255,255,0.5);"></i>
                                        </div>
                                    @endif
                                    <div class="project-card-body">
                                        <h4 class="project-title">{{ $project->title }}</h4>
                                        <p class="project-description">{{ Str::limit($project->description, 90) }}</p>
                                        @if ($project->technologies)
                                            <div class="mb-3">
                                                @foreach (array_slice(explode(',', $project->technologies), 0, 4) as $tech)
                                                    <span class="tech-tag">{{ trim($tech) }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="project-links">
                                            @if ($project->github_link)
                                                <a href="{{ $project->github_link }}" target="_blank"><i
                                                        class="bi bi-github"></i> GitHub</a>
                                            @endif
                                            @if ($project->demo_link)
                                                <a href="{{ $project->demo_link }}" target="_blank"><i
                                                        class="bi bi-box-arrow-up-right"></i> Live Demo</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon-wrap"><i class="bi bi-folder2-open"></i></div>
                        <h6>No Projects Yet</h6>
                        <p>Click "Add" to showcase your work.</p>
                    </div>
                @endif
            </div>

            {{-- Social Links Section --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-title-icon"><i class="bi bi-share-fill"></i></div>
                        <span>Social Links</span>
                    </div>
                    <div class="btn-group-action">
                        <button class="btn-add" onclick="openModal('addSocialLinkModal')">
                            <i class="bi bi-plus-lg"></i> Add Link
                        </button>
                        <a href="{{ route('dashboard.social-links.index') }}?user_id={{ $user->id }}"
                            class="btn-manage">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Manage All
                        </a>
                    </div>
                </div>
                @if ($socialLinks->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($socialLinks as $link)
                            <div class="social-link-item">
                                <a href="{{ $link->url }}" target="_blank">
                                    <i
                                        class="bi bi-{{ strtolower($link->platform) == 'github' ? 'github' : (strtolower($link->platform) == 'linkedin' ? 'linkedin' : (strtolower($link->platform) == 'twitter' ? 'twitter-x' : 'share-fill')) }}"></i>
                                    <span>{{ ucfirst($link->platform) }}</span>
                                </a>
                                <div class="social-actions">
                                    <button class="icon-btn edit" onclick="editItem('socialLink', {{ $link->id }})"
                                        title="Edit" style="padding:4px 6px;">
                                        <i class="bi bi-pencil-fill" style="font-size: 11px;"></i>
                                    </button>
                                    <button class="icon-btn delete"
                                        onclick="deleteItem('socialLink', {{ $link->id }}, '{{ addslashes($link->platform) }}')"
                                        title="Delete" style="padding:4px 6px;">
                                        <i class="bi bi-x-lg" style="font-size: 11px;"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon-wrap"><i class="bi bi-share"></i></div>
                        <h6>No Social Links Yet</h6>
                        <p>Click "Add Link" to connect your social profiles.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- ============================================================ --}}
    {{-- CV TEMPLATE MODAL --}}
    {{-- ============================================================ --}}
    <div class="modal fade modal-dash" id="cvTemplateModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-layout-three-columns me-2"></i>Change CV Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.clients.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="update_template_only" value="1">
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <input type="hidden" name="username" value="{{ $user->username }}">

                    <div class="modal-body">
                        <p class="mb-3" style="color: #7a869a; font-size:14px;">Select a design template for
                            <strong>{{ $user->name }}</strong>'s CV
                        </p>

                        <div>
                            {{-- @dump([
                                $templates->all()
                            ]) --}}
                            @foreach ($templates as $template)
                                <div class="template-option {{ $user->cvTemplate && $user->cvTemplate->id == $template->id ? 'selected' : '' }}"
                                    onclick="selectTemplateById('{{ $template->id }}', this)">
                                    <span class="template-name">
                                        @if ($template->is_system)
                                            <i class="bi bi-star-fill" style="color: #f59e0b; font-size: 12px;"></i>
                                        @else
                                            <i class="bi bi-plus-circle-fill"
                                                style="color: #2f7bff; font-size: 12px;"></i>
                                        @endif
                                        {{ $template->name }}
                                    </span>
                                    <div>
                                        @if ($user->cvTemplate && $user->cvTemplate->id == $template->id)
                                            <span class="template-badge"
                                                style="background: #d1fae5; color: #065f46;">Current</span>
                                        @endif
                                        @if ($template->is_default)
                                            <span class="template-badge"
                                                style="background: #dbeafe; color: #1e40af;">Default</span>
                                        @endif
                                        @if ($template->is_system)
                                            <span class="template-badge"
                                                style="background: #fef3c7; color: #92400e;">System</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <input type="hidden" name="cv_template_id" id="selectedTemplateId"
                            value="{{ $user->cv_template_id ?? '' }}">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save
                            Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ADD MODALS --}}
    {{-- ============================================================ --}}

    {{-- Add Project Modal --}}
    <div class="modal fade modal-dash" id="addProjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-folder-plus me-2"></i>Add Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.projects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label-dash">Project Title *</label>
                                <input type="text" name="title" class="form-control-dash" required
                                    placeholder="My Awesome Project">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Category *</label>
                                <select name="category" class="form-control-dash" required>
                                    <option value="Laravel/PHP">Laravel / PHP</option>
                                    <option value="Web">Web</option>
                                    <option value="Java/Flutter">Java / Flutter</option>
                                    <option value="C++">C++</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Description *</label>
                                <textarea name="description" class="form-control-dash" rows="3" required
                                    placeholder="Describe your project..."></textarea>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label-dash">Technologies</label>
                                <input type="text" name="technologies" class="form-control-dash"
                                    placeholder="Laravel, MySQL, Bootstrap">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control-dash" value="0"
                                    min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Project Image</label>
                                <input type="file" name="image" class="form-control-dash" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">GitHub URL</label>
                                <input type="url" name="github_link" class="form-control-dash"
                                    placeholder="https://github.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Demo URL</label>
                                <input type="url" name="demo_link" class="form-control-dash"
                                    placeholder="https://...">
                            </div>
                            <div class="col-12">
                                <label class="d-flex align-items-center gap-2"
                                    style="cursor:pointer; font-size:13px; color:#475569; font-weight:600;">
                                    <input type="checkbox" name="is_active" value="1" checked> Show on portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save
                            Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Skill Modal --}}
    <div class="modal fade modal-dash" id="addSkillModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-lightning-charge me-2"></i>Add Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.skills.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label-dash">Skill Name *</label>
                                <input type="text" name="name" class="form-control-dash" required
                                    placeholder="e.g. Laravel, React, Python...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Category</label>
                                <select name="category" class="form-control-dash">
                                    <option value="">-- Select --</option>
                                    <option value="Frontend">Frontend</option>
                                    <option value="Backend">Backend</option>
                                    <option value="Database">Database</option>
                                    <option value="DevOps">DevOps</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Skill Level (0-100%)</label>
                                <input type="range" name="level" class="form-control-dash" min="0"
                                    max="100" value="50"
                                    oninput="this.nextElementSibling.textContent = this.value + '%'">
                                <div
                                    style="text-align:center; margin-top:8px; font-weight:700; color:#2f7bff; font-size:18px;">
                                    50%</div>
                            </div>
                            <div class="col-12">
                                <label class="d-flex align-items-center gap-2"
                                    style="cursor:pointer; font-size:13px; color:#475569; font-weight:600;">
                                    <input type="checkbox" name="is_active" value="1" checked> Show on portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save
                            Skill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Experience Modal --}}
    <div class="modal fade modal-dash" id="addExperienceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-briefcase me-2"></i>Add Experience</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.experiences.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-dash">Job Title *</label>
                                <input type="text" name="job_title" class="form-control-dash" required
                                    placeholder="Senior Developer">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Company *</label>
                                <input type="text" name="company" class="form-control-dash" required
                                    placeholder="Company Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Start Date *</label>
                                <input type="date" name="start_date" class="form-control-dash" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">End Date</label>
                                <input type="date" name="end_date" class="form-control-dash">
                                <small style="color:#94a3b8; font-size:11px;">Leave empty if currently working here</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Description</label>
                                <textarea name="description" class="form-control-dash" rows="3"
                                    placeholder="Describe your responsibilities..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control-dash" value="0"
                                    min="0">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <label class="d-flex align-items-center gap-2 mb-0"
                                    style="cursor:pointer; font-size:13px; color:#475569; font-weight:600; padding-bottom:10px;">
                                    <input type="checkbox" name="is_active" value="1" checked> Show on portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save
                            Experience</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Education Modal --}}
    <div class="modal fade modal-dash" id="addEducationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-mortarboard me-2"></i>Add Education</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.education.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-dash">Degree *</label>
                                <input type="text" name="degree" class="form-control-dash" required
                                    placeholder="B.Sc. Computer Science">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">University *</label>
                                <input type="text" name="university" class="form-control-dash" required
                                    placeholder="University Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Start Date *</label>
                                <input type="date" name="start_date" class="form-control-dash" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">End Date</label>
                                <input type="date" name="end_date" class="form-control-dash">
                                <small style="color:#94a3b8; font-size:11px;">Leave empty if currently studying</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Description</label>
                                <textarea name="description" class="form-control-dash" rows="3" placeholder="Majors, achievements, GPA..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control-dash" value="0"
                                    min="0">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <label class="d-flex align-items-center gap-2 mb-0"
                                    style="cursor:pointer; font-size:13px; color:#475569; font-weight:600; padding-bottom:10px;">
                                    <input type="checkbox" name="is_active" value="1" checked> Show on portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save
                            Education</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Social Link Modal --}}
    <div class="modal fade modal-dash" id="addSocialLinkModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-share me-2"></i>Add Social Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.social-links.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label-dash">Platform *</label>
                                <select name="platform" class="form-control-dash" required>
                                    <option value="github">GitHub</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="twitter">Twitter / X</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="telegram">Telegram</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label-dash">URL *</label>
                                <input type="url" name="url" class="form-control-dash" placeholder="https://..."
                                    required>
                            </div>
                            <div class="col-12">
                                <label class="d-flex align-items-center gap-2"
                                    style="cursor:pointer; font-size:13px; color:#475569; font-weight:600;">
                                    <input type="checkbox" name="is_active" value="1" checked> Show on portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save
                            Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- EDIT MODALS (Dynamic - Filled via JavaScript) --}}
    {{-- ============================================================ --}}

    {{-- Edit Project Modal --}}
    <div class="modal fade modal-dash" id="editProjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editProjectForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editProjectBody">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update
                            Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Skill Modal --}}
    <div class="modal fade modal-dash" id="editSkillModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSkillForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editSkillBody">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update
                            Skill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Experience Modal --}}
    <div class="modal fade modal-dash" id="editExperienceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Experience</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editExperienceForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editExperienceBody">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update
                            Experience</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Education Modal --}}
    <div class="modal fade modal-dash" id="editEducationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Education</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editEducationForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editEducationBody">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update
                            Education</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Social Link Modal --}}
    <div class="modal fade modal-dash" id="editSocialLinkModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Social Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSocialLinkForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editSocialLinkBody">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update Social
                            Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade modal-dash" id="deleteItemModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-trash-fill me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" style="padding: 32px 24px;">
                    <div
                        style="width:70px; height:70px; background:#fee2e2; border-radius:20px; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:30px; color:#ef4444;">
                        <i class="bi bi-trash-fill"></i>
                    </div>
                    <p style="font-size: 15px; color: #1a2035; margin-bottom: 6px; font-weight:600;">Delete <strong
                            id="deleteItemName"></strong>?</p>
                    <p style="font-size: 12px; color: #94a3b8; margin:0;">This action cannot be undone.</p>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-2">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteItemForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger-dash"><i class="bi bi-trash-fill me-1"></i>
                            Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete User Modal --}}
    <div class="modal fade modal-dash" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-x-fill me-2"></i>Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" style="padding: 32px 24px;">
                    <div
                        style="width:70px; height:70px; background:#fee2e2; border-radius:20px; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:30px; color:#ef4444;">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <p style="font-size: 15px; color: #1a2035; margin-bottom: 6px; font-weight:600;">Delete <strong
                            id="delete_user_name"></strong>?</p>
                    <p style="font-size: 12px; color: #94a3b8; margin:0;">All user data will be permanently removed.</p>
                </div>
                <div class="modal-footer d-flex justify-content-center gap-2">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteUserForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger-dash"><i class="bi bi-trash-fill me-1"></i> Delete
                            User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ============================================================
        // MODAL HELPERS
        // ============================================================
        function openModal(id) {
            new bootstrap.Modal(document.getElementById(id)).show();
        }

        function openDeleteModal(id, name) {
            document.getElementById('delete_user_name').innerText = name;
            document.getElementById('deleteUserForm').action = `/dashboard/clients/${id}`;
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        }

        // ============================================================
        // TEMPLATE SELECTOR
        // ============================================================
        function selectTemplate(template, el) {
            document.getElementById('selectedTemplate').value = template;
            document.querySelectorAll('.template-option').forEach(opt => {
                opt.classList.remove('selected');
                const badge = opt.querySelector('.template-badge');
                if (badge && badge.innerText === 'Selected') badge.remove();
            });
            el.classList.add('selected');
            if (!el.querySelector('.template-badge')) {
                el.insertAdjacentHTML('beforeend', '<span class="template-badge">Selected</span>');
            }
        }

        // ============================================================
        // DELETE ITEM
        // ============================================================
        function deleteItem(type, id, name) {
            const routes = {
                project: `/dashboard/projects/${id}`,
                skill: `/dashboard/skills/${id}`,
                experience: `/dashboard/experiences/${id}`,
                education: `/dashboard/education/${id}`,
                socialLink: `/dashboard/social-links/${id}`,
            };
            document.getElementById('deleteItemName').innerText = name;
            document.getElementById('deleteItemForm').action = routes[type] || '#';
            new bootstrap.Modal(document.getElementById('deleteItemModal')).show();
        }

        // ============================================================
        // EDIT ITEM — Fixed URL for education (no trailing 's')
        // ============================================================
        function getEditUrl(type, id) {
            const map = {
                project: `/dashboard/projects/${id}`,
                skill: `/dashboard/skills/${id}`,
                experience: `/dashboard/experiences/${id}`,
                education: `/dashboard/education/${id}`, // ✅ no extra 's'
                socialLink: `/dashboard/social-links/${id}`,
            };
            return map[type] || null;
        }

        function buildHtml(type, item) {
            const esc = escapeHtml;
            switch (type) {
                case 'project':
                    return `<div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label-dash">Project Title *</label>
                        <input type="text" name="title" class="form-control-dash" value="${esc(item.title)}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-dash">Category *</label>
                        <select name="category" class="form-control-dash" required>
                            ${['Laravel/PHP','Web','Java/Flutter','C++'].map(c => `<option value="${c}" ${item.category==c?'selected':''}>${c}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-dash">Description *</label>
                        <textarea name="description" class="form-control-dash" rows="3" required>${esc(item.description)}</textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label-dash">Technologies</label>
                        <input type="text" name="technologies" class="form-control-dash" value="${esc(item.technologies||'')}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-dash">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control-dash" value="${item.sort_order||0}" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">GitHub URL</label>
                        <input type="url" name="github_link" class="form-control-dash" value="${esc(item.github_link||'')}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Demo URL</label>
                        <input type="url" name="demo_link" class="form-control-dash" value="${esc(item.demo_link||'')}">
                    </div>
                    <div class="col-12">
                        <label class="d-flex align-items-center gap-2" style="cursor:pointer; font-size:13px; font-weight:600; color:#475569;">
                            <input type="checkbox" name="is_active" value="1" ${item.is_active?'checked':''}> Show on portfolio
                        </label>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                </div>`;

                case 'skill':
                    return `<div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label-dash">Skill Name *</label>
                        <input type="text" name="name" class="form-control-dash" value="${esc(item.name)}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-dash">Category</label>
                        <select name="category" class="form-control-dash">
                            <option value="">-- Select --</option>
                            ${['Frontend','Backend','Database','DevOps','Mobile','Other'].map(c => `<option value="${c}" ${item.category==c?'selected':''}>${c}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-dash">Skill Level (0–100%)</label>
                        <input type="range" name="level" class="form-control-dash" min="0" max="100" value="${item.level||50}"
                            oninput="this.nextElementSibling.textContent = this.value + '%'">
                        <div style="text-align:center; margin-top:8px; font-weight:700; color:#2f7bff; font-size:18px;">${item.level||50}%</div>
                    </div>
                    <div class="col-12">
                        <label class="d-flex align-items-center gap-2" style="cursor:pointer; font-size:13px; font-weight:600; color:#475569;">
                            <input type="checkbox" name="is_active" value="1" ${item.is_active?'checked':''}> Show on portfolio
                        </label>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                </div>`;

                case 'experience':
                    return `<div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-dash">Job Title *</label>
                        <input type="text" name="job_title" class="form-control-dash" value="${esc(item.job_title)}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Company *</label>
                        <input type="text" name="company" class="form-control-dash" value="${esc(item.company)}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Start Date *</label>
                        <input type="date" name="start_date" class="form-control-dash" value="${item.start_date||''}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">End Date</label>
                        <input type="date" name="end_date" class="form-control-dash" value="${item.end_date||''}">
                        <small style="color:#94a3b8; font-size:11px;">Leave empty if currently working</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label-dash">Description</label>
                        <textarea name="description" class="form-control-dash" rows="3">${esc(item.description||'')}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control-dash" value="${item.sort_order||0}" min="0">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer; font-size:13px; font-weight:600; color:#475569; padding-bottom:10px;">
                            <input type="checkbox" name="is_active" value="1" ${item.is_active?'checked':''}> Show on portfolio
                        </label>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                </div>`;

                case 'education':
                    return `<div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-dash">Degree *</label>
                        <input type="text" name="degree" class="form-control-dash" value="${esc(item.degree)}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">University *</label>
                        <input type="text" name="university" class="form-control-dash" value="${esc(item.university)}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Start Date *</label>
                        <input type="date" name="start_date" class="form-control-dash" value="${item.start_date||''}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">End Date</label>
                        <input type="date" name="end_date" class="form-control-dash" value="${item.end_date||''}">
                        <small style="color:#94a3b8; font-size:11px;">Leave empty if currently studying</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label-dash">Description</label>
                        <textarea name="description" class="form-control-dash" rows="3">${esc(item.description||'')}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-dash">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control-dash" value="${item.sort_order||0}" min="0">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer; font-size:13px; font-weight:600; color:#475569; padding-bottom:10px;">
                            <input type="checkbox" name="is_active" value="1" ${item.is_active?'checked':''}> Show on portfolio
                        </label>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                </div>`;

                case 'socialLink':
                    return `<div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label-dash">Platform *</label>
                        <select name="platform" class="form-control-dash" required>
                            ${['github','linkedin','twitter','facebook','instagram','youtube','whatsapp','telegram','other']
                                .map(p => `<option value="${p}" ${item.platform==p?'selected':''}>${p.charAt(0).toUpperCase()+p.slice(1)}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label-dash">URL *</label>
                        <input type="url" name="url" class="form-control-dash" value="${esc(item.url)}" required>
                    </div>
                    <div class="col-12">
                        <label class="d-flex align-items-center gap-2" style="cursor:pointer; font-size:13px; font-weight:600; color:#475569;">
                            <input type="checkbox" name="is_active" value="1" ${item.is_active?'checked':''}> Show on portfolio
                        </label>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                </div>`;

                default:
                    return '<p>Unknown type</p>';
            }
        }

        const modalMap = {
            project: {
                modal: 'editProjectModal',
                form: 'editProjectForm',
                body: 'editProjectBody'
            },
            skill: {
                modal: 'editSkillModal',
                form: 'editSkillForm',
                body: 'editSkillBody'
            },
            experience: {
                modal: 'editExperienceModal',
                form: 'editExperienceForm',
                body: 'editExperienceBody'
            },
            education: {
                modal: 'editEducationModal',
                form: 'editEducationForm',
                body: 'editEducationBody'
            },
            socialLink: {
                modal: 'editSocialLinkModal',
                form: 'editSocialLinkForm',
                body: 'editSocialLinkBody'
            },
        };

        function editItem(type, id) {
            const cfg = modalMap[type];
            if (!cfg) return;

            // Show modal with spinner immediately
            new bootstrap.Modal(document.getElementById(cfg.modal)).show();
            document.getElementById(cfg.body).innerHTML =
                '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-3 text-muted" style="font-size:13px;">Loading...</p></div>';

            const url = getEditUrl(type, id);
            if (!url) return;

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed to load');

                    // The response key might be 'education' or 'educations' — handle both
                    const item = data[type] || data[type + 's'] || data['education'] || null;
                    if (!item) throw new Error('Item not found in response');

                    document.getElementById(cfg.form).action = url;
                    document.getElementById(cfg.body).innerHTML = buildHtml(type, item);
                })
                .catch(err => {
                    document.getElementById(cfg.body).innerHTML =
                        `<div class="text-center py-4">
                        <div style="font-size:36px; color:#ef4444; margin-bottom:12px;"><i class="bi bi-exclamation-circle-fill"></i></div>
                        <p style="color:#64748b; font-size:14px;">Failed to load item.<br><small>${err.message}</small></p>
                        <button class="btn-light-dash mt-2" onclick="editItem('${type}', ${id})">
                            <i class="bi bi-arrow-clockwise me-1"></i> Retry
                        </button>
                    </div>`;
                });
        }

        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const div = document.createElement('div');
            div.textContent = String(text);
            return div.innerHTML;
        }

        function selectTemplateById(templateId, element) {
            document.querySelectorAll('.template-option').forEach(opt => {
                opt.classList.remove('selected');
                const badge = opt.querySelector('.template-badge');
                if (badge && badge.innerText === 'Selected') badge.remove();
            });
            element.classList.add('selected');
            if (!element.querySelector('.template-badge')) {
                element.insertAdjacentHTML('beforeend', '<span class="template-badge">Selected</span>');
            }
            document.getElementById('selectedTemplateId').value = templateId;
        }
    </script>
@endpush
