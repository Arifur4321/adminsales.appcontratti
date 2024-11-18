<p>Hello {{ $name }},</p>
<p>Your account information has been updated successfully. Here are your new details:</p>

<ul>
    <li>Name: {{ $name }}</li>
    <li>Email: {{ $email }}</li>
    @if ($password)
    <li>New Password: {{ $password }}</li>
    @endif
    <li>Your company currently has {{ $user->numberOfSales }} salespersons, with a maximum allowed of {{ $user->company->NumOfsales }}.</li>
</ul>

<p>Please <a href="https://appcontratti.it/login">click here</a> to verify or reset your password.</p>

<p>Thank you,<br>Codice 1%</p>
