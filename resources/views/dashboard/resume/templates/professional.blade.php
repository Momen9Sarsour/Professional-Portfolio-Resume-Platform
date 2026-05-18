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
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #e8e6e1;
            padding: 40px;
            color: #2c2c2c;
        }

        .cv-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .cv-header {
            background: #1e3a5f;
            color: white;
            padding: 40px;
            text-align: center;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ffd966;
            margin-bottom: 20px;
        }

        .name {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .title {
            font-size: 16px;
            color: #ffd966;
            letter-spacing: 1px;
        }

        .contact-bar {
            background: #ffd966;
            color: #1e3a5f;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        .contact-bar span {
            margin: 0 15px;
        }

        .cv-body {
            padding: 40px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e3a5f;
            border-bottom: 2px solid #ffd966;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .bio-text {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 30px;
            font-style: italic;
            color: #4a5568;
        }

        .skill-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-item {
            background: #f0f0f0;
            padding: 5px 12px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 500;
        }

        .experience-item, .education-item {
            margin-bottom: 25px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .item-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e3a5f;
        }

        .item-date {
            font-size: 12px;
            color: #718096;
        }

        .item-subtitle {
            font-size: 14px;
            font-weight: 600;
            color: #ffb347;
            margin-bottom: 8px;
        }

        .item-description {
            font-size: 13px;
            line-height: 1.5;
            color: #4a5568;
        }

        .project-item {
            margin-bottom: 20px;
            padding-left: 15px;
            border-left: 3px solid #ffd966;
        }

        .project-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 5px;
        }

        .project-tech {
            display: inline-block;
            font-size: 10px;
            background: #f0f0f0;
            padding: 2px 8px;
            border-radius: 3px;
            margin: 4px 4px 0 0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
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
            <h1 class="name">{{ strtoupper($user->name) }}</h1>
            @if($profile && $profile->title)
                <div class="title">{{ $profile->title }}</div>
            @endif
        </div>

        <div class="contact-bar">
            @if($profile && $profile->email)
                <span>📧 {{ $profile->email }}</span>
            @endif
            @if($profile && $profile->phone)
                <span>📞 {{ $profile->phone }}</span>
            @endif
            @if($profile && $profile->location)
                <span>📍 {{ $profile->location }}</span>
            @endif
        </div>

        <div class="cv-body">
            @if($profile && $profile->bio)
                <div class="bio-text">
                    {{ $profile->bio }}
                </div>
            @endif

            <div class="row" style="display: flex; gap: 40px; flex-wrap: wrap;">
                <div class="col" style="flex: 1;">
                    {{-- Skills --}}
                    @if($skills->count() > 0)
                        <div class="section">
                            <h3 class="section-title">Core Competencies</h3>
                            <div class="skill-list">
                                @foreach($skills as $skill)
                                    <span class="skill-item">{{ $skill->name }}</span>
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
                                    <div class="item-header">
                                        <span class="item-title">{{ $edu->degree }}</span>
                                        <span class="item-date">
                                            {{ \Carbon\Carbon::parse($edu->start_date)->format('Y') }} -
                                            {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('Y') : 'Present' }}
                                        </span>
                                    </div>
                                    <div class="item-subtitle">{{ $edu->university }}</div>
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
                            <h3 class="section-title">Professional Experience</h3>
                            @foreach($experiences as $exp)
                                <div class="experience-item">
                                    <div class="item-header">
                                        <span class="item-title">{{ $exp->job_title }}</span>
                                        <span class="item-date">
                                            {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                                            {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                                        </span>
                                    </div>
                                    <div class="item-subtitle">{{ $exp->company }}</div>
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
                            <h3 class="section-title">Key Projects</h3>
                            @foreach($projects as $project)
                                <div class="project-item">
                                    <div class="project-title">{{ $project->title }}</div>
                                    <div class="item-description">{{ Str::limit($project->description, 120) }}</div>
                                    @if($project->technologies)
                                        <div style="margin-top: 6px;">
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
