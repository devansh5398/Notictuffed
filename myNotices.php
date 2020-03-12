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

    <?php include 'authenticate.php'; ?>
    <?php
      if($_SERVER['REQUEST_METHOD'] == "POST") {        // for sign-out
        if(!empty($_POST['sign_out'])) {
          session_unset();
          session_destroy();
          echo "<script>location.replace('index.php');</script>";
        }
      }

    ?>

    <title>My Notices</title>
    <style media="screen">
      .box {
        box-shadow: 0 0 3rem #CCC;
      }
      .box:hover {
        box-shadow: 0 0 3rem #AAA;
      }
    </style>
  </head>

  <body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top shadow-lg">       <!-- for navigational bar -->
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
              <a class="dropdown-item" href="profile.php">Profile</a>
              <a class="dropdown-item" href="newNotice.php">New Notice</a>
              <a class="dropdown-item active" href="myNotices.php">My Notices</a>
              <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input class="dropdown-item" type="submit" name="sign_out" value="Sign out">
              </form>
            </div>
          </li>
        </ul>
      </div>

    </nav>

    <!-- for filling space under navigational bar -->
    <div class="p-4 mb-5"></div>
    <div class="clearfix text-center">
      <div style="font-size:1.8rem;" class="ml-md-5 float-md-left"><strong>My Notices</strong></div>
      <a class="mr-md-5 mt-md-1 mt-2 float-md-right btn btn-danger" href="home.php">Back to Home</a>
    </div>

    <?php
      $admin = $_SESSION['username'];
      $err = "";
      $active_page = 1;

      // counting notices stored in 'notices' with ADMIN = '$admin' table and accordingly evaluating $total_pages required
      $sql = "SELECT COUNT(*) AS total_rows FROM notices WHERE ADMIN = '$admin'";
      $result = mysqli_query($conn,$sql);
      $entry = mysqli_fetch_assoc($result);
      if($entry['total_rows'] > 0)
        $total_pages = ceil($entry['total_rows']/10);
      else {
        echo "<div style=\"font-size:1.2rem;\" class=\"py-5 my-5 text-center text-secondary\">You haven't posted any notice.</div>";
        exit();
      }

      // start of posting the notices
      echo "<div class=\"clearfix\"><div class=\"col-lg-6 float-lg-right py-lg-5 my-lg-5\"></div>";  // for having notices at unequal height with opening "clearfix" div
      if($_SERVER['REQUEST_METHOD'] == "POST") {        // finding active page
        for($x = 1; $x <= $total_pages; $x++) {
          if(!empty($_POST[$x])) {
            $active_page = $x;
            break;
          }
        }
      }

      $offset_value = ($active_page-1)*10;
      $sql = "SELECT * FROM notices WHERE ADMIN = '$admin' ORDER BY UPLOADTIME DESC LIMIT 10 OFFSET $offset_value";
      $result = mysqli_query($conn, $sql);

      $loop_var = 0;
      $float_arr = array("float-lg-left pr-lg-4", "float-lg-right pl-lg-4");
      $border_arr = array("#007BFF;", "#DC3545;", "#FFC107;", "#28A745;");

      while($entry = mysqli_fetch_assoc($result)) {
        $filename = $entry['FILENAME'];
        $subject = $entry['SUBJECT'];
        $uploadtime = $entry['UPLOADTIME'];
        $text = file_get_contents($filename);

        echo "<div class=\"col-lg-6 ".$float_arr[$loop_var%2]." pt-5 px-5 pb-0\">".
                "<div style=\"border:0.15rem solid ".$border_arr[$loop_var%4]." border-radius:1rem;\" class=\"p-3 box\">".
                  "<div style=\"font-size:1.4rem;\"><strong>".htmlspecialchars($subject)."</strong></div>".
                  "<div style=\"font-size:0.9rem;\" class=\"text-right text-secondary pt-1 pb-2 mr-3\">".htmlspecialchars($uploadtime)."</div>".
                  "<textarea style=\"resize:none; white-space:pre; font-size:0.8em;\" ".
                    "class=\"form-control border-0\" rows=\"22\" readonly>".htmlspecialchars($text)."</textarea>".
                "</div>".
              "</div>";

        ++$loop_var;
      }
      echo "</div><br><br>";    // closing "clearfix" div

    ?>

    <div class="text-center">               <!-- dynamic buttons for pages -->
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <?php
          if($total_pages <=10) {
            for($x = 1; $x <= $total_pages; $x++) {
              if($active_page === $x)
                echo "<input class=\"mx-1 btn btn-dark\" type=\"submit\" name=\"".$x."\" value=".$x.">";
              else
                echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".$x."\" value=".$x.">";
            }
          }       // end of if($total_pages <=10)

          else {

            if($active_page <= 5) {
              for($x = 1; $x <= $active_page+2; $x++) {
                if($active_page === $x)
                  echo "<input class=\"mx-1 btn btn-dark\" type=\"submit\" name=\"".$x."\" value=".$x.">";
                else
                  echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".$x."\" value=".$x.">";
              }

              echo "<button class=\"p-1 btn\"><strong>. . .</strong></button>";
              echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".($total_pages-1)."\" value=".($total_pages-1).">";
              echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".$total_pages."\" value=".$total_pages.">";
            }         // end of if($active_page <= 5)

            else if($active_page > $total_pages-5) {
              echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".(1)."\" value=".(1).">";
              echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".(2)."\" value=".(2).">";
              echo "<button class=\"p-1 btn\"><strong>. . .</strong></button>";

              for($x = $active_page-2; $x <= $total_pages; $x++) {
                if($active_page === $x)
                  echo "<input class=\"mx-1 btn btn-dark\" type=\"submit\" name=\"".$x."\" value=".$x.">";
                else
                  echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".$x."\" value=".$x.">";
              }
            }         // end of else if($active_page > $total_pages-5)

            else {
              echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".(1)."\" value=".(1).">";
              echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".(2)."\" value=".(2).">";
              echo "<button class=\"p-1 btn\"><strong>. . .</strong></button>";

              for($x = $active_page-2; $x <= $active_page+2; $x++) {
                if($active_page === $x)
                  echo "<input class=\"mx-1 btn btn-dark\" type=\"submit\" name=\"".$x."\" value=".$x.">";
                else
                  echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".$x."\" value=".$x.">";
              }

              echo "<button class=\"p-1 btn\"><strong>. . .</strong></button>";
              echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".($total_pages-1)."\" value=".($total_pages-1).">";
              echo "<input class=\"mx-1 btn btn-secondary\" type=\"submit\" name=\"".$total_pages."\" value=".$total_pages.">";
            }       // end of else

          }       // end of outer else
          echo "<br><br><br>";
        ?>
      </form>
    </div>

    <?php
      mysqli_close($conn);
    ?>

  </body>
</html>
