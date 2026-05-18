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
            background: #e9ecef;
            font-family: 'Times New Roman', Times, serif;
            padding: 40px;
        }

        .resume-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        .header {
            background: #f8f9fa;
            padding: 30px;
            border-bottom: 2px solid #2f7bff;
            text-align: center;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #2f7bff;
        }

        .name {
            font-size: 32px;
            font-weight: bold;
            color: #1a2035;
        }

        .title {
            font-size: 18px;
            color: #2f7bff;
            margin-bottom: 10px;
        }

        .contact-info {
            font-size: 14px;
            color: #6c757d;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            border-bottom: 2px solid #2f7bff;
            padding-bottom: 5px;
            margin-bottom: 20px;
            color: #1a2035;
        }

        .skill-badge {
            display: inline-block;
            background: #f1f3f5;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            margin: 2px;
        }

        .job-title,
        .degree-title {
            font-weight: bold;
            font-size: 16px;
        }

        .company,
        .university {
            font-style: italic;
            color: #6c757d;
        }

        .date {
            font-size: 12px;
            color: #adb5bd;
        }

        .project-card {
            border-left: 3px solid #2f7bff;
            padding-left: 15px;
            margin-bottom: 20px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }

        .btn-download {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #2f7bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <a href="{{ route('dashboard.resume.download', 'classic') }}" class="btn-download no-print">
        <i class="bi bi-download"></i> Download PDF
    </a>

    <div class="resume-container">
        <div class="header">
            @if ($profile && $profile->avatar)
                <img src="{{ asset('storage/' . $profile->avatar) }}" class="avatar">
            @endif
            <div class="name">{{ $user->name }}</div>
            @if ($profile && $profile->title)
                <div class="title">{{ $profile->title }}</div>
            @endif
            <div class="contact-info">
                <i class="bi bi-envelope"></i> {{ $user->email }}
                @if ($profile && $profile->phone)
                    | <i class="bi bi-phone"></i> {{ $profile->phone }}
                @endif
                @if ($profile && $profile->location)
                    | <i class="bi bi-geo-alt"></i> {{ $profile->location }}
                @endif
            </div>
            @if ($profile && $profile->bio)
                <div style="margin-top: 15px;">{{ $profile->bio }}</div>
            @endif
        </div>

        <div class="p-4">
            <div class="row">
                <div class="col-md-4">
                    @if ($skills->count())
                        <div class="mb-4">
                            <div class="section-title">Skills</div>
                            @foreach ($skillsByCategory as $category => $categorySkills)
                                @if ($category)
                                    <strong>{{ $category }}</strong><br>
                                @endif
                                @foreach ($categorySkills as $skill)
                                    <span class="skill-badge">{{ $skill->name }} @if ($skill->level)
                                            ({{ $skill->level }}%)
                                        @endif
                                    </span>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                    @if ($education->count())
                        <div>
                            <div class="section-title">Education</div>
                            @foreach ($education as $edu)
                                <div class="degree-title">{{ $edu->degree }}</div>
                                <div class="university">{{ $edu->university }}</div>
                                <div class="date">{{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} -
                                    {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
                                </div>
                                @if ($edu->description)
                                    <p style="font-size: 13px;">{{ $edu->description }}</p>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-md-8">
                    @if ($experiences->count())
                        <div class="mb-4">
                            <div class="section-title">Experience</div>
                            @foreach ($experiences as $exp)
                                <div>
                                    <div class="job-title">{{ $exp->job_title }}</div>
                                    <div class="company">{{ $exp->company }}</div>
                                    <div class="date">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                                        {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                                    </div>
                                    @if ($exp->description)
                                        <p style="margin-top: 5px;">{{ $exp->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($projects->count())
                        <div>
                            <div class="section-title">Projects</div>
                            @foreach ($projects as $project)
                                <div class="project-card">
                                    <strong>{{ $project->title }}</strong>
                                    <span style="font-size: 12px; color: #2f7bff;">({{ $project->category }})</span>
                                    <p>{{ $project->description }}</p>
                                    @if ($project->technologies)
                                        <div>
                                            @foreach (explode(',', $project->technologies) as $tech)
                                                <span class="skill-badge">{{ trim($tech) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if ($project->github_link || $project->demo_link)
                                        <div class="mt-2">
                                            @if ($project->github_link)
                                                <a href="{{ $project->github_link }}" target="_blank"><i
                                                        class="bi bi-github"></i> GitHub</a>
                                            @endif
                                            @if ($project->demo_link)
                                                &nbsp;&nbsp;<a href="{{ $project->demo_link }}" target="_blank"><i
                                                        class="bi bi-link-45deg"></i> Demo</a>
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
