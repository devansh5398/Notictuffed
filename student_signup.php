<?php
  session_start();
  if(isset($_SESSION['username']) && !isset($_SESSION['code']))
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

        $firstname = $lastname = $username = $password = $email = "";
        $firsterr = $lasterr = $usererr = $passerr = $confirmerr = $emailerr = "";
        $message = $code = $otp = $otperr = "";
        $form1_time = $form2_time = 0;

        if(empty($_POST['next2']))
          echo "<script>
                  $(document).ready(function() {
                    $('.sign_up').addClass('show');
                    $('.verification').removeClass('show');
                  });
                </script>";

        $js1 = "<script></script>";

      if($_SERVER["REQUEST_METHOD"] == 'POST') {
        if(!empty($_POST['next1'])) {
          $form1_time = time();

          echo "<script>
                  $(document).ready(function() {
                    $('.sign_up').addClass('show');
                    $('.verification').removeClass('show');
                  });
                </script>";

          if(empty($_POST['firstname']))
            $firsterr = "Enter first name.";
          else {
            $firstname = testIt($_POST['firstname']);
            if (!preg_match("/^[a-zA-Z]*$/", $firstname))
              $firsterr = "Sorry, only letters (a-z) are allowed.";
          }

          $lastname = testIt($_POST['lastname']);
          if (!preg_match("/^[a-zA-Z]*$/", $lastname))
            $lasterr = "Sorry, only letters (a-z) are allowed.";

          if(empty($_POST['username']))
            $usererr = "Choose a username.";
          else {
            $username = testIt($_POST['username']);
            $sql = "SELECT * FROM users WHERE USERNAME = '$username'";
            $result = mysqli_query($conn, $sql);

            if (!preg_match("/^[a-zA-Z0-9_.\-]*$/", $username))
              $usererr = "Sorry, only letters (a-z), numbers (0-9) and special characters (. _ -) are allowed.";
            else if($username[0]=='_' || $username[0]=='.' || $username[0]=='-' )
              $usererr = "Sorry, first character of your username must be an ascii letter(a-z) or number (0-9).";
            elseif(mysqli_num_rows($result) > 0)
              $usererr = "Sorry, an account already exists with this username.";
          }

          if(empty($_POST['password']))
            $passerr = "Enter a password.";
          else {
            $password = $copy = $_POST['password'];
            $password = trim($password);
            $confirm = $_POST['confirm'];

            if($password !== $copy)
              $passerr = "Your password can't start or end with a blank space.";
            else if(strlen($password) < 8)
              $passerr = "Use 8 characters or more for your password.";
            else if(strlen($password) > 30)
              $passerr = "Use 30 characters or fewer for your password.";
            else if(empty($confirm))
              $confirmerr = "Confirm your password.";
            else if($password !== $confirm)
              $confirmerr = "Passwords didn't match. Please try again.";
            else
              $password = md5(htmlspecialchars($password));
          }

          if(empty($_POST['email']))
            $emailerr = "Enter an email.";
          else {
            $email = testIt($_POST['email']);
            $sql = "SELECT * FROM users WHERE EMAIL = '$email'";
            $result = mysqli_query($conn, $sql);

            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
              $emailerr = "Sorry, your email is not valid.";
            elseif (mysqli_num_rows($result) > 0)
              $emailerr = "Sorry, an account is already exist with this email.";
          }

          if(empty($firsterr) && empty($lasterr) && empty($usererr) && empty($passerr) && empty($confirmerr) && empty($emailerr)) {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&=?';
            $code = substr(str_shuffle($permitted_chars),0,8);
            $to = $email;
            $subject = "Signup of Account";
            $msg = "
            <html>
            <body>
              <p>Thank you <strong>Mr/s. $firstname $lastname</strong> for creating an account.</p>
              <p>You are just one step away for registering your account. Here is your <strong>activation code</strong>.</p>
              <h1 style=\"text-align:center\">$code</h1>
              <h3><strong>Code is active for only 2 minutes.</strong></h3>
            </body>
            </html>
            ";
            $header = "MIME-Version: 1.0" . "\r\n";
            $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $header .= "From: noreply@website_name.com" . "\r\n";
            if(mail($to, $subject, $msg, $header)) {
              //$display = "block";
              $_SESSION["firstname"] = $firstname;
              $_SESSION["lastname"] = $lastname;
              $_SESSION["username"] = $username;
              $_SESSION["password"] = $password;
              $_SESSION["email"] = $email;
              $_SESSION["code"] = md5($code);
              $_SESSION["form1_time"] = $form1_time;
              $js1 = "<script>
                        $(document).ready(function() {
                          $('.sign_up').collapse('hide');
                          $('.verification').collapse('show');
                        });
                      </script>";
              $message = "";

              //echo $code;
            }
            else
              $message = "Sorry, email containing activation code can't be send right now. Try again later!";
          }
        }
        if(!empty($_POST['next2'])) {
          $form2_time = time();
          $form1_time = isset($_SESSION['form1_time']) ? $_SESSION['form1_time'] : 0;

          echo "<script>
                  $(document).ready(function() {
                    $('.sign_up').removeClass('show');
                    $('.verification').addClass('show');
                  });
                </script>";

          $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";

          if(empty($_POST['otp']))
            $otperr = "Please enter activation code.";
          else {
            $otp = md5($_POST['otp']);
            $code = isset($_SESSION['code']) ? $_SESSION['code'] : "";

            $firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "";
            $lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : "";
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
            $password = isset($_SESSION['password']) ? $_SESSION['password'] : "";

            session_unset();
            session_destroy();

            if($form2_time - $form1_time > 120)
              $otperr = "Sorry, activation Code is now destroyed. Please sign up again to receive another activation code.";
            else if($code === $otp) {
              $sql = "INSERT INTO users VALUES ('$firstname', '$lastname', '$username', '$password', '$email')";
              if(mysqli_query($conn, $sql) === TRUE)
                echo "<script>alert('Your account is created. Redirecting to sign in page.');</script>";
              else
                echo "<script>alert('Sorry, unexpected error occured. Please try again later. Redirecting to sign in page.');</script>";
              echo "<script>location.replace('student_signin.php');</script>";
            }
            else
              $otperr = "Code didn't match. Please sign up again to receive another activation code.";
          }
        }
      }

      function testIt($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

      mysqli_close($conn);
    ?>

    <title>Student's SignUp</title>
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
    <div style="background-color:white;" class="col-lg-5 col-md-6 col-sm-7 p-5 mx-auto mt-5 mb-sm-5 shadow rounded">

      <div class="sign_up connOne collapse">
        <h2 class="text-center">Student's Sign up</h2><br>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <div class="col-sm-6 p-0 pl-sm-0 pr-sm-4 float-sm-left">
            <input class="form-control" type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="First name">
            <div class="error mt-2"><?php echo $firsterr; ?></div><br>
          </div>
          <div class="col-sm-6 p-0 pl-sm-4 pr-sm-0 float-sm-right">
            <input class="form-control" type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="Last name">
            <div class="error mt-2"><?php echo $lasterr; ?></div><br>
          </div>
          <input class="form-control" type="text" name="username" value="<?php echo $username; ?>" placeholder="Username">
          <div class="error mt-2"><?php echo $usererr; ?></div><br>
          <div class="col-sm-6 p-0 pl-sm-0 pr-sm-4 float-sm-left">
            <input class="form-control" type="password" name="password" placeholder="Password">
            <div class="error mt-2"><?php echo $passerr; ?></div><br>
          </div>
          <div class="col-sm-6 p-0 pl-sm-4 pr-sm-0 float-sm-right">
            <input class="form-control" type="password" name="confirm" placeholder="Confirm">
            <div class="error mt-2"><?php echo $confirmerr; ?></div><br>
          </div>
          <input class="form-control" type="text" name="email" value="<?php echo $email; ?>" placeholder="Email">
          <div class="error mt-2"><?php echo $emailerr; ?></div><br>
          <input class="form-control btn btn-primary" type="submit" name="next1" value="Sign up">
        </form>
        <br><br>
        <div class="text-center">Have an account? <a href="student_signin.php">Sign in</a></div>
      </div>

      <div class="verification connOne collapse">
        <h2 class="text-center">E-mail verification</h2><br>
        <h5 class="text-center">An activation code is send to your email address '<?php echo $email; ?>'. Enter the code below to acivate your account.</h5><br>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <input class="form-control" type="password" name="otp" placeholder="Activation Code">
          <div class="error mt-2"><?php echo $otperr; ?></div><br>
          <button class="back col-4 btn btn-outline-primary float-left" type="button" name="back2" data-toggle="collapse" data-target=".connOne">Back</button>
          <input class="next col-4 form-control btn btn-primary float-right" type="submit" name="next2" value="Confirm">
        </form>
        <br><br>
        <div class="text-center">Have an account? <a href="student_signin.php">Sign in</a></div>
      </div>

    </div>

    <?php
      echo $js1;
      if(!empty($message))
        echo "<script>alert(\"".$message."\");</script>";
    ?>
  </body>

</html>
