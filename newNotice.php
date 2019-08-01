<?php
  session_start();
  if(isset($_SESSION['username']) && !isset($_SESSION['about']))
    echo "<script>location.replace('home.php');</script>";
  if(!isset($_SESSION['about']))
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

    <?php
      $server = "localhost";
      $dbuser = "Devansh";
      $dbpass = "Har427Mahadev";
      $db = "root";

      $conn = mysqli_connect($server, $dbuser, $dbpass, $db);
      if(!$conn)
        die("Connection failed: ".mysqli_connect_error());

      $subject = $content = $err = "";

      if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(!empty($_POST['sign_out'])) {
          session_unset();
          session_destroy();
          echo "<script>location.replace('index.php');</script>";
        }

        if(!empty($_POST['post_notice'])) {
          $filename = "./uploadedNotices/".md5(time().$_SESSION['username']).".txt";
          $subject = testIt($_POST['subject']);
          $content = $_POST['content'];
          $admin = $_SESSION['username'];
          date_default_timezone_set("Asia/Kolkata");
          $uploadtime = date("Y-m-d H:i:s");
          $sql = "SELECT * FROM notices WHERE FILENAME = '$filename'";
          $result = mysqli_query($conn, $sql);

          if(empty($content) && empty($subject))
            $err = "Please enter subject and content of notice.";
          else if(empty($subject))
            $err = "Please enter subject of notice.";
          else if(empty($content))
            $err = "Please enter content of notice.";
          else if(strlen($subject) > 100)
            $err = "Use 100 characters or fewer for your notice subject.";
          else if(mysqli_num_rows($result) > 0)
            $err = "Sorry, unexpected error occured. Please try again later.";
          else {
            $sql = "INSERT INTO notices(FILENAME, SUBJECT, ADMIN, UPLOADTIME) VALUES ('$filename', '$subject', '$admin', '$uploadtime')";
            if(mysqli_query($conn, $sql) === FALSE)
              $err = "Sorry, unexpected error occured. Please try again later.";
            else {
              file_put_contents($filename, $content);
              echo "<script>alert('Your notice is uploaded.');</script>";
              $subject = $content = $err = "";
              //unlink("./uploadedNotices/a83540642f914a8e2077b8391aa94ebb.txt");   --> used to delete file with given filename
            }
          }
        }
      }

      function testIt($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
      }

      mysqli_close($conn);
    ?>

    <title>Post new Notice</title>
    <style media="screen">

    </style>
  </head>

  <body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top shadow-lg">
      <a class="navbar-brand" href="home.php">
        <img class="mt-n1 pr-1" src="logo.png" alt="Logo" style="height:2rem;">
        <strong style="font-family:'Caveat', 'Times New Roman', cursive; letter-spacing:4px;"> Notictuffed</strong>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".links"><span class="navbar-toggler-icon"></span></button>

      <div class="collapse navbar-collapse links">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><?php echo $_SESSION['firstname']." ".$_SESSION['lastname'] ?></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="profile.php">Profile</a>
              <a class="dropdown-item active" href="newNotice.php">New Notice</a>
              <a class="dropdown-item" href="myNotices.php">My Notices</a>
              <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input class="dropdown-item" type="submit" name="sign_out" value="Sign out">
              </form>
            </div>
          </li>
        </ul>
      </div>

    </nav>

    <div class="p-4 mb-5"></div>
    <div class="clearfix text-center">
      <div style="font-size:1.8rem;" class="ml-md-5 float-md-left"><strong>Post new Notice</strong></div>
      <a class="mr-md-5 mt-md-1 mt-2 float-md-right btn btn-danger" href="home.php">Back to Home</a>
    </div><br>
    <div class="mx-5 px-3 py-1 bg-warning text-white rounded-lg">
      Please note that all the entered whitespaces and new-line will be posted as such. If no new-line is entered no line-break will occur.
    </div><br><br>
    <form class="col-9 p-0 mx-auto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
      <input style="border:0.15rem solid #17A2B8;" class="form-control shadow" type="text" name="subject" value="<?php echo $subject; ?>" placeholder="Subject of the Notice"><br>
      <textarea style="resize:none; white-space:pre; font-size:0.9rem; border:0.15rem solid #17A2B8;"
      class="form-control rounded-lg shadow-lg" name="content" rows="25"><?php echo htmlspecialchars($content); ?></textarea><br>
      <div class="clearfix">
        <div style="color:red; font-size:0.8rem;" class="float-left"><?php echo $err; ?></div>
        <input class="px-4 btn btn-primary float-right" type="submit" name="post_notice" value="Post">
      </div>
    </form><br><br>

  </body>
</html>
