<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - CV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #2f7bff;
            --secondary: #1a2035;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            background: #f4f6fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 0;
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
            background: linear-gradient(135deg, var(--secondary) 0%, #2d3a5e 100%);
            color: white;
            padding: 40px;
            position: relative;
        }

        .avatar {
            width: 150px;
            height: 150px;
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
            color: var(--secondary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--primary);
            display: inline-block;
        }

        .skill-badge {
            display: inline-block;
            padding: 5px 12px;
            background: var(--light);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--dark);
            margin: 3px;
        }

        .experience-item, .education-item {
            margin-bottom: 25px;
            padding-left: 20px;
            border-left: 3px solid var(--primary);
        }

        .job-title, .degree-title {
            font-weight: 700;
            color: var(--secondary);
            font-size: 18px;
            margin-bottom: 5px;
        }

        .company, .university {
            color: var(--primary);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .date {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .project-card {
            background: var(--light);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }

        .project-card:hover {
            transform: translateY(-3px);
        }

        .project-title {
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 8px;
        }

        .tech-tag {
            display: inline-block;
            padding: 2px 8px;
            background: white;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            color: var(--primary);
            margin: 2px;
        }

        .social-link {
            color: white;
            font-size: 20px;
            margin: 0 10px;
            transition: opacity 0.2s;
        }

        .social-link:hover {
            opacity: 0.8;
            color: white;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .cv-container {
                box-shadow: none;
            }
        }

        .btn-download {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(47,123,255,0.3);
            z-index: 1000;
        }

        .btn-download:hover {
            background: #1a5fcc;
            color: white;
        }
    </style>
</head>
<body>
    <a href="{{ route('cv.download', $user->username ?? '') }}" class="btn btn-download no-print">
        <i class="bi bi-download"></i> Download PDF
    </a>

    <div class="cv-container">
        {{-- Header --}}
        <div class="cv-header">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    @if($profile && $profile->avatar)
                        <img src="{{ asset('storage/' . $profile->avatar) }}" class="avatar" alt="{{ $user->name }}">
                    @else
                        <div class="avatar bg-light d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-fill" style="font-size: 60px; color: var(--primary);"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <h1 style="font-size: 42px; font-weight: 700; margin-bottom: 10px;">{{ $user->name }}</h1>
                    @if($profile && $profile->title)
                        <h3 style="font-size: 20px; opacity: 0.9; margin-bottom: 15px;">{{ $profile->title }}</h3>
                    @endif
                    @if($profile && $profile->bio)
                        <p style="opacity: 0.85; line-height: 1.6;">{{ $profile->bio }}</p>
                    @endif
                    <div class="mt-3">
                        @if($profile && $profile->email)
                            <i class="bi bi-envelope"></i> {{ $profile->email }} &nbsp;&nbsp;
                        @endif
                        @if($profile && $profile->phone)
                            <i class="bi bi-phone"></i> {{ $profile->phone }} &nbsp;&nbsp;
                        @endif
                        @if($profile && $profile->location)
                            <i class="bi bi-geo-alt"></i> {{ $profile->location }}
                        @endif
                    </div>
                    <div class="mt-3">
                        @foreach($socialLinks as $link)
                            <a href="{{ $link->url }}" target="_blank" class="social-link">
                                <i class="bi bi-{{ strtolower($link->platform) }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="cv-body">
            <div class="row">
                {{-- Left Column --}}
                <div class="col-md-4">
                    {{-- Skills --}}
                    @if($skills->count() > 0)
                        <div class="mb-4">
                            <h3 class="section-title">Skills</h3>
                            @foreach($skillsByCategory as $category => $categorySkills)
                                @if($category)
                                    <h6 style="margin-top: 15px; font-weight: 600;">{{ $category }}</h6>
                                @endif
                                @foreach($categorySkills as $skill)
                                    <span class="skill-badge">
                                        {{ $skill->name }}
                                        @if($skill->level)
                                            ({{ $skill->level }}%)
                                        @endif
                                    </span>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                    {{-- Education --}}
                    @if($education->count() > 0)
                        <div class="mb-4">
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
                                        <p style="font-size: 13px; color: #6c757d; margin-top: 8px;">{{ $edu->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Right Column --}}
                <div class="col-md-8">
                    {{-- Experience --}}
                    @if($experiences->count() > 0)
                        <div class="mb-4">
                            <h3 class="section-title">Work Experience</h3>
                            @foreach($experiences as $exp)
                                <div class="experience-item">
                                    <div class="job-title">{{ $exp->job_title }}</div>
                                    <div class="company">{{ $exp->company }}</div>
                                    <div class="date">
                                        {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                                        {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                                    </div>
                                    @if($exp->description)
                                        <p style="font-size: 14px; line-height: 1.5; margin-top: 8px;">{{ $exp->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Projects --}}
                    @if($projects->count() > 0)
                        <div class="mb-4">
                            <h3 class="section-title">Projects</h3>
                            @foreach($projects as $project)
                                <div class="project-card">
                                    <div class="project-title">{{ $project->title }}</div>
                                    <div style="font-size: 13px; color: #6c757d; margin-bottom: 8px;">
                                        <span class="badge-cat" style="background: rgba(47,123,255,0.1); color: var(--primary); padding: 2px 8px; border-radius: 12px;">
                                            {{ $project->category }}
                                        </span>
                                    </div>
                                    <p style="font-size: 13px; line-height: 1.5; margin-bottom: 8px;">{{ $project->description }}</p>
                                    @if($project->technologies)
                                        <div>
                                            @foreach(explode(',', $project->technologies) as $tech)
                                                <span class="tech-tag">{{ trim($tech) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($project->github_link || $project->demo_link)
                                        <div class="mt-2">
                                            @if($project->github_link)
                                                <a href="{{ $project->github_link }}" target="_blank" style="margin-right: 10px;">
                                                    <i class="bi bi-github"></i> GitHub
                                                </a>
                                            @endif
                                            @if($project->demo_link)
                                                <a href="{{ $project->demo_link }}" target="_blank">
                                                    <i class="bi bi-link-45deg"></i> Live Demo
                                                </a>
                                            @endif
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
