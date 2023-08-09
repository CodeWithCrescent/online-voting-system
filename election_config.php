    <div class="pagetitle">
      <h1>Election Configuration</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Election Lists</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0">Election Lists</h5>
          <a href="#" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addElection">Click to Add</a>
        </div>
        <span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
          <i class="bi bi-arrows-expand"></i> Scroll Horizontally
        </span>
        <div class="card-body table-responsive">

          <!-- Table with hoverable rows -->
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Year</th>
                <th scope="col">Status</th>
                <th class="text-center" scope="col">Action</th>
              </tr>
            </thead>
            <tbody class="election">
              <?php
              $i = 1;
              $election = $conn->prepare("SELECT * FROM election ORDER BY id DESC");
              $election->execute();
              $result = $election->get_result();
              while ($row = $result->fetch_assoc()) {

                echo '<tr>
                    <th scope="row">' . $i++ . '</th>
                    <td><a href="index.php?page=candidates&election_id=' . $row['id'] . '">' . $row['title'] . '</a></td>
                    <td>' . $row['year'];
                '</td>';
                if ($row['status'] === 0) {
                  echo '<td><a href="#" name="status" class="btn badge rounded-pill btn-secondary election-status" data-id="' . $row['id'] . '" data-status="1" data-name="Activate">Not active</a></td>';
                } else {
                  echo '<td><a href="#" name="status" class="btn badge rounded-pill btn-success election-status" data-id="' . $row['id'] . '" data-status="0" data-name="Deactivate">Active</a></td>';
                }
                echo '<td class="text-center"><a href="#" class="btn btn-secondary btn-sm open-modal" data-bs-toggle="modal" data-bs-target="#viewElection" data-id="' . $row['id'] . '">View</a>
                        <a href="#" class="btn btn-primary btn-sm edit-election-modal" data-bs-toggle="modal" data-bs-target="#editElection" data-id="' . $row['id'] . '">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm delete-election" data-id="' . $row['id'] . '" data-name="' . $row['title'] . '">Delete</a></td>
                  </tr>';
              } ?>
            </tbody>
          </table><!-- End Election lists Table -->

          <!-- Add Election Modal -->
          <div class="modal fade" id="addElection" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
              <form id="add-election-form" class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Add Election</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label for="title" class="form-label">Title</label>
                      <input type="text" name="title" class="form-control" id="title" data-error-message="Title of election is required">
                      <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-6">
                      <label for="year" class="form-label">Year</label>
                      <input type="text" class="form-control" name="year" id="year" data-error-message="Please enter year of election">
                      <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-6">
                      <label for="voters" class="form-label">Voters</label>
                      <select id="voters" name="voters" class="form-select" data-error-message="Please choose voters">
                        <option value="" selected hidden>Choose...</option>
                        <option>Students</option>
                      </select>
                      <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-6">
                      <label for="starttime" class="form-label">Start Date</label>
                      <input type="text" name="starttime" id="starttime" class="form-control" placeholder="Start at" data-error-message="Start date is required">
                      <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-6">
                      <label for="endtime" class="form-label">End Date</label>
                      <input type="text" name="endtime" class="form-control" id="endtime" placeholder="End at" data-error-message="End date is required">
                      <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-12">
                      <label for="description" class="form-label">Description</label>
                      <textarea name="description" class="form-control" id="description" rows="5" data-error-message="Please enter short description of this election"></textarea>
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="reset" class="btn btn-secondary">Reset</button>
                  <button type="submit" name="submit" id="submit" class="btn btn-primary">Add Election</button>
                </div>
              </form>
            </div>
          </div><!-- End Add Election Modal Dialog -->

          <!-- The Edit Election Modal -->
          <div class="modal fade" id="editElection" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
              </div>
            </div>
          </div>
          <!-- End Edit Election Modal Dialog -->

          <!-- The Modal -->
          <div class="modal fade" id="viewElection" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
              </div>
            </div>
          </div>
          <!-- End View Election Modal Dialog -->

        </div>
      </div>
    </section>