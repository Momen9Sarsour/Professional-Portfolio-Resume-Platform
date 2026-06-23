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
            font-family: 'Segoe UI', 'Poppins', sans-serif;
            background: #f0f2f8;
            padding: 40px;
            color: #1a2035;
        }

        .cv-container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .cv-header {
            background: linear-gradient(135deg, #2f7bff 0%, #1a5fcc 100%);
            color: white;
            padding: 40px;
            position: relative;
        }

        .avatar {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .cv-body {
            padding: 40px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #2f7bff;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #2f7bff;
            display: inline-block;
        }

        .skill-badge {
            display: inline-block;
            padding: 5px 12px;
            background: #f4f6fb;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 3px;
        }

        .experience-item, .education-item {
            margin-bottom: 25px;
            padding-left: 20px;
            border-left: 3px solid #2f7bff;
        }

        .job-title, .degree-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .company, .university {
            color: #2f7bff;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .date {
            color: #7a869a;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .project-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .tech-tag {
            display: inline-block;
            padding: 2px 8px;
            background: white;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            color: #2f7bff;
            margin: 2px;
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
            <div class="row" style="display: flex; gap: 30px; flex-wrap: wrap;">
                <div class="col-auto">
                    @if($profile && $profile->avatar)
                        <img src="{{ asset('storage/' . $profile->avatar) }}" class="avatar" alt="{{ $user->name }}">
                    @else
                        <div class="avatar" style="background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-fill" style="font-size: 60px;"></i>
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h1 style="font-size: 42px; margin-bottom: 10px;">{{ $user->name }}</h1>
                    @if($profile && $profile->title)
                        <h3 style="font-size: 20px; opacity: 0.9; margin-bottom: 15px;">{{ $profile->title }}</h3>
                    @endif
                    @if($profile && $profile->bio)
                        <p style="opacity: 0.85;">{{ $profile->bio }}</p>
                    @endif
                    <div style="margin-top: 15px;">
                        @if($profile && $profile->email)
                            <span><i class="bi bi-envelope"></i> {{ $profile->email }}</span>
                        @endif
                        @if($profile && $profile->phone)
                            <span style="margin-left: 20px;"><i class="bi bi-phone"></i> {{ $profile->phone }}</span>
                        @endif
                        @if($profile && $profile->location)
                            <span style="margin-left: 20px;"><i class="bi bi-geo-alt"></i> {{ $profile->location }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="cv-body">
            <div class="row" style="display: flex; gap: 40px; flex-wrap: wrap;">
                <div class="col" style="flex: 1;">
                    {{-- Skills --}}
                    @if($skills->count() > 0)
                        <div style="margin-bottom: 30px;">
                            <h3 class="section-title">Skills</h3>
                            @foreach($skills as $skill)
                                <span class="skill-badge">
                                    {{ $skill->name }}
                                    @if($skill->level) ({{ $skill->level }}%) @endif
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Education --}}
                    @if($education->count() > 0)
                        <div style="margin-bottom: 30px;">
                            <h3 class="section-title">Education</h3>
                            @foreach($education as $edu)
                                <div class="education-item">
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
                        <div style="margin-bottom: 30px;">
                            <h3 class="section-title">Experience</h3>
                            @foreach($experiences as $exp)
                                <div class="experience-item">
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
                        <div>
                            <h3 class="section-title">Projects</h3>
                            @foreach($projects as $project)
                                <div class="project-card">
                                    <div style="font-weight: 700; margin-bottom: 8px;">{{ $project->title }}</div>
                                    <p style="font-size: 13px; margin-bottom: 8px;">{{ $project->description }}</p>
                                    @if($project->technologies)
                                        <div>
                                            @foreach(explode(',', $project->technologies) as $tech)
                                                <span class="tech-tag">{{ trim($tech) }}</span>
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
