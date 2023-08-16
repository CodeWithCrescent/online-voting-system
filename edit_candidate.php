<?php
include 'config/dbconnection.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $stmt = $conn->prepare("SELECT c.*, cat.name AS cat_name, cat.id AS cat_id FROM candidates c JOIN categories cat ON c.category_id = cat.id WHERE c.id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $election_id = $row['election_id'];
?>

    <form id="update-candidate-form">
      <div class="row g-3">
        <div class="col-md-12">
          <label for="category" class="form-label">Category<span class="text-danger"> *</span></label>
          <input type="hidden" name="candidate_id" value="<?php echo $id; ?>">
          <select class="form-select" name="category" id="category" data-error-message="Select Category">
            <option value="<?php echo $row['cat_id']; ?>" hidden selected><?php echo $row['cat_name']; ?></option>
          </select>

          <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-12">
          <label for="candidate" class="form-label">Candidate Name<span class="text-danger"> *</span></label>
          <input type="text" name="candidate" value="<?php echo $row['name']; ?>" class="form-control" id="candidate" data-error-message="Candidate name is required" placeholder="Enter Candidate name">
          <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-6">
          <label for="candidate_photo" class="form-label">Candidate Photo</label>
          <input disabled type="file" class="form-control" id="candidate_photo" name="candidate_photo" data-error-message="Please upload candidate's photo">
          <?php
          if ($row['candidate_photo']) {
            $candidate_photo = $row['candidate_photo'];
            $candidate_photo_path = 'assets/img/profile/candidate/' . $candidate_photo;

            if (file_exists($candidate_photo_path)) {
              echo '<img src="' . $candidate_photo_path . '" alt="Candidate Photo" class="mt-2" style="max-width: 100px;">';
            }
          }
          ?>
          <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
          <label for="candidate_year" class="form-label">Year of Study<span class="text-danger"> *</span></label>
          <select class="form-select" value="<?php echo $row['candidate_year']; ?>" name="candidate_year" id="candidate_year" data-error-message="Select year of study">
            <option value="" hidden>Select Year</option>
            <option value="1">First Year</option>
            <option value="2">Second Year</option>
            <option value="3">Third Year</option>
            <option value="4">Fourth Year</option>
          </select>
          <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-12">
          <label for="fellow_candidate" class="form-label">Fellow Candidate Name</label>
          <input type="text" value="<?php echo $row['fellow_candidate_name']; ?>" name="fellow_candidate" class="form-control" id="fellow_candidate" placeholder="If not, Leave empty">
          <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-6">
          <label for="fellow_candidate_photo" class="form-label">Fellow Candidate Photo</label>
          <input disabled class="form-control" type="file" id="fellow_candidate_photo" name="fellow_candidate_photo" data-error-message="Please upload candidate's photo" placeholder="">
          <?php
          if ($row['fellow_candidate_photo']) {
            $fellow_candidate_photo = $row['fellow_candidate_photo'];
            $fellow_candidate_photo_path = 'assets/img/profile/candidate/' . $fellow_candidate_photo;

            if (file_exists($fellow_candidate_photo_path)) {
              echo '<img src="' . $fellow_candidate_photo_path . '" alt="Fellow Candidate Photo" class="mt-2" style="max-width: 100px;">';
            }
          }
          ?>
          <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-6">
          <label for="fellow_candidate_year" class="form-label">Year of Study</label>
          <select class="form-select" value="<?php echo $row['fellow_candidate_year']; ?>" name="fellow_candidate_year" id="fellow_candidate_year">
            <option value="" hidden>Select Year</option>
            <option value="1">First Year</option>
            <option value="2">Second Year</option>
            <option value="3">Third Year</option>
            <option value="4">Fourth Year</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn btn-secondary">Reset</button>
        <button type="submit" class="btn btn-info">Update Candidate</button>
      </div>
    </form>
<?php
  } else {
    echo 'Error executing the query.';
  }
  $conn->close();
} else {

  echo "500 Error! No id found in URL.";
} ?>

<!-- Add Candidate JQuery -->
<script>
  $('#add-candidate-form').submit(function(e) {
    e.preventDefault()

    let isValid = true;
    const SpecifiedInputs = ['category', 'candidate', 'candidate_year'];
    $("#add-candidate-form").find("input, select").each(function() {
      const input = $(this);
      const value = input.val().trim();
      const inputName = input.attr("name");
      const errorMessage = input.data("error-message");
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

      var formData = $(this).serialize();
      $.ajax({
        url: 'controllers/app.php?action=add_candidate',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            location.href = response.redirect_url;
          } else if (response.status === 'errors') {
            $('#add-candidate-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');

          } else {
            $('#add-candidate-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
          }
        }
      })
    }
  })
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
</script>