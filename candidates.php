<?php
include 'config/dbconnection.php';
include 'config/session.php';
include 'config/isadmin.php';

if (isset($_GET['election_id']) && is_numeric($_GET['election_id'])) {
    $election_id = $_GET['election_id'];
} else {
    echo '<script>window.history.back();</script>';
    exit;
}
$election = $conn->prepare("SELECT title AS election_name FROM election WHERE id = ?");
$election->bind_param('i', $election_id);
$election->execute();
$title = $election->get_result()->fetch_assoc();
$election_title = $title['election_name'];

$stmt = $conn->prepare("
    SELECT c.*, cat.name AS category_name
    FROM candidates c
    JOIN categories cat ON c.category_id = cat.id
    WHERE c.election_id = ?
");
$stmt->bind_param('i', $election_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

?>

<div class="pagetitle">
    <h1><?php echo $election_title; ?></h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">All Candidates</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="card info-card candidates-card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0">Candidates</h5>
            <a href="#" class="btn btn-primary btn-sm add-candidate" data-bs-toggle="modal" data-bs-target="#addCandidate" data-id="<?php echo $election_id; ?>">Add New</a>
        </div>
        <span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
            <i class="bi bi-arrows-expand"></i> Scroll Horizontally
        </span>
        <?php
        $categories = []; // Store categories with empty fellow_candidate_name

        foreach ($result as $key => $value) {
            if (!empty($value['fellow_candidate_name'])) {
                if (!isset($categories[$value['category_id']])) {
                    $categories[$value['category_id']] = [
                        'category_name' => $value['category_name'],
                        'candidates' => [],
                    ];
                }
                $categories[$value['category_id']]['candidates'][] = $value;
            }
        }
        ?>

        <?php foreach ($categories as $category_id => $category) { ?>
            <div class="card">
                <div class="card-title text-center"><?php echo $category['category_name']; ?></div>
                <div class="card-body table-responsive">
                    <!-- Pair Candidates List -->
                    <table class="table table-borderless text-nowrap">
                        <tbody>
                            <tr class="d-flex align-items-center">
                                <?php foreach ($category['candidates'] as $value) { ?>
                                    <td class="border-end d-flex justify-content-center px-3">
                                        <div class="row pt-3 pb-2">
                                        <?php if ($value['candidate_photo']) { ?>
                                            <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <img class="card-icon rounded-circle" src="assets/img/profile/candidate/<?php echo $value['candidate_photo']; ?>" alt="Candidate Photo">
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                        <?php } if ($value['fellow_candidate_photo']) { ?>
                                            <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <img class="card-icon rounded-circle" src="assets/img/profile/candidate/<?php echo $value['fellow_candidate_photo']; ?>" alt="Candidate Photo">
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                        <?php } ?>
                                            <span class="small small-text pt-2 text-nowrap text-center fw-bold"><?php echo $value['name']; ?><br>&<br><?php echo $value['fellow_candidate_name']; ?></span>
                                            <div class="btn-group pt-2">
                                                <button type="button" class="btn btn-primary btn-sm edit-candidate" data-bs-toggle="modal" data-bs-target="#editCandidate" data-id="<?php echo $value['id']; ?>" data-name="<?php echo $value['name']; ?>"><i class="bx bx-pencil"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm delete-candidate" data-id="<?php echo $value['id']; ?>" data-name="<?php echo $value['name']; ?>" data-category="<?php echo $value['category_name']; ?>"><i class="bx bx-trash"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End Pair Candidates lists -->
                </div>
            </div>
        <?php } ?>


        <!-- Single Candidates Lists -->
        <?php
        $previous_category = null;

        foreach ($result as $key => $value) {
            if (empty($value['fellow_candidate_name'])) {

                if ($value['category_name'] !== $previous_category) {
                    // Close the previous card body if it exists
                    if ($previous_category !== null) {
                        echo '</tr></tbody></table></div>';
                    }

                    // Display the new category title and open the new card body
                    echo '<div class="card-title text-center">' . $value['category_name'] . '</div>
                        <div class="card-body table-responsive">
                        <table class="table table-borderless text-nowrap">
                        <tbody>
                        <tr class="d-flex align-items-center">';


                    // Update the previous category variable
                    $previous_category = $value['category_name'];
                }
        ?>
                <td class="border-end d-flex justify-content-center px-3">
                    <div class="pt-3 pb-2 row">
                    <?php if ($value['candidate_photo']) { ?>
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <img class="card-icon rounded-circle" src="assets/img/profile/candidate/<?php echo $value['candidate_photo']; ?>" alt="Candidate Photo">
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                    <?php } ?>
                        <span class="small small-text pt-2 text-nowrap text-center fw-bold"><?php echo $value['name']; ?></span>
                        <div class="btn-group pt-2">
                            <button type="button" class="btn btn-primary btn-sm edit-candidate" data-bs-toggle="modal" data-bs-target="#editCandidate" data-id="<?php echo $value['id']; ?>" data-name="<?php echo $value['name']; ?>"><i class="bx bx-pencil"></i></button>
                            <button type="button" class="btn btn-danger btn-sm delete-candidate" data-id="<?php echo $value['id']; ?>" data-name="<?php echo $value['name']; ?>" data-category="<?php echo $value['category_name']; ?>"><i class="bx bx-trash"></i></button>
                        </div>
                    </div>
                </td>
        <?php
            }
        }

        //  Close the last card body if it exists
        if ($previous_category !== null) {
            echo '</tr></tbody></table></div>';
        }
        ?><!-- End of Single Candidates Lists -->

        <!-- Add Candidate Modal -->
        <div class="modal fade" id="addCandidate" tabindex="-1">
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

        <!-- Edit Candidate Modal -->
        <div class="modal fade" id="editCandidate" tabindex="-1">
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
</section>