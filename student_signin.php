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
    <link href="https://fonts.googleapis.com/css?family=Caveat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <?php
      $server = "localhost";
      $dbuser = "Devansh";
      $dbpass = "Har427Mahadev";
      $db = "root";

      $conn = mysqli_connect($server, $dbuser, $dbpass, $db);
      if(!$conn)
        die("Connection failed: ".mysqli_connect_error());

      $usermail = $password = $usermailerr = $passerr = "";     //for div class = "signin"
      $message = "";

      if($_SERVER["REQUEST_METHOD"] == 'POST') {
          if(empty($_POST['usermail']))
            $usermailerr = "Enter username or email.";
          else
            $usermail = testIt($_POST['usermail']);

          if(empty($_POST['password']))
            $passerr = "Enter your password.";
          else
            $password = md5(htmlspecialchars($_POST['password']));

          if(empty($usermailerr) && empty($passerr)) {
            if(filter_var($usermail, FILTER_VALIDATE_EMAIL))
              $sql = "SELECT * FROM USERS WHERE EMAIL = '$usermail' AND PASSWORD = '$password'";
            else
              $sql = "SELECT * FROM users WHERE USERNAME = '$usermail' AND PASSWORD = '$password'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              while($entry = mysqli_fetch_assoc($result)) {
                $_SESSION['firstname'] = $entry['FIRSTNAME'];
                $_SESSION['lastname'] = $entry['LASTNAME'];
                $_SESSION['username'] = $entry['USERNAME'];
                $_SESSION['email'] = $entry['EMAIL'];
                echo "<script>location.replace('home.php');</script>";
              }
            }
            else {
              $passerr = "The username and/or password you specified are not correct.";
              session_unset();
              session_destroy();
            }
          }
      }

      function testIt($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
    ?>

    <title>Sign in</title>
    <style>
      .error {
        color : red;
        font-size: 0.8rem;
      }
    </style>
  </head>

  <body style="background-color:#ECEFF1;">

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top shadow-lg">
      <a class="navbar-brand" href="index.php">
        <img class="mt-n1 pr-1" src="logo.png" alt="Logo" style="height:2rem;">
        <strong style="font-family:'Caveat', 'Times New Roman', cursive; letter-spacing:4px;"> Notictuffed</strong>
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

    <div class="p-sm-4"></div>
    <div style="background-color:white;" class="col-lg-4 col-md-5 col-sm-6 p-5 mx-auto mt-5 mb-sm-5 shadow rounded">
      <div class="inner">
        <h2 class="text-center mb-4">Student's Sign in</h2><br>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <input class="form-control" type="text" name="usermail" value="<?php echo $usermail; ?>" placeholder="Username or E-mail">
          <div class="error mt-2"><?php echo $usermailerr; ?></div><br>
          <input class="form-control" type="password" name="password" placeholder="Password">
          <div class="error mt-2"><?php echo $passerr; ?></div><br>
          <input class="form-control btn btn-primary" type="submit" name="btn1" value="Sign in"><br><br>
        </form>
        <?php echo $message; ?>
        <div class="clearfix mt-3">
          <span class="float-left"><a href="student_forgot_pass.php">Forgot Password?</a></span>
          <span class="float-right"><a href="student_signup.php">Sign up</a></span>
        </div>
      </div>
    </div>

  </body>
</html>
