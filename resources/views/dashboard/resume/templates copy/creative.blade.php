<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Resume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
        }
        .resume-card {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .sidebar {
            background: linear-gradient(135deg, #2f7bff 0%, #1a5fcc 100%);
            color: white;
            padding: 40px 30px;
            height: 100%;
        }
        .main-content {
            padding: 40px 30px;
        }
        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin-bottom: 20px;
        }
        .sidebar .name {
            font-size: 28px;
            font-weight: 800;
        }
        .sidebar .title {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        .sidebar .contact-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar .contact-item i {
            font-size: 18px;
            width: 24px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #2f7bff;
            margin-bottom: 20px;
            border-bottom: 2px solid #2f7bff;
            display: inline-block;
            padding-bottom: 5px;
        }
        .skill-tag {
            display: inline-block;
            background: #f0f2f5;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 500;
            margin: 3px;
            color: #1a2035;
        }
        .experience-item, .education-item {
            margin-bottom: 25px;
        }
        .job-title, .degree-title {
            font-weight: 800;
            font-size: 16px;
            color: #1a2035;
        }
        .company, .university {
            color: #2f7bff;
            font-weight: 600;
            font-size: 14px;
        }
        .date {
            font-size: 12px;
            color: #7a869a;
            margin: 5px 0;
        }
        .project-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }
        .project-card:hover {
            transform: translateY(-2px);
        }
        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none; }
        }
        .btn-download {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: white;
            color: #2f7bff;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <a href="{{ route('dashboard.resume.download', 'creative') }}" class="btn-download no-print">
        <i class="bi bi-download"></i> Download PDF
    </a>
    <div class="resume-card">
        <div class="row g-0">
            <div class="col-md-4">
                <div class="sidebar">
                    @if($profile && $profile->avatar)
                        <img src="{{ asset('storage/' . $profile->avatar) }}" class="avatar">
                    @else
                        <div class="avatar bg-white d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-fill" style="font-size: 70px; color: #2f7bff;"></i>
                        </div>
                    @endif
                    <div class="name">{{ $user->name }}</div>
                    @if($profile && $profile->title)
                        <div class="title">{{ $profile->title }}</div>
                    @endif
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i> {{ $user->email }}
                    </div>
                    @if($profile && $profile->phone)
                        <div class="contact-item">
                            <i class="bi bi-telephone-fill"></i> {{ $profile->phone }}
                        </div>
                    @endif
                    @if($profile && $profile->location)
                        <div class="contact-item">
                            <i class="bi bi-geo-alt-fill"></i> {{ $profile->location }}
                        </div>
                    @endif
                    @if($profile && $profile->bio)
                        <hr style="background: rgba(255,255,255,0.2); margin: 20px 0;">
                        <div>{{ $profile->bio }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-8">
                <div class="main-content">
                    @if($skills->count())
                        <div class="mb-4">
                            <div class="section-title">Skills</div>
                            <div>
                                @foreach($skills as $skill)
                                    <span class="skill-tag">{{ $skill->name }} @if($skill->level) ({{ $skill->level }}%) @endif</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($experiences->count())
                        <div class="mb-4">
                            <div class="section-title">Experience</div>
                            @foreach($experiences as $exp)
                                <div class="experience-item">
                                    <div class="job-title">{{ $exp->job_title }}</div>
                                    <div class="company">{{ $exp->company }}</div>
                                    <div class="date">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}</div>
                                    @if($exp->description)
                                        <p>{{ $exp->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($education->count())
                        <div class="mb-4">
                            <div class="section-title">Education</div>
                            @foreach($education as $edu)
                                <div class="education-item">
                                    <div class="degree-title">{{ $edu->degree }}</div>
                                    <div class="university">{{ $edu->university }}</div>
                                    <div class="date">{{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} - {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($projects->count())
                        <div>
                            <div class="section-title">Projects</div>
                            @foreach($projects as $project)
                                <div class="project-card">
                                    <h6 style="font-weight: 800;">{{ $project->title }}</h6>
                                    <span class="skill-tag" style="background: rgba(47,123,255,0.1); color: #2f7bff;">{{ $project->category }}</span>
                                    <p class="mt-2">{{ $project->description }}</p>
                                    @if($project->technologies)
                                        <div>
                                            @foreach(explode(',', $project->technologies) as $tech)
                                                <span class="skill-tag">{{ trim($tech) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($project->github_link || $project->demo_link)
                                        <div class="mt-2">
                                            @if($project->github_link)
                                                <a href="{{ $project->github_link }}" target="_blank"><i class="bi bi-github"></i> GitHub</a>
                                            @endif
                                            @if($project->demo_link)
                                                &nbsp;&nbsp;<a href="{{ $project->demo_link }}" target="_blank"><i class="bi bi-link-45deg"></i> Demo</a>
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
</body>
</html>
