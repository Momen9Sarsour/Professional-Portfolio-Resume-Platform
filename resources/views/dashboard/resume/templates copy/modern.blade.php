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
            background: #f0f2f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
        }

        .resume-container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .resume-header {
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
            margin: 3px;
            color: #1a2035;
        }

        .experience-item,
        .education-item {
            margin-bottom: 25px;
        }

        .job-title,
        .degree-title {
            font-weight: 700;
            color: #1a2035;
            font-size: 18px;
        }

        .company,
        .university {
            color: #2f7bff;
            font-weight: 600;
        }

        .date {
            color: #7a869a;
            font-size: 12px;
            margin: 5px 0;
        }

        .project-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
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
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(47, 123, 255, 0.3);
        }
    </style>
</head>

<body>
    <a href="{{ route('dashboard.resume.download', 'modern') }}" class="btn-download no-print">
        <i class="bi bi-download"></i> Download PDF
    </a>

    <div class="resume-container">
        <div class="resume-header">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    @if ($profile && $profile->avatar)
                        <img src="{{ asset('storage/' . $profile->avatar) }}" class="avatar">
                    @else
                        <div class="avatar bg-white d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-fill" style="font-size: 60px; color: #2f7bff;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <h1 style="font-size: 42px; font-weight: 800;">{{ $user->name }}</h1>
                    @if ($profile && $profile->title)
                        <h3 style="font-size: 20px; opacity: 0.9;">{{ $profile->title }}</h3>
                    @endif
                    @if ($profile && $profile->bio)
                        <p style="margin-top: 15px;">{{ $profile->bio }}</p>
                    @endif
                    <div class="mt-3">
                        <i class="bi bi-envelope"></i> {{ $user->email }}
                        @if ($profile && $profile->phone)
                            &nbsp;&nbsp;|&nbsp;&nbsp; <i class="bi bi-phone"></i> {{ $profile->phone }}
                        @endif
                        @if ($profile && $profile->location)
                            &nbsp;&nbsp;|&nbsp;&nbsp; <i class="bi bi-geo-alt"></i> {{ $profile->location }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="row">
                <div class="col-md-4">
                    @if ($skills->count())
                        <div class="mb-4">
                            <h3 class="section-title">Skills</h3>
                            @foreach ($skillsByCategory as $category => $categorySkills)
                                @if ($category)
                                    <h6 style="margin-top: 15px;">{{ $category }}</h6>
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
                        <div class="mb-4">
                            <h3 class="section-title">Education</h3>
                            @foreach ($education as $edu)
                                <div class="education-item">
                                    <div class="degree-title">{{ $edu->degree }}</div>
                                    <div class="university">{{ $edu->university }}</div>
                                    <div class="date">{{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} -
                                        {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-md-8">
                    @if ($experiences->count())
                        <div class="mb-4">
                            <h3 class="section-title">Work Experience</h3>
                            @foreach ($experiences as $exp)
                                <div class="experience-item">
                                    <div class="job-title">{{ $exp->job_title }}</div>
                                    <div class="company">{{ $exp->company }}</div>
                                    <div class="date">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                                        {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                                    </div>
                                    @if ($exp->description)
                                        <p style="margin-top: 8px;">{{ $exp->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($projects->count())
                        <div>
                            <h3 class="section-title">Projects</h3>
                            @foreach ($projects as $project)
                                <div class="project-card">
                                    <h5 style="font-weight: 700;">{{ $project->title }}</h5>
                                    <span class="skill-badge"
                                        style="background: rgba(47,123,255,0.1); color: #2f7bff;">{{ $project->category }}</span>
                                    <p style="margin-top: 10px;">{{ $project->description }}</p>
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
                                                        class="bi bi-link-45deg"></i> Live Demo</a>
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
