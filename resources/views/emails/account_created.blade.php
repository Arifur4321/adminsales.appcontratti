<!DOCTYPE html>
<html>
<head>
    <title>Account Created</title>
</head>
<body>
    <p>Dear Client,</p>

    <p>Hello {{ $name }},</p>

    <p>Your new account has been created successfully. Here are your new details:</p>

    <p>
        Name: {{ $name }} <br>
        Email: {{ $email }} <br>
        New Password: {{ $password }} <br>
    </p>

    <p>Please click <a href="https://appcontratti.it">here</a> to check or reset your password.</p>

    <p>Thank you,<br>
    Codice 1%</p>
</body>
</html>
