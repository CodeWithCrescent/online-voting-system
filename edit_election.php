<?php
session_start();
include 'config/dbconnection.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $result = $conn->query("SELECT * FROM election WHERE id = $id");

  if ($result) {
    $row = $result->fetch_assoc();
    if ($row) { ?>
      <form id="update-election-form">
        <div class="row g-3">
          <div class="col-md-12">
            <label for="title" class="form-label">Title</label>
            <input type="hidden" name="election_id" value="<?php echo $row['id']; ?>">
            <input type="text" name="title" class="form-control" value="<?php echo $row['title']; ?>" id="title" data-error-message="Title of election is required">
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-6">
            <label for="year" class="form-label">Year</label>
            <input type="text" class="form-control" name="year" value="<?php echo $row['year']; ?>" id="year" data-error-message="Please enter year of election">
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-6">
            <label for="voters" class="form-label">Voters</label>
            <select id="voters" name="voters" class="form-select" value="<?php echo $row['voters']; ?>" data-error-message="Please choose voters">
              <option value="" selected hidden>Choose...</option>
              <option>Students</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-6">
            <label for="starttime" class="form-label">Start Date</label>
            <input type="text" name="starttime" id="starttime" value="<?php echo $row['starttime']; ?>" class="form-control" placeholder="Start at" data-error-message="Start date is required">
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-6">
            <label for="endtime" class="form-label">End Date</label>
            <input type="text" name="endtime" class="form-control" value="<?php echo $row['endtime']; ?>" id="endtime" placeholder="End at" data-error-message="End date is required">
            <div class="invalid-feedback"></div>
          </div>
          <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" value="<?php echo $row['description']; ?>" id="description" rows="5" data-error-message="Please enter short description of this election"></textarea>
            <div class="invalid-feedback"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary">Reset</button>
          <button type="submit" name="submit" id="submit" class="btn btn-primary">Update Election</button>
        </div>
      </form>
<?php    }
  } else {
    echo "Error executing the query.";
  }

  $conn->close();
} else {

  echo "500 Error! No id found in URL.";
}
?>