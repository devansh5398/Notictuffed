<?php
  session_start();
  if(isset($_SESSION['username']))
    echo "<script>location.replace('home.php');</script>";
?>

<!DOCTYPE html>

<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Permanent+Marker&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Caveat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <title>WebNotices</title>
    <style type="text/css" rel="stylesheet">
      .logo {
        height : 250px;
      }
      @media screen and (min-width:768px) {
        .logo {
          height : 300px;
        }
      }

      .l1 { box-shadow: 0 0 3rem #007BFF;}
      .l2 { box-shadow: 0 0 3rem #FFC107;}
      .l3 { box-shadow: 0 0 3rem #DC3545;}
    </style>
  </head>

  <body style="background-color:#ECEFF1;">

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top shadow-lg">
      <a class="navbar-brand" href="index.php">
        <img class="mt-n1 pr-1" src="logo.png" alt="Logo" style="height:2rem;">
        <strong style="font-family:'Caveat', 'Times New Roman', cursive; letter-spacing:4px;"> WebNotices</strong>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".links"><span class="navbar-toggler-icon"></span></button>

      <div class="collapse navbar-collapse links">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="student_signup.php">Sign up</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Sign in</a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="admin_signin.php">Admin</a>
              <a class="dropdown-item" href="student_signin.php">Student</a>
            </div>
          </li>
        </ul>
      </div>

    </nav>

    <div class="intro p-3">
      <div class="intro_img col-md-9 col-sm-10 p-0 mx-auto mt-3 text-center">
        <img class="logo" src="logo.png" alt="WebNotices logo"><br>
        <h1 style="font-family: 'Permanent Marker', 'Times New Roman', cursive; font-size: 3rem; color: #204969; text-shadow: 0 0 1rem #8ac6d1;">WebNotices</h1>
      </div><br>
      <div style="font-family: 'Caveat', 'Times New Roman', cursive; font-size: 1.5rem;" class="intro_body col-lg-8 col-md-9 col-sm-10 p-0 mx-auto">
        <div class="l1 bg-primary text-white rounded mb-4 px-3">Isn't it a headache to see all important notices on a small messy noticeboard?</div>
        <div class="l2 bg-warning rounded mb-4 px-3">But there is another pretty easier way to check all of your college notices in one go i.e. using <strong>WebNotices</strong>.</div>
        <div class="l3 bg-danger text-white rounded px-3">So, get started by <strong><a href="student_signup.php" style="text-decoration:none; color:#293462">Signing up</a></strong>
          to fetch all notices of academics, club activities, sports activities, placements and much more.</div>
      </div><br>
    </div>

  </body>
</html>
