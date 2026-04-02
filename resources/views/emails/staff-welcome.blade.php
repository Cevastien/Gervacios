<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Caf&#233; Gervacios Staff Account</title>
</head>
<body style="font-family:Arial, sans-serif; background:#f8fafc; margin:0; padding:24px; color:#0f172a;">
    <div style="max-width:560px; margin:0 auto; background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:24px;">
        <h1 style="margin-top:0; font-size:22px;">Your Caf&#233; Gervacios Staff Account</h1>

        <p>Hello {{ $name }},</p>
        <p>Your staff account has been created. Please use the credentials below to sign in:</p>

        <p style="line-height:1.8;">
            <strong>Name:</strong> {{ $name }}<br>
            <strong>Email:</strong> {{ $email }}<br>
            <strong>Temporary Password:</strong> {{ $temporaryPassword }}<br>
            <strong>Login URL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
        </p>

        <p style="margin-bottom:0;">You will be asked to change your password on first login.</p>
    </div>
</body>
</html>
