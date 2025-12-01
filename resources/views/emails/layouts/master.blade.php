<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Email' }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f6f6; padding: 20px; }
        .email-container { background: white; padding: 20px; border-radius: 8px; }
        .footer { font-size: 12px; color: #888; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="email-container">
        @yield('content')
    </div>

    <p class="footer">© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
</body>
</html>
