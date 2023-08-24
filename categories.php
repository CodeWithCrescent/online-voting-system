<?php
include 'config/dbconnection.php';
include 'config/session.php';
include 'config/isadmin.php';

$row = $conn->prepare("SELECT * FROM election ORDER BY created_at DESC");
$row->execute();
$result = $row->get_result();
?>

<div class="pagetitle">
    <h1>Categories Configuration</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Election Categories</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="card">
        <select class="form-select" name="election" id="election">
            <option value="" hidden>Select Election</option>
            <?php
            foreach ($result as $key => $value) {
                echo '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0">Categories</h5>

            <a href="#" type="button" class="btn btn-primary btn-sm add-category disabled" data-bs-toggle="modal" data-bs-target="#addCategory" data-id="<?php echo isset($_GET['election_id']) ? $election_id : ''; ?>">Add New</a>
        </div>
        <span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
            <i class="bi bi-arrows-expand"></i> Scroll Horizontally
        </span>
        <div class="card-body table-responsive">

            <!-- Categories list Table -->
            <table class="table text-nowrap" id="categoriesTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Category</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="election" id="categoriesTableBody">
                    <?php
                    if (isset($_GET['election_id']) && is_numeric($_GET['election_id'])) {
                        $i = 1;
                        $election = $conn->prepare("SELECT * FROM categories WHERE election_id = ? ORDER BY created_at ASC");
                        $election_id = $_GET['election_id'];
                        $election->bind_param('i', $election_id);
                        $election->execute();
                        $result = $election->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                  <th scope="row">' . $i++ . '</th>
                                  <td>' . $row['name'] . '</td>
                                  <td class="text-center">
                                      <a href="#" class="btn btn-primary btn-sm edit-category" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '">
                                          <i class="bi bi-pencil"></i> Edit
                                      </a>
                                      <a href="#" class="btn btn-danger btn-sm category-delete" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '">
                                          <i class="bi bi-trash"></i> Delete
                                      </a>
                                  </td>
                              </tr>';
                        }
                    } else {
                        echo '<tr>
                              <td colspan="3" class="text-center text-muted py-5 border-bottom-0">Select an Election to add and view categories.</td>
                          </tr>';
                    }
                    ?>
                </tbody>
            </table><!-- End Categories lists Table -->


            <!-- Add Category Modal -->
            <div class="modal fade" id="addCategory" tabindex="-1">
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
            <!-- End Add Category Modal Dialog -->

            <!-- The Modal -->
            <div class="modal fade" id="editCategory" tabindex="-1">
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
            <!-- End Edit Category Modal Dialog -->

        </div>
    </div>
</section>