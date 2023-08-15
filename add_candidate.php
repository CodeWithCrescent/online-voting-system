<?php
include 'config/dbconnection.php';

if (isset($_GET['election_id']) && is_numeric($_GET['election_id'])) {
  $election_id = $_GET['election_id'];
} else {
  echo '<script>window.history.back();</script>';
  exit;
}

$stmt = $conn->prepare("SELECT * FROM categories WHERE election_id = ?");
$stmt->bind_param('i', $election_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<form id="add-candidate-form" enctype="multipart/form-data">
  <div class="row g-3">
    <div class="col-md-12">
      <label for="category" class="form-label">Category<span class="text-danger"> *</span></label>
      <input type="hidden" name="election" value="<?php echo $election_id; ?>">
      <select class="form-select" name="category" id="category" data-error-message="Select Category">
        <option value="" hidden>Select Category</option>
        <?php
        foreach ($result as $key => $value) {
          echo '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        ?>
      </select>
      <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-12">
      <label for="candidate" class="form-label">Candidate Name<span class="text-danger"> *</span></label>
      <input type="text" name="candidate" class="form-control" id="candidate" data-error-message="Candidate name is required" placeholder="Enter Candidate name">
      <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6">
      <label for="candidate_photo" class="form-label">Candidate Photo</label>
      <input class="form-control" type="file" id="candidate_photo"  name="candidate_photo" data-error-message="Please upload candidate's photo" placeholder="">
      <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6">
      <label for="candidate_year" class="form-label">Year of Study<span class="text-danger"> *</span></label>
      <select class="form-select" name="candidate_year" id="candidate_year" data-error-message="Select year of study">
        <option value="" hidden>Select Year</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
        <option value="3">Third Year</option>
        <option value="3">Fourth Year</option>
      </select>
      <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-12">
      <label for="fellow_candidate" class="form-label">Fellow Candidate Name</label>
      <input type="text" name="fellow_candidate" class="form-control" id="fellow_candidate" placeholder="If not, Leave empty">
      <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6">
      <label for="fellow_candidate_photo" class="form-label">Fellow Candidate Photo</label>
      <input class="form-control" type="file" id="fellow_candidate_photo"  name="fellow_candidate_photo" data-error-message="Please upload candidate's photo" placeholder="">
      <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6">
      <label for="fellow_candidate_year" class="form-label">Year of Study</label>
      <select class="form-select" name="fellow_candidate_year" id="fellow_candidate_year">
        <option value="" hidden>Select Year</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
        <option value="3">Third Year</option>
        <option value="3">Fourth Year</option>
      </select>
    </div>
  </div>
  <div class="modal-footer">
    <button type="reset" class="btn btn-secondary">Reset</button>
    <button type="submit" class="btn btn-primary">Add Candidate</button>
  </div>
</form>

<!-- Add Candidate JQuery -->
<script>
  $(document).ready(function() {
    $('#add-candidate-form').submit(function(e) {
      e.preventDefault();

      let isValid = true;
      const SpecifiedInputs = ['category', 'candidate', 'candidate_year'];
      $("#add-candidate-form").find("input, select").each(function() {
        const input = $(this);
        const value = input.val().trim();
        const inputName = input.attr("name");
        const errorMessage = input.data("error-message");

        // Check only specified inputs for empty values
        if (SpecifiedInputs.includes(inputName)) {
          if (value === "") {
            isValid = false;
            input.addClass("is-invalid");
            input.siblings(".invalid-feedback").text(errorMessage);
          } else {
            input.removeClass("is-invalid");
            input.siblings(".invalid-feedback").text("");
          }
        }
      });

      if (isValid) {
        // Use FormData for handling file uploads
        var formData = new FormData($("#add-candidate-form")[0]);
        $.ajax({
          url: 'controllers/app.php?action=add_candidate',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function(response) {
            console.log(response);
            if (response.status === 'success') {
              location.href = response.redirect_url;
            } else if (response.status === 'error') {
              $('#add-candidate-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
            } else {
              $('#add-candidate-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
            $('#add-candidate-form').prepend('<div class="alert alert-danger">An error occurred while processing the request.</div>');
          }
        });
      }
    });

    // Real-time validation when the user enters the required field
    $("#add-candidate-form").find("input, select").on("input", function() {
      const input = $(this);
      const value = input.val().trim();
      const errorMessage = input.data("error-message");

      if (value === "") {
        input.addClass("is-invalid");
        input.siblings(".invalid-feedback").text(errorMessage);
      } else {
        input.removeClass("is-invalid");
        input.siblings(".invalid-feedback").text("");
      }
    });
  });
</script>
