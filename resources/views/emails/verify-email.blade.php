<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; line-height: 1.6; color: #1f2937; }
        .container { max-width: 520px; margin: 0 auto; padding: 32px 24px; }
        h1 { font-size: 20px; margin: 0 0 8px; }
        p { margin: 0 0 16px; color: #4b5563; }
        .btn { display: inline-block; padding: 12px 28px; background: #0f172a; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; }
        .footer { margin-top: 24px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verify your email</h1>
        <p>Thanks for joining {{ config('app.name') }}. Click the button below to verify your email address.</p>
        <p><a href="{{ $signedUrl }}" class="btn">Verify Email</a></p>
        <p>If you did not create an account, no further action is required.</p>
        <p class="footer">{{ config('app.name') }}</p>
    </div>
</body>
</html>
