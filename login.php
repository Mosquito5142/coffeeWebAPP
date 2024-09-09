<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <title>Login</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Exo:100,200,400);
        @import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300);

        body {
            margin: 0;
            padding: 0;
            background: #fff;

            color: #fff;
            font-family: Arial;
            font-size: 12px;
        }

        .body {
            position: absolute;
            top: -20px;
            left: -20px;
            right: -40px;
            bottom: -40px;
            width: auto;
            height: auto;
            background-image: url(<?php include 'img-bg.php' ?>);
            background-size: cover;
            -webkit-filter: blur(5px);
            z-index: 0;
        }

        .grad {
            position: absolute;
            top: -20px;
            left: -20px;
            right: -40px;
            bottom: -40px;
            width: auto;
            height: auto;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(0, 0, 0, 0)), color-stop(100%, rgba(0, 0, 0, 0.65)));
            /* Chrome,Safari4+ */
            z-index: 1;
            opacity: 0.7;
        }

        .header {
            position: absolute;
            top: calc(50% - 35px);
            left: calc(50% - 255px);
            z-index: 2;
        }

        .header div {
            float: left;
            color: #fff;
            font-family: 'Exo', sans-serif;
            font-size: 35px;
            font-weight: 200;
        }

        .header div span {
            color: #a18d6c !important;
            text-shadow: 0 0 3px #FFF;
        }

        .login {
            position: absolute;
            top: calc(50% - 75px);
            left: calc(50% - 50px);
            height: 150px;
            width: 350px;
            padding: 10px;
            z-index: 2;
        }

        .login input[type=text],
        .login input[type=password] {
            width: 250px;
            height: 30px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 2px;
            color: #fff;
            font-family: 'Exo', sans-serif;
            font-size: 16px;
            font-weight: 400;
            padding: 4px;
            margin-top: 10px;
        }

        .login input[type=submit] {
            width: 260px;
            height: 35px;
            background: #fff;
            border: 1px solid #fff;
            cursor: pointer;
            border-radius: 2px;
            color: #a18d6c;
            font-family: 'Exo', sans-serif;
            font-size: 16px;
            font-weight: 400;
            padding: 6px;
            margin-top: 10px;
        }

        .login input[type=submit]:hover {
            opacity: 0.8;
        }

        .login input[type=submit]:active {
            opacity: 0.6;
        }

        .login input[type=text]:focus,
        .login input[type=password]:focus {
            outline: none;
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .login input[type=submit]:focus {
            outline: none;
        }

        ::-webkit-input-placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        ::-moz-input-placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="body"></div>
    <div class="grad"></div>
    <div class="header">
        <div>
            <i class="fas fa-coffee me-2" style="font-size: 32px;"></i>
            กาแฟ
            <span><br />......หรือแกฟะ</span>
        </div>
    </div>
    <br>
    <div class="login">
        <form action="AC/login_admin.php" method="POST">
            <input type="text" placeholder="username" name="username"><br>
            <input type="password" placeholder="password" name="password"><br>
            บน : admin
            ล่าง : 1234
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>