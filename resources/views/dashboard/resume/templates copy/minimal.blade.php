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
            background: #fff;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 40px;
        }

        .resume {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #eaeef2;
            padding: 40px;
        }

        hr {
            margin: 25px 0;
            border-top: 1px solid #eaeef2;
        }

        .name {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .title {
            font-size: 16px;
            color: #5a6874;
            margin-bottom: 15px;
        }

        .contact {
            font-size: 14px;
            color: #5a6874;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .section {
            margin-top: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2f7bff;
            margin-bottom: 15px;
        }

        .skill-item {
            display: inline-block;
            background: #f4f6f9;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 13px;
            margin: 3px;
        }

        .exp-item,
        .edu-item {
            margin-bottom: 20px;
        }

        .exp-title,
        .edu-title {
            font-weight: 600;
        }

        .exp-meta,
        .edu-meta {
            font-size: 13px;
            color: #5a6874;
            margin: 3px 0;
        }

        .project-item {
            margin-bottom: 20px;
        }

        .project-title {
            font-weight: 600;
        }

        @media print {
            body {
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
            font-weight: 500;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <a href="{{ route('dashboard.resume.download', 'minimal') }}" class="btn-download no-print">
        <i class="bi bi-download"></i> PDF
    </a>
    <div class="resume">
        <div class="name">{{ $user->name }}</div>
        @if ($profile && $profile->title)
            <div class="title">{{ $profile->title }}</div>
        @endif
        <div class="contact">
            <span><i class="bi bi-envelope"></i> {{ $user->email }}</span>
            @if ($profile && $profile->phone)
                <span><i class="bi bi-phone"></i> {{ $profile->phone }}</span>
            @endif
            @if ($profile && $profile->location)
                <span><i class="bi bi-geo-alt"></i> {{ $profile->location }}</span>
            @endif
        </div>
        @if ($profile && $profile->bio)
            <div>{{ $profile->bio }}</div>
        @endif

        <hr>

        @if ($skills->count())
            <div class="section">
                <div class="section-title">Skills</div>
                <div>
                    @foreach ($skills as $skill)
                        <span class="skill-item">{{ $skill->name }} @if ($skill->level)
                                ({{ $skill->level }}%)
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($experiences->count())
            <div class="section">
                <div class="section-title">Experience</div>
                @foreach ($experiences as $exp)
                    <div class="exp-item">
                        <div class="exp-title">{{ $exp->job_title }} at {{ $exp->company }}</div>
                        <div class="exp-meta">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                            {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                        </div>
                        @if ($exp->description)
                            <div style="font-size: 14px;">{{ $exp->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if ($education->count())
            <div class="section">
                <div class="section-title">Education</div>
                @foreach ($education as $edu)
                    <div class="edu-item">
                        <div class="edu-title">{{ $edu->degree }}</div>
                        <div class="edu-meta">{{ $edu->university }} |
                            {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} -
                            {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
                        </div>
                        @if ($edu->description)
                            <div style="font-size: 14px;">{{ $edu->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if ($projects->count())
            <div class="section">
                <div class="section-title">Projects</div>
                @foreach ($projects as $project)
                    <div class="project-item">
                        <div class="project-title">{{ $project->title }} <span
                                style="font-size:12px; color:#5a6874;">({{ $project->category }})</span></div>
                        <div>{{ $project->description }}</div>
                        @if ($project->technologies)
                            <div style="margin-top: 5px;">
                                @foreach (explode(',', $project->technologies) as $tech)
                                    <span class="skill-item">{{ trim($tech) }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if ($project->github_link || $project->demo_link)
                            <div>
                                @if ($project->github_link)
                                    <a href="{{ $project->github_link }}" target="_blank">GitHub</a>
                                @endif
                                @if ($project->demo_link)
                                    &nbsp;|&nbsp; <a href="{{ $project->demo_link }}" target="_blank">Demo</a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>

</html>
