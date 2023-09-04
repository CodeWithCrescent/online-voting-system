<?php
session_start();

include 'config/dbconnection.php';
include 'config/session.php';

echo '<!DOCTYPE html>
<html lang="en" id="results">';

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
    // http_response_code(404);
    include "404.php";
  }
  ?>
</main><!-- End #main -->


<?php
include 'includes/footer.php';

include 'includes/scripts.php';

if (isset($_GET['msg'])) {
  $msg = urldecode($_GET['msg']);
  echo '<script>toastr.success("' . $msg . '");
  setTimeout(() => {
      window.location.href = "index.php?page=reports"
    }, 2500);</script>';
} elseif (isset($_GET['err'])) {
  $err = urldecode($_GET['err']);
  echo '<script>toastr.error("' . $err . '");
  setTimeout(() => {
    window.location.href = "index.php?page=results"
  }, 2500);</script>';
}
?>

</body>

</html>

<script type="text/javascript">
  function printDiv(results) {
    var elementsToRemove = document.querySelectorAll("a, header, [id='hide']");
    var removedElements = [];

    elementsToRemove.forEach(function(element) {
      removedElements.push(element);
      element.remove();
    });

    var elementsToCenter = document.querySelectorAll("[id='center_div']");
    elementsToCenter.forEach(function(element) {
      element.classList.add("text-center");
      var span = document.createElement("span");

      <?php
      $formattedStartTime = date('D j M, Y', strtotime($starttime));
      $formattedEndTime = date('D j M, Y', strtotime($endtime));
      ?>

      span.textContent = "Voting started on <?php echo $formattedStartTime; ?> & Ended on <?php echo $formattedEndTime; ?>";
      element.appendChild(span);
    });

    var printResults = document.getElementById('results').innerHTML;
    var printWindow = window.open();

    printWindow.document.write(printResults);
    printWindow.document.close();
    printWindow.print();
    printWindow.close();

    removedElements.forEach(function(element) {
      document.body.appendChild(element);
    });

    location.reload();
  }
</script>