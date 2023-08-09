<?php
session_start();

include 'config/dbconnection.php';

if (!isset($_SESSION['login_id'])) {
  $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
  header("location:login.php");
  exit();
}
echo '<!DOCTYPE html>
<html lang="en">';

include 'includes/head.php';

echo '<body>';

include 'includes/header.php';

include 'includes/sidebar.php';
?>

<main id="main" class="main">

  <?php
  if ($_SESSION['login_type'] == 0) {
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
  } else {
    $page = isset($_GET['page']) ? $_GET['page'] : 'vote';
  }
  $filePath = $page . '.php';

  if (file_exists($filePath)) {
    include $filePath;
  } else {
    http_response_code(404);
    include "404.php";
  }
  ?>
</main><!-- End #main -->


<?php
include 'includes/footer.php';

include 'includes/scripts.php';
?>

</body>

</html>