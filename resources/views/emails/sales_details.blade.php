<!DOCTYPE html>
<html>
<head>
    <title>Sales Registration</title>
</head>
<body>

    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('build/images/logo-dark.png') }}" alt="Company Logo" style="width: 150px; height: auto;">
    </div>
 
    <p>Ciao {{ $name }},</p>
    <p>Sei registrato come nuovo venditore nell'app 1% Contract.</p>
    <p>Le tue credenziali di accesso sono le seguenti:</p>
    <p>Email: {{ $email }}</p>
    <p>Password: {{ $password }}</p>
    <p>Verifica la tua email cliccando <a href="https://sales.appcontratti.it/login">qui</a>.</p>
</body>
</html>
