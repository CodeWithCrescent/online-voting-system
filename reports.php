<?php
include 'config/session.php';
include 'config/isadmin.php';
?>
<div class="pagetitle">
    <h1>Election Reports</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Report Lists</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0">Report Lists</h5>
        </div>
        <span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
            <i class="bi bi-arrow-left-right"></i> Scroll Horizontally
        </span>
        <div class="card-body table-responsive">

            <!-- Table with hoverable rows -->
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Election Title</th>
                        <th scope="col">Year</th>
                        <th scope="col">Generated By</th>
                        <th scope="col">Generated On</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="election">
                    <?php
                    $i = 1;
                    // $election = $conn->prepare("SELECT e.name, u.* FROM election e RIGHT JOIN users u ON election.added_by = users.id WHERE report_path != '' ORDER BY id DESC");
                    $election = $conn->prepare("
                    SELECT e.*, u.id AS user_id, u.name AS admin_name
                    FROM election e
                    LEFT JOIN users u ON e.added_by = u.id
                    WHERE report_path != ''
                    ORDER BY e.id DESC
                    ");
                    $election->execute();
                    $result = $election->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $filesize = filesize('assets/reports/'.$row['report_path']);
                        $formatByte = formatBytes($filesize);

                        function formatBytes($bytes, $precision = 2) {
                            $units = array("B", "KB", "MB", "GB", "TB");
                            $bytes = max($bytes, 0);
                            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                            $pow = min($pow, count($units) - 1);
                            $bytes /= (1 << (10 * $pow));
                        
                            return round($bytes, $precision) . " " . $units[$pow];
                        }

                        echo
                        '<tr>
                        <th scope="row">' . $i++ . '</th>
                        <td>' . $row['title'] . ' REPORT</td>
                        <td>' . $row['year'] . '</td>
                        <td>' . $row['admin_name'] . '</td>
                        <td>' . $row['created_at'] . '</td>
                        <td class="text-center" ><a href="#" class="btn btn-primary btn-sm" data-id="' . $row['id'] . '"><i class="bi bi-download d-md-none"></i> <span class="d-none d-md-inline">Download</span> ['.$formatByte.']</a>
                        <a href="#" class="btn btn-danger btn-sm delete-report" data-id="' . $row['id'] . '" data-name="' . $row['title'] . '"><i class="bi bi-trash"></i> Delete</a></td>
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