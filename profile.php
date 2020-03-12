<?php
  session_start();
  if(!isset($_SESSION['username']))
    echo "<script>location.replace('index.php');</script>";
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

    <?php include 'authenticate.php'; ?>
    <?php
      if($_SERVER['REQUEST_METHOD'] == "POST") {
        if(!empty($_POST['sign_out'])) {
          session_unset();
          session_destroy();
          echo "<script>location.replace('index.php');</script>";
        }
      }

      $firstname = $_SESSION['firstname'];
      $lastname = $_SESSION['lastname'];
      $username = $_SESSION['username'];
      $email = $_SESSION['email'];
      $about = (isset($_SESSION['about']) ? $_SESSION['about'] : "");
      $old_pass = $new_pass = "";
      $nameerr = $abouterr = $passerr = "";
      $query_err = "";
      $table = (isset($_SESSION['about']) ? "admins" : "users");

      if($_SERVER['REQUEST_METHOD'] == "POST") {
        if(!empty($_POST['edit_submit'])) {
          if(!empty($_POST['firstname']) && ($_POST['firstname'] != $_SESSION['firstname'])) {
            $firstname = testIt($_POST['firstname']);
            if(!preg_match("/^[a-zA-Z]*$/", $firstname))
              $nameerr = "Sorry, only letters (a-z) are allowed.";
          }
          if(!empty($_POST['lastname']) && ($_POST['lastname'] != $_SESSION['lastname'])) {
            $lastname = testIt($_POST['lastname']);
            if(!preg_match("/^[a-zA-Z]*$/", $lastname))
              $nameerr = "Sorry, only letters (a-z) are allowed.";
          }
          if(!empty($_POST['about']) && ($_POST['about'] != $_SESSION['about'])) {
            $about = testIt($_POST['about']);
            if(strlen($about) > 50)
              $abouterr = "Use 50 characters or less for your 'about' section.";
          }
          if(!empty($_POST['old_pass']) || !empty($_POST['new_pass']) || !empty($_POST['new_pass_confirm'])) {
            $sql = "SELECT PASSWORD FROM ".$table." WHERE USERNAME = '$username'";
            $result = mysqli_query($conn, $sql);
            $entry = mysqli_fetch_assoc($result);
            $old_pass = md5(htmlspecialchars($_POST['old_pass']));
            if($old_pass === $entry['PASSWORD']) {
              if(empty($_POST['new_pass']))
                $passerr = "Enter a new password.";
              else {
                $new_pass = $copy = $_POST['new_pass'];
                $new_pass = trim($new_pass);
                $confirm = $_POST['new_pass_confirm'];

                if($new_pass !== $copy)
                  $passerr = "Your new password can't start or end with a blank space.";
                else if(strlen($new_pass) < 8)
                  $passerr = "Use 8 characters or more for your new password.";
                else if(strlen($new_pass) > 30)
                  $passerr = "Use 30 characters or fewer for your new password.";
                else if(empty($confirm))
                  $passerr = "Confirm your new password.";
                else if($new_pass !== $confirm)
                  $passerr = "New password and confirm didn't match. Please try again.";
                else
                  $new_pass = md5(htmlspecialchars($new_pass));
              }
            }
            else
              $passerr = "Sorry, entered old password didn't match.";
          }

          if(empty($nameerr) && empty($abouterr) && empty($passerr)) {

            if($firstname != $_SESSION['firstname']) {
              $sql = "UPDATE ".$table." SET FIRSTNAME = '$firstname' WHERE USERNAME = '$username'";
              if(mysqli_query($conn, $sql) === FALSE)
                $query_err = "Sorry, unexpected error occured while updating name";
            }
            if($lastname != $_SESSION['lastname']) {
              $sql = "UPDATE ".$table." SET LASTNAME = '$lastname' WHERE USERNAME = '$username'";
              if(mysqli_query($conn, $sql) === FALSE)
                $query_err = "Sorry, unexpected error occured while updating name";
            }
            if($table === "admins" && $about != $_SESSION['about']) {
              $sql = "UPDATE ".$table." SET ABOUT = '$about' WHERE USERNAME = '$username'";
              if(mysqli_query($conn, $sql) === FALSE)
                $query_err = empty($query_err) ? "Sorry, unexpected error occured while updating about section" : ", about section";
            }
            if(!empty($new_pass)) {
              $sql = "UPDATE ".$table." SET PASSWORD = '$new_pass' WHERE USERNAME = '$username'";
              if(mysqli_query($conn, $sql) === FALSE)
                $query_err = empty($query_err) ? "Sorry, unexpected error occured while updating password" : ", password";
            }

            if(empty($query_err))
              echo "<script>alert(\"Your profile is updated.\");</script>";
            else
              echo "<script>alert(\"".$query_err.". Please try again later.\");</script>";
          }
          else {
            echo "<script>
                    $(document).ready(function() {
                      $('.edit_info').removeClass(\"d-none\");
                      $('input[name=\"firstname\"]').attr(\"readonly\", false);
                      $('input[name=\"lastname\"]').attr(\"readonly\", false);
                      $('.username').addClass(\"d-none\");
                      $('.email').addClass(\"d-none\");
                      $('input[name=\"about\"]').attr(\"readonly\", false);
                      $('.change_pass').removeClass(\"d-none\");
                      $('.edit_submit').removeClass(\"d-none\");
                      $('.error').removeClass(\"d-none\");
                      $('button[name=\"edit\"]').addClass(\"disabled\");
                    });
                  </script>";
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

    <title>Home</title>
    <style media="screen">
      .error {
        font-size: 0.8rem;
        color: red;
      }
    </style>
  </head>

  <body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top shadow-lg">
      <a class="navbar-brand" href="home.php">
        <img class="mt-n1 pr-1" src="logo.png" alt="Logo" style="height:2rem;">
        <strong style="font-family:'Caveat', 'Times New Roman', cursive; letter-spacing:4px;"> WebNotices</strong>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".links"><span class="navbar-toggler-icon"></span></button>

      <div class="collapse navbar-collapse links">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><?php echo $_SESSION['firstname']." ".$_SESSION['lastname'] ?></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href=<?php echo (isset($_SESSION['about'])) ? "admin_profile.php" : "student_profile.php"; ?> >Profile</a>
              <?php if(isset($_SESSION['about'])) echo "<a class=\"dropdown-item\" href=\"newNotice.php\">New Notice</a>"; ?>
              <?php if(isset($_SESSION['about'])) echo "<a class=\"dropdown-item\" href=\"myNotices.php\">My Notices</a>"; ?>
              <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input class="dropdown-item" type="submit" name="sign_out" value="Sign out">
              </form>
            </div>
          </li>
        </ul>
      </div>

    </nav>

    <div class="p-4 mb-5"></div>        <!-- for filling space under navigational bar -->
    <!-- for 'My Profile' bar -->
    <div class="clearfix text-center">
      <div style="font-size:1.8rem;" class="ml-md-5 float-md-left"><strong>My Profile</strong></div>
      <a class="mr-md-5 mt-md-1 mt-2 float-md-right btn btn-danger" href="home.php">Back to Home</a>
    </div>
    <br><br>

    <div class="px-4">
      <div style="border:0.1rem solid #17A2B8;" class="col-sm-10 col-md-9 mx-auto p-3 pb-5 rounded-lg">
        <div class="text-right"><button class="btn btn-outline-primary py-0 mx-4" name="edit">Edit</button></div><br>
        <div class="edit_info px-4 py-1 bg-warning text-white rounded-lg mb-4 d-none">
          If you don't want any particular field to edit then please leave that field as such.
        </div>
        <form class="" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <div class="name clearfix">
            <strong style="font-size:1.4rem;" class="float-left mx-2 mx-sm-4">Name:</strong>
            <input class="form-control col-3 col-md-4 float-left mx-1 mx-sm-2" type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="Firstname" readonly>
            <input class="form-control col-3 col-md-4 float-left mx-1 mx-sm-2" type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="Lastname" readonly>
          </div>
          <div class="error mx-2 mx-sm-4 mt-2 d-none"><?php echo $nameerr; ?></div>
          <div class="username clearfix mt-4">
            <strong style="font-size:1.4rem;" class="float-left mx-2 mx-sm-4">Username:</strong>
            <input class="form-control col-4 col-md-6 float-left mx-1 mx-sm-2" type="text" name="username" value="<?php echo $username; ?>" readonly>
          </div>
          <div class="email clearfix mt-4">
            <strong style="font-size:1.4rem;" class="float-left mx-2 mx-sm-4">Email:</strong>
            <input class="form-control col-4 col-md-6 float-left mx-1 mx-sm-2" type="text" name="email" value="<?php echo $email; ?>" readonly>
          </div>
          <?php
            if(isset($_SESSION['about'])) {
              echo "<div class=\"about clearfix mt-4\">".
                      "<strong style=\"font-size:1.4rem;\" class=\"float-left mx-2 mx-sm-4\">About:</strong>".
                      "<input class=\"form-control col-4 col-md-6 float-left mx-1 mx-sm-2\" type=\"text\" name=\"about\" value=\"".$about."\" placeholder=\"About\" readonly>".
                    "</div>".
                    "<div class=\"error mx-2 mx-sm-4 mt-2 d-none\">".$abouterr."</div>";
            }
          ?>
          <div class="change_pass mt-4 d-none">
            <div class="mb-3"><strong style="font-size:1.4rem;" class="mx-2 mx-sm-4">Change Password</strong></div>
            <div class="old_pass clearfix mb-4">
              <strong style="font-size:1.2rem;" class="float-left mx-2 mx-sm-4 pl-2">Old Password:</strong>
              <input class="form-control col-4 col-md-6 float-left mx-1 mx-sm-2" type="password" name="old_pass" placeholder="Old Password">
            </div>
            <div class="new_pass clearfix mb-4">
              <strong style="font-size:1.2rem;" class="float-left mx-2 mx-sm-4 pl-2">New Password:</strong>
              <input class="form-control col-4 col-md-6 float-left mx-1 mx-sm-2" type="password" name="new_pass" placeholder="New Password">
            </div>
            <div class="new_pass_confirm clearfix">
              <strong style="font-size:1.2rem;" class="float-left mx-2 mx-sm-4 pl-2">Confirm:</strong>
              <input class="form-control col-4 col-md-6 float-left mx-1 mx-sm-2" type="password" name="new_pass_confirm" placeholder="Confirm">
            </div>
          </div>
          <div class="error mx-2 mx-sm-4 mt-2 d-none"><?php echo $passerr; ?></div>
          <div class="edit_submit text-center mt-4 d-none">
            <input class="btn btn-primary" type="submit" name="edit_submit" value="Save Changes">
          </div>

        </form>
      </div><br><br>
    </div>

    <script>
      $(document).ready(function() {
        $('button[name="edit"]').click(function() {
          $('.edit_info').removeClass("d-none");
          $('input[name="firstname"]').attr("readonly", false);
          $('input[name="lastname"]').attr("readonly", false);
          $('.username').addClass("d-none");
          $('.email').addClass("d-none");
          $('input[name="about"]').attr("readonly", false);
          $('.change_pass').removeClass("d-none");
          $('.edit_submit').removeClass("d-none");
          $('.error').removeClass("d-none");
          $('button[name="edit"]').addClass("disabled");
        });
      });
    </script>

    <?php mysqli_close($conn); ?>

  </body>
</html>
