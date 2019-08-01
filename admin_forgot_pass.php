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

        $usermail = $usermailerr = $email = $code = ""; $sent_time = 0;   //for div class = "forgot_pass"
        $otp = $otperr = ""; $receive_time = 0;     //for div class = "type_code"
        $password = $passerr = $confirmerr = "";    //for div class = "new_pass"
        $message = "";

        if(empty($_POST['next2']) || empty($_POST['next3']))
          echo "<script>
                  $(document).ready(function() {
                    $('.forgot_pass').addClass('show');
                    $('.type_code').removeClass('show');
                    $('.new_pass').removeClass('show');
                  });
                </script>";

        $js1 = $js2 = $js3 = "<script></script>";

        if($_SERVER["REQUEST_METHOD"] == 'POST') {

          if(!empty($_POST['next1'])) {
              echo "<script>
                      $(document).ready(function() {
                        $('.forgot_pass').addClass('show');
                        $('.type_code').removeClass('show');
                        $('.new_pass').removeClass('show');
                      });
                    </script>";

            if(empty($_POST['usermail']))
              $usermailerr = "Enter username or email.";
            else {
              $usermail = testIt($_POST['usermail']);
              if(!filter_var($usermail, FILTER_VALIDATE_EMAIL))
                $sql = "SELECT FIRSTNAME, LASTNAME, EMAIL FROM admins WHERE USERNAME = '$usermail'";
              else
                $sql = "SELECT FIRSTNAME, LASTNAME, EMAIL FROM admins WHERE EMAIL = '$usermail'";

              $result = mysqli_query($conn, $sql);
              if (mysqli_num_rows($result) > 0) {
                while($entry = mysqli_fetch_assoc($result)) {
                  $email = $entry['EMAIL'];
                  $firstname = $entry['FIRSTNAME'];
                  $lastname = $entry['LASTNAME'];
                }
              }
              else
                $usermailerr = "Sorry, entered username or email is not associated with any account.";
            }

            if(empty($usermailerr)) {
              $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&=?';
              $code = substr(str_shuffle($permitted_chars),0,8);

              $to = $email;
              $subject = "Recovery of Account";
              $msg = "
              <html>
              <body>
                <p><strong>Mr/s. $firstname $lastname</strong>, here is your <strong>password resetting code</strong>.</p>
                <h1 style=\"text-align:center\">$code</h1>
                <h3><strong>Code is active for only 2 minutes.</strong></h3>
              </body>
              </html>
              ";
              $header = "MIME-Version: 1.0" . "\r\n";
              $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
              $header .= "From: noreply@website_name.com" . "\r\n";
              if(mail($to, $subject, $msg, $header)) {
                $_SESSION["sent_time"] = time();
                $_SESSION["code"] = md5($code);
                $_SESSION["email"] = $email;
                $js1 = "<script>
                          $(document).ready(function(){
                            $('.forgot_pass').collapse('hide');
                            $('.type_code').collapse('show');
                          });
                        </script>";
                $message = "";

                //echo $code;
              }
              else
                $message = "Sorry, email containing password resetting code can't be send right now. Try again later!";
            }
          }

          if(!empty($_POST['next2'])) {
            $receive_time = time();
            $sent_time = (isset($_SESSION['sent_time'])) ? $_SESSION['sent_time'] : 0;
            $code = (isset($_SESSION['code'])) ? $_SESSION['code'] : "";

            echo "<script>
                    $(document).ready(function() {
                      $('.forgot_pass').removeClass('show');
                      $('.type_code').addClass('show');
                      $('.new_pass').removeClass('show');
                    });
                  </script>";

            if(empty($_POST['otp']))
              $otperr = "Enter password resetting code.";
            else {
              unset($_SESSION['sent_time']);
              unset($_SESSION['code']);
              $otp = md5($_POST['otp']);

              if($receive_time - $sent_time > 120)
                $otperr = "Sorry, password resetting code is now destroyed.";
              else if($otp === $code) {
                $js2 = "<script>
                          $(document).ready(function() {
                            $('.type_code').collapse('hide');
                            $('.new_pass').collapse('show');
                          });
                        </script>";
                $message = "";
              }
              else
                $otperr = "Code didn't match. Please go back to receive another password resetting code.";
            }
          }

          if(!empty($_POST['next3'])) {
            echo "<script>
                    $(document).ready(function() {
                      $('.forgot_pass').removeClass('show');
                      $('.type_code').removeClass('show');
                      $('.new_pass').addClass('show');
                    });
                  </script>";

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
              else {
                $password = md5(htmlspecialchars($password));
                $email = (isset($_SESSION['email'])) ? $_SESSION['email'] : "";
                if(!empty($email)) {
                  session_unset();
                  session_destroy();

                  $sql = "UPDATE admins SET PASSWORD = '$password' WHERE EMAIL = '$email'";
                  if(mysqli_query($conn, $sql) === TRUE)
                    echo "<script>alert(\"Password updated. Redirecting to sign in page.\");</script>";
                  else
                    echo "<script>alert(\"Sorry, unexpected error occured. Please try again later. Redirecting to sign in page.\");</script>";
                  echo "<script>location.replace('admin_signin.php');</script>";
                }
              }
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

    <title>Find Your Account</title>
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
    <div style="background-color:white;" class="col-lg-4 col-md-5 col-sm-6 p-5 mx-auto mt-5 mb-sm-5 shadow-sm rounded">

      <div class="forgot_pass connOne collapse">
        <h2 class="text-center">Find your account</h2><br>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <h5 class="text-center">Enter your username or email</h5><br>
          <input class="form-control" type="text" name="usermail" value="<?php echo $usermail; ?>" placeholder="Username or E-mail">
          <div class="error mt-2"><?php echo $usermailerr; ?></div><br>
          <input class="next col-4 btn btn-primary float-right" type="submit" name="next1" value="Next"><br><br>
        </form>
        <br>
        <div class="text-center"><a href="admin_signin.php">Sign in</a></div>
      </div>

      <div class="type_code connOne connTwo collapse">
        <h2 class="text-center">Find your account</h2><br>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <h5 class="text-center">Enter password resetting code sent to your email '<?php echo (isset($_SESSION['email']) ? $_SESSION['email'] : ""); ?>'</h5><br>
          <input class="form-control" type="password" name="otp" placeholder="Password Resetting Code">
          <div class="error mt-2"><?php echo $otperr; ?></div><br>
          <button class="back col-4 btn btn-outline-primary" type="button" name="back2" data-toggle="collapse" data-target=".connOne">Back</button>
          <input class="next col-4 btn btn-primary float-right" type="submit" name="next2" value="Next">
        </form>
        <br>
        <div class="text-center"><a href="admin_signin.php">Sign in</a></div>
      </div>

      <div class="new_pass connTwo collapse">
        <h2 class="text-center">Find your account</h2><br>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <h5 class="text-center">Set new password for your account</h5><br>
          <input class="form-control" type="password" name="password" placeholder="Password">
          <div class="error mt-2"><?php echo $passerr; ?></div><br>
          <input class="form-control" type="password" name="confirm" placeholder="Confirm">
          <div class="error mt-2"><?php echo $confirmerr; ?></div><br>
          <button class="back col-4 btn btn-outline-primary" type="button" name="back3" data-toggle="collapse" data-target=".connTwo">Back</button>
          <input class="next col-4 btn btn-primary float-right" type="submit" name="next3" value="Confirm">
        </form>
        <br>
        <div class="text-center"><a href="admin_signin.php">Sign in</a></div>
      </div>

    </div>

    <?php
      echo $js1;
      echo $js2;
      if(!empty($message))
        echo "<script>alert($message);</script>";
    ?>
  </body>

</html>
