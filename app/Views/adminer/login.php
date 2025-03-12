<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RayyanTech Dashboard</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/media/image/favicon.png"/>

    <!-- Plugin styles -->
    <link rel="stylesheet" href="vendors/bundle.css" type="text/css">

    <!-- App styles -->
    <link rel="stylesheet" href="assets/css/app.min.css" type="text/css">
</head>
<body class="form-membership">

<!-- begin::preloader-->
<div class="preloader">
    <div class="preloader-icon"></div>
</div>
<!-- end::preloader -->

<div class="form-wrapper">

    <!-- logo -->
    <div id="logo">
        <img src="assets/media/image/dark-logo.png" alt="image">
    </div>
    <!-- ./ logo -->

    
    <h5>Sign in</h5>

    <!-- form -->
    <form method="post" action="adminer/login">
        <div class="form-group">
            <input name="username" type="text" class="form-control" placeholder="Username or email" required autofocus>
        </div>
        <div class="form-group">
            <input name="password" type="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group d-flex justify-content-between">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" checked="" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember me</label>
            </div>
            <!-- <a href="recovery-password.html" disabled>Reset password</a> -->
        </div>
        <button class="btn btn-primary btn-block">Sign in</button>
        <hr>
    </form>
    <!-- ./ form -->


</div>

<!-- Plugin scripts -->
<script src="vendors/bundle.js"></script>

<!-- App scripts -->
<script src="assets/js/app.min.js"></script>
</body>
</html>
