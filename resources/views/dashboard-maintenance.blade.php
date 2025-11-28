<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ \App\Models\Setting::get('dashboard_under_maintenance_title') }}
    </title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Inter, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
        }
        .container {
            max-width: 600px;
            width: 90%;
            padding: 2rem;
        }
        .content {
            text-align: center;
            font-size: 1rem;
            line-height: 1.6;
        }
        .content img {
            max-width: 80%;
            height: auto;
            display: block;
            margin: 0 auto 20px;
        }
        .content p {
            margin: 1rem 0;
            color: #333;
        }
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
        }
        @media (max-width: 480px) {
            .container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <h1>{{ \App\Models\Setting::get('dashboard_under_maintenance_title') }}</h1>
        {!! \App\Models\Setting::get('dashboard_under_maintenance_text') !!}
    </div>
</div>
</body>
</html>
