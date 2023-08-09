<?php
include 'config/dbconnection.php';

if (isset($_GET['election_id']) && is_numeric($_GET['election_id'])) {
  $election_id = $_GET['election_id'];
} else {
  echo '<script>window.history.back();</script>';
  exit;
}

$row = $conn->prepare("SELECT * FROM election");
$row->execute();
$result = $row->get_result();
?>

<form id="add-category-form">
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-md-12">
        <label for="election" class="form-label">Election</label>
        <select class="form-select" name="election" id="election" data-error-message="Select Election">
          <option value="" hidden>Select Election</option>
          <?php
          foreach ($result as $key => $value) {
            echo '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
          }
          ?>
        </select>
        <div class="invalid-feedback"></div>
      </div>
      <div class="col-md-12">
        <label for="category" class="form-label">Category Name</label>
        <input type="text" name="category" class="form-control" id="category" data-error-message="Category is required">
        <div class="invalid-feedback"></div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="reset" class="btn btn-secondary">Reset</button>
    <button type="submit" class="btn btn-primary">Add Category</button>
  </div>
</form>

<!-- Add Category JQuery -->
<script>
  $('#add-category-form').submit(function(e) {
    e.preventDefault()

    let isValid = true;
    $("#add-category-form").find("input, select").each(function() {
      const input = $(this);
      const value = input.val().trim();
      const errorMessage = input.data("error-message");
      if (value === "") {
        isValid = false;
        input.addClass("is-invalid"); // Add a CSS class for invalid inputs
        input.siblings(".invalid-feedback").text(errorMessage); // Show error message
      } else {
        input.removeClass("is-invalid"); // Remove the CSS class if input is valid
        input.siblings(".invalid-feedback").text(""); // Clear error message
      }
    });

    if (isValid) {

      $.ajax({
        url: 'controllers/app.php?action=add_category',
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp) {
          var response = JSON.parse(resp);
          if (response.status === 'success') {
            location.href = response.redirect_url;
          } else if (response.status === 'errors') {
            $('#add-category-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');

          } else {
            $('#add-category-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
          }
        }
      })
    }
  })
  // Real-time validation when the user enters the required field
  $("#add-category-form").find("input, select").on("input", function() {
    const input = $(this);
    const value = input.val().trim();
    const errorMessage = input.data("error-message");

    if (value === "") {
      input.addClass("is-invalid"); // Add a CSS class for invalid inputs
      input.siblings(".invalid-feedback").text(errorMessage); // Show error message
    } else {
      input.removeClass("is-invalid"); // Remove the CSS class if input is valid
      input.siblings(".invalid-feedback").text(""); // Clear error message
    }
  });
</script>