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

    ?>

    <title>Home</title>
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
              <a class="dropdown-item" href="profile.php" >Profile</a>
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

    <div class="p-4 mb-5"></div>

    <?php
      // setting welcome bar
      if(isset($_SESSION['about'])) {         // for setting welcome notice with other options when an admin signed in
        echo "<div class=\"clearfix text-center\">".
                "<div style=\"font-size:1.8rem;\" class=\" ml-md-5 float-md-left\"><strong>Welcome, ".$_SESSION['firstname']." ".$_SESSION['lastname']."</strong></div>".
                "<div class=\"mr-md-5 mt-md-1 mt-2 float-md-right\">".
                  "<a class=\"btn btn-primary mx-2\" href=\"newNotice.php\">Post new notice</a>".
                  "<a class=\"btn btn-warning mx-2\" href=\"myNotices.php\">My notices</a>".
                "</div>".
              "</div>";
      }
      else                // for setting welcome notice when a student signed in
        echo "<div style=\"font-size:1.8rem;\" class=\"ml-md-5 text-center text-md-left\"><strong>Welcome, ".$_SESSION['firstname']." ".$_SESSION['lastname']."</strong></div>";

      $err = "";
      $active_page = 1;       // default value of $active_page

      // counting total no of notices stored in 'notices' table and accordingly evaluating $total_pages required
      $sql = "SELECT COUNT(*) AS total_rows FROM notices";
      $result = mysqli_query($conn,$sql);
      $entry = mysqli_fetch_assoc($result);
      if($entry['total_rows'] > 0)
        $total_pages = ceil($entry['total_rows']/10);
      else {
        echo "<div style=\"font-size:1.2rem;\" class=\"py-5 my-5 text-center text-secondary\">No notice have been posted yet.</div>";
        exit();
      }

      // start of posting the notices
      echo "<div class=\"clearfix\"><div class=\"col-lg-6 float-lg-right py-lg-5 my-lg-5\"></div>";  // for having notices at unequal height with opening "clearfix" div
      if($_SERVER['REQUEST_METHOD'] == "POST") {      // finding active page
        for($x = 1; $x <= $total_pages; $x++) {
          if(!empty($_POST[$x])) {
            $active_page = $x;
            break;
          }
        }
      }

      $offset_value = ($active_page-1)*10;
      $sql = "SELECT * FROM notices ORDER BY UPLOADTIME DESC LIMIT 10 OFFSET $offset_value";
      $result = mysqli_query($conn, $sql);

      $loop_var = 0;
      $float_arr = array("float-lg-left pr-lg-4", "float-lg-right pl-lg-4");
      $border_arr = array("#007BFF;", "#DC3545;", "#FFC107;", "#28A745;");

      while($entry = mysqli_fetch_assoc($result)) {
        $filename = $entry['FILENAME'];
        $subject = $entry['SUBJECT'];
        $uploadtime = $entry['UPLOADTIME'];
        $text = file_get_contents($filename);

        $a_username = $entry['ADMIN'];    // $a_username = admin username
        $a_result = mysqli_query($conn, "SELECT FIRSTNAME, LASTNAME, ABOUT FROM admins WHERE USERNAME = '$a_username'");
        $a_entry = mysqli_fetch_assoc($a_result);
        $a_firstname = $a_entry['FIRSTNAME'];
        $a_lastname = $a_entry['LASTNAME'];
        $a_about = $a_entry['ABOUT'];

          echo "<div class=\"col-lg-6 ".$float_arr[$loop_var%2]." pt-5 px-5 pb-0\">".
                  "<div style=\"border:0.15rem solid ".$border_arr[$loop_var%4]." border-radius:1rem;\" class=\"p-3 box\">".
                    "<div style=\"font-size:1.4rem;\"><strong>".htmlspecialchars($subject)."</strong></div>".
                    "<div style=\"font-size:0.9rem;\" class=\"text-right pt-1 pb-2\">".
                      "<span class=\"text-info mx-1 mx-sm-3\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"bottom\" data-content=\"@".$a_username.", ".$a_about."\">by ".$a_firstname." ".$a_lastname."</span>".
                      "<span class=\"text-secondary mx-1 mx-sm-3\">on ".$uploadtime."</span>".
                    "</div>".
                    "<textarea style=\"resize:none; white-space:pre; font-size:0.8em;\" ".
                      "class=\"form-control border-0\" rows=\"22\" readonly>".htmlspecialchars($text)."</textarea>".
                  "</div>".
                "</div>";

          ++$loop_var;
        }
        echo "</div><br><br>";    // closing "clearfix" div

      ?>

      <!-- dynamic buttons for pages -->
      <div class="text-center">
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

      <script>        // script used to trigger popover to show username and about of author of notice in notices printing
        $(document).ready(function() {
          $('[data-toggle="popover"]').popover();
        });
      </script>

      <?php
        mysqli_close($conn);
      ?>

    </body>
  </html>
