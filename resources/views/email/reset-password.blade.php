<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password  Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif ; font-size:16px"  >
    
    <h1>You Have Requested To Change Password </h1>
    <p>Hello , {{ $formData['user']->name }}</p>

    <p>Please Click the Link given Below To Reset Password</p>

    <a href="{{ route('front.resetPassword', $formData['token']) }}">Click Here</a>
    
    <p>Thanks</p>

</body>
</html>