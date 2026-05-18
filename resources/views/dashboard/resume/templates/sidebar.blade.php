<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - CV</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f1f5f9;
            padding: 40px;
        }

        .cv-container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
            flex-wrap: wrap;
        }

        .sidebar {
            width: 33%;
            background: #1e293b;
            color: white;
            padding: 40px 30px;
        }

        .main-content {
            width: 67%;
            padding: 40px;
        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #2f7bff;
            margin-bottom: 25px;
        }

        .sidebar-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .sidebar-title {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #334155;
        }

        .sidebar-section {
            margin-bottom: 30px;
        }

        .sidebar-section-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2f7bff;
            margin-bottom: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 13px;
            color: #cbd5e1;
        }

        .skill-tag {
            display: inline-block;
            background: #334155;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin: 4px;
        }

        .main-section {
            margin-bottom: 30px;
        }

        .main-section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #2f7bff;
            display: inline-block;
        }

        .bio-text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 25px;
        }

        .experience-item, .education-item {
            margin-bottom: 25px;
        }

        .item-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .item-subtitle {
            font-size: 13px;
            color: #2f7bff;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .item-date {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .item-description {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
        }

        .project-item {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .project-title {
            font-size: 14px;
            font-weight: 700;
            color: #2f7bff;
            margin-bottom: 6px;
        }

        @media (max-width: 768px) {
            .sidebar, .main-content {
                width: 100%;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .sidebar {
                background: #1e293b;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="cv-container">
        <div class="sidebar">
            @if($profile && $profile->avatar)
                <img src="{{ asset('storage/' . $profile->avatar) }}" class="avatar" alt="{{ $user->name }}">
            @endif
            <div class="sidebar-name">{{ $user->name }}</div>
            @if($profile && $profile->title)
                <div class="sidebar-title">{{ $profile->title }}</div>
            @endif

            <div class="sidebar-section">
                <div class="sidebar-section-title">Contact</div>
                @if($profile && $profile->email)
                    <div class="contact-item">
                        <span>✉️</span> {{ $profile->email }}
                    </div>
                @endif
                @if($profile && $profile->phone)
                    <div class="contact-item">
                        <span>📱</span> {{ $profile->phone }}
                    </div>
                @endif
                @if($profile && $profile->location)
                    <div class="contact-item">
                        <span>📍</span> {{ $profile->location }}
                    </div>
                @endif
                @if($user->username)
                    <div class="contact-item">
                        <span>🔗</span> {{ config('app.url') }}/cv/{{ $user->username }}
                    </div>
                @endif
            </div>

            @if($skills->count() > 0)
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Skills</div>
                    <div>
                        @foreach($skills as $skill)
                            <span class="skill-tag">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($education->count() > 0)
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Education</div>
                    @foreach($education as $edu)
                        <div style="margin-bottom: 15px;">
                            <div style="font-weight: 600; font-size: 13px;">{{ $edu->degree }}</div>
                            <div style="font-size: 11px; color: #94a3b8;">{{ $edu->university }}</div>
                            <div style="font-size: 10px; color: #64748b;">
                                {{ \Carbon\Carbon::parse($edu->start_date)->format('Y') }} -
                                {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('Y') : 'Present' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="main-content">
            @if($profile && $profile->bio)
                <div class="main-section">
                    <div class="main-section-title">About Me</div>
                    <div class="bio-text">{{ $profile->bio }}</div>
                </div>
            @endif

            @if($experiences->count() > 0)
                <div class="main-section">
                    <div class="main-section-title">Experience</div>
                    @foreach($experiences as $exp)
                        <div class="experience-item">
                            <div class="item-title">{{ $exp->job_title }}</div>
                            <div class="item-subtitle">{{ $exp->company }}</div>
                            <div class="item-date">
                                {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                                {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                            </div>
                            @if($exp->description)
                                <div class="item-description">{{ $exp->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if($projects->count() > 0)
                <div class="main-section">
                    <div class="main-section-title">Projects</div>
                    @foreach($projects as $project)
                        <div class="project-item">
                            <div class="project-title">{{ $project->title }}</div>
                            <div class="item-description">{{ Str::limit($project->description, 100) }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
