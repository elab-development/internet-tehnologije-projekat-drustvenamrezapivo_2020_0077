<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <p>Hello,</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p><a href="{{ url('password/reset/' . $token) }}">Reset Password</a></p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Regards,<br>Your Application</p>
</body>
</html>