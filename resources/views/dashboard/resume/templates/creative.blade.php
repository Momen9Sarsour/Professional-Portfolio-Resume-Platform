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
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            min-height: 100vh;
        }

        .cv-container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
        }

        .cv-header {
            background: linear-gradient(135deg, #f97316 0%, #f59e0b 100%);
            padding: 50px;
            position: relative;
            overflow: hidden;
        }

        .cv-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .name {
            font-size: 48px;
            font-weight: 800;
            color: white;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .title {
            font-size: 20px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 20px;
        }

        .contact-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .contact-info span {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .cv-body {
            padding: 50px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #f97316;
            margin-bottom: 25px;
            display: inline-block;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, #f97316, #f59e0b);
            border-radius: 3px;
        }

        .skill-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .skill-card {
            background: linear-gradient(135deg, #fff5e8 0%, #fff0e0 100%);
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            color: #f97316;
        }

        .experience-card, .education-card {
            background: #fef9f0;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .experience-card:hover, .education-card:hover {
            transform: translateX(5px);
        }

        .job-title, .degree-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a2035;
            margin-bottom: 5px;
        }

        .company, .university {
            font-size: 14px;
            color: #f97316;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .date {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .project-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 20px;
            border-radius: 20px;
            transition: all 0.3s;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .project-title {
            font-size: 16px;
            font-weight: 700;
            color: #f97316;
            margin-bottom: 8px;
        }

        .tech-badge {
            display: inline-block;
            background: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 600;
            color: #f97316;
            margin: 4px 4px 0 0;
        }

        .bio-text {
            background: linear-gradient(135deg, #fff5e8 0%, #fff0e0 100%);
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 30px;
            font-style: italic;
            line-height: 1.6;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .cv-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="cv-container">
        <div class="cv-header">
            <div class="header-content">
                @if($profile && $profile->avatar)
                    <img src="{{ asset('storage/' . $profile->avatar) }}" class="avatar" alt="{{ $user->name }}">
                @endif
                <div>
                    <h1 class="name">{{ $user->name }}</h1>
                    @if($profile && $profile->title)
                        <div class="title">{{ $profile->title }}</div>
                    @endif
                    <div class="contact-info">
                        @if($profile && $profile->email)
                            <span>✉️ {{ $profile->email }}</span>
                        @endif
                        @if($profile && $profile->phone)
                            <span>📱 {{ $profile->phone }}</span>
                        @endif
                        @if($profile && $profile->location)
                            <span>📍 {{ $profile->location }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="cv-body">
            @if($profile && $profile->bio)
                <div class="bio-text">
                    "{{ $profile->bio }}"
                </div>
            @endif

            <div class="row" style="display: flex; gap: 50px; flex-wrap: wrap;">
                <div class="col" style="flex: 1;">
                    {{-- Skills --}}
                    @if($skills->count() > 0)
                        <div class="section">
                            <h3 class="section-title">✨ Skills</h3>
                            <div class="skill-grid">
                                @foreach($skills as $skill)
                                    <div class="skill-card">
                                        {{ $skill->name }}
                                        @if($skill->level) • {{ $skill->level }}% @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Education --}}
                    @if($education->count() > 0)
                        <div class="section">
                            <h3 class="section-title">🎓 Education</h3>
                            @foreach($education as $edu)
                                <div class="education-card">
                                    <div class="degree-title">{{ $edu->degree }}</div>
                                    <div class="university">{{ $edu->university }}</div>
                                    <div class="date">
                                        {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} -
                                        {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
                                    </div>
                                    @if($edu->description)
                                        <p style="font-size: 13px; margin-top: 8px;">{{ $edu->description }}</p>
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
                            <h3 class="section-title">💼 Experience</h3>
                            @foreach($experiences as $exp)
                                <div class="experience-card">
                                    <div class="job-title">{{ $exp->job_title }}</div>
                                    <div class="company">{{ $exp->company }}</div>
                                    <div class="date">
                                        {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                                        {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                                    </div>
                                    @if($exp->description)
                                        <p style="font-size: 13px; margin-top: 8px;">{{ $exp->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Projects --}}
                    @if($projects->count() > 0)
                        <div class="section">
                            <h3 class="section-title">🚀 Projects</h3>
                            <div class="project-grid">
                                @foreach($projects as $project)
                                    <div class="project-card">
                                        <div class="project-title">{{ $project->title }}</div>
                                        <p style="font-size: 12px; margin-bottom: 8px;">{{ Str::limit($project->description, 80) }}</p>
                                        @if($project->technologies)
                                            <div>
                                                @foreach(array_slice(explode(',', $project->technologies), 0, 3) as $tech)
                                                    <span class="tech-badge">{{ trim($tech) }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
