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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #ffffff;
            padding: 40px;
            color: #1e293b;
        }

        .cv-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
        }

        .cv-header {
            text-align: center;
            padding: 40px 0 30px;
            border-bottom: 2px solid #e2e8f0;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .name {
            font-size: 36px;
            font-weight: 600;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .title {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 16px;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
            font-size: 14px;
            color: #475569;
        }

        .contact-info span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .cv-body {
            padding: 40px 0;
        }

        .section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: -0.3px;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .skill-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-item {
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            color: #1e293b;
        }

        .experience-item, .education-item {
            margin-bottom: 24px;
        }

        .item-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .item-subtitle {
            font-size: 14px;
            color: #2f7bff;
            margin-bottom: 4px;
        }

        .item-date {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .item-description {
            font-size: 14px;
            line-height: 1.5;
            color: #334155;
        }

        .project-item {
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .project-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .project-tech {
            display: inline-block;
            font-size: 11px;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 12px;
            margin: 4px 4px 0 0;
        }

        .bio-text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
            text-align: center;
        }

        @media print {
            body {
                padding: 0;
            }
            .cv-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="cv-container">
        <div class="cv-header">
            @if($profile && $profile->avatar)
                <img src="{{ asset('storage/' . $profile->avatar) }}" class="avatar" alt="{{ $user->name }}">
            @endif
            <h1 class="name">{{ $user->name }}</h1>
            @if($profile && $profile->title)
                <div class="title">{{ $profile->title }}</div>
            @endif
            <div class="contact-info">
                @if($profile && $profile->email)
                    <span>📧 {{ $profile->email }}</span>
                @endif
                @if($profile && $profile->phone)
                    <span>📞 {{ $profile->phone }}</span>
                @endif
                @if($profile && $profile->location)
                    <span>📍 {{ $profile->location }}</span>
                @endif
                @if($user->username)
                    <span>🔗 {{ config('app.url') }}/cv/{{ $user->username }}</span>
                @endif
            </div>
        </div>

        <div class="cv-body">
            @if($profile && $profile->bio)
                <div class="bio-text">
                    {{ $profile->bio }}
                </div>
            @endif

            <div class="row" style="display: flex; gap: 48px; flex-wrap: wrap;">
                <div class="col" style="flex: 1;">
                    {{-- Skills --}}
                    @if($skills->count() > 0)
                        <div class="section">
                            <h3 class="section-title">Skills</h3>
                            <div class="skill-list">
                                @foreach($skills as $skill)
                                    <span class="skill-item">
                                        {{ $skill->name }}
                                        @if($skill->level) ({{ $skill->level }}%) @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Education --}}
                    @if($education->count() > 0)
                        <div class="section">
                            <h3 class="section-title">Education</h3>
                            @foreach($education as $edu)
                                <div class="education-item">
                                    <div class="item-title">{{ $edu->degree }}</div>
                                    <div class="item-subtitle">{{ $edu->university }}</div>
                                    <div class="item-date">
                                        {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} -
                                        {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
                                    </div>
                                    @if($edu->description)
                                        <div class="item-description">{{ $edu->description }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col" style="flex: 2;">
                    {{-- Experience --}}
                    @if($experiences->count() > 0)
                        <div class="section">
                            <h3 class="section-title">Experience</h3>
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

                    {{-- Projects --}}
                    @if($projects->count() > 0)
                        <div class="section">
                            <h3 class="section-title">Projects</h3>
                            @foreach($projects as $project)
                                <div class="project-item">
                                    <div class="project-title">{{ $project->title }}</div>
                                    <div class="item-description">{{ $project->description }}</div>
                                    @if($project->technologies)
                                        <div style="margin-top: 8px;">
                                            @foreach(explode(',', $project->technologies) as $tech)
                                                <span class="project-tech">{{ trim($tech) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
