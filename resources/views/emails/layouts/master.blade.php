<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Email' }}</title>
    <style>
          body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333333;
        }
        .otp {
            display: inline-block;
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            background-color: #4caf50;
            padding: 12px 20px;
            border-radius: 6px;
            letter-spacing: 3px;
            margin: 20px 0;
        }
        p {
            color: #555555;
            line-height: 1.5;
        }
        .footer {
            font-size: 12px;
            color: #999999;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        @yield('content')
    </div>

    <p class="footer">© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
</body>
</html>
