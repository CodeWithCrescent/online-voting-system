<?php
include 'config/dbconnection.php';
include 'config/session.php';

$status = 1;
$query = $conn->prepare("SELECT * FROM election WHERE status = ?");
$query->bind_param('i', $status);
$exec = $query->execute();
$temp = $query->get_result();
$show_results = $temp->fetch_assoc();

if ($show_results) {
    $check_election = $show_results['id'];

    // Check if user has already voted
    $user_id = $_SESSION['login_id'];
    $sqlz = "SELECT * FROM votes WHERE voter_id = '$user_id' AND election_id = '$check_election'";
    $resultz = mysqli_query($conn, $sqlz);

    if (mysqli_num_rows($resultz) == 0) {
        echo "<script> location.href = 'index.php?page=vote' </script>";
        exit();
    }

    $stmt = $conn->prepare("
    SELECT c.*, cat.name AS category_name, e.title AS election_name, e.id AS election_id, e.created_at AS end_time, v.voter_id AS voter_id
    FROM candidates c
    JOIN categories cat ON c.category_id = cat.id
    JOIN election e ON c.election_id = e.id
    LEFT JOIN votes v ON e.id = v.election_id AND c.category_id = v.category_id AND c.id = v.candidate_id
    WHERE e.status = ? AND voter_id = ?
");
    $stmt->bind_param('ii', $status, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $election_title = $row['election_name'];
        $election_id = $row['election_id'];
        $end_time = $row['end_time'];

?>

        <div class="pagetitle">
            <h1><?php echo $election_title; ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Vote Details</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard row">
            <h1 class="vote-details-title">You Aready Voted For </h1>

            <div class="col-xxl-4 col-xl-12">

                <!-- One Vote For Two Candidates -->
                <div class="d-flex align-items-center row">
                    <?php
                    $categories = array();
                    foreach ($result as $key => $value) {
                        if (!empty($value['fellow_candidate_name'])) {
                            $categories[$value['category_name']][] = $value;
                        }
                    }

                    foreach ($categories as $categoryName => $categoryCandidates) {
                    ?>
                        <div class="">
                            <div class="card info-card candidates-card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $categoryName; ?></h5>
                                    <!-- List of candidates -->
                                    <?php
                                    foreach ($categoryCandidates as $key => $candidate) {
                                    ?>
                                        <div class="d-flex align-items-center row pt-3 pb-2"> <!-- test-x -->
                                            <?php if ($candidate['candidate_photo']) { ?>
                                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <img class="card-icon rounded-circle" src="assets/img/profile/candidate/<?php echo $candidate['candidate_photo']; ?>" alt="Candidate Photo">
                                                    </div>
                                                    <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold"><?php echo $candidate['name']; ?></span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                    <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold"><?php echo $candidate['name']; ?></span>
                                                </div>
                                            <?php }
                                            if ($candidate['fellow_candidate_photo']) { ?>
                                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <img class="card-icon rounded-circle" src="assets/img/profile/candidate/<?php echo $candidate['fellow_candidate_photo']; ?>" alt="Candidate Photo">
                                                    </div>
                                                    <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold"><?php echo $candidate['fellow_candidate_name']; ?></span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                    <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold"><?php echo $candidate['fellow_candidate_name']; ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <!-- End List of candidates -->
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <!-- One Vote For One Candidates -->

                <div class="row">
                    <?php
                    $categories = array();
                    foreach ($result as $key => $value) {
                        if (empty($value['fellow_candidate_name'])) {
                            $categories[$value['category_name']][] = $value;
                        }
                    } ?>

                    <?php
                    foreach ($categories as $categoryName => $categoryCandidates) {
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card info-card candidates-card px-4">
                                <h5 class="card-title"><?php echo $categoryName; ?></h5>
                                <div class="card-body row">
                                    <!-- List of candidates -->
                                    <?php
                                    foreach ($categoryCandidates as $key => $out) {

                                        if ($out['candidate_photo']) { ?>
                                            <div class="col-6 card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <img class="card-icon rounded-circle" src="assets/img/profile/candidate/<?php echo $out['candidate_photo']; ?>" alt="Candidate Photo">
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-6 card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        <?php } ?>

                                        <div class="col-6 ms-2 me-auto">
                                            <div class="pt-2 text-nowrap text-center fw-bold"><?php echo $out['name']; ?></div>
                                        </div>

                                    <?php
                                    }
                                    ?>
                                    <!-- End List of candidates -->
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <div class="d-flex justify-content-center">
                    <a href="index.php?page=results" class="btn btn-primary">View Results</a>
                </div>
            </div>
        </section>
    <?php }
} else { ?>
    <div class="container">
        <div class="pagetitle">
            <h1>Online Voting System</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Vote Details</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <!-- .No active election section -->
        <section class="section error-404 d-flex flex-column align-items-center justify-content-center">
            <img src="assets/img/img-2.svg" class="img-fluid py-5" alt="No active election!" style="max-width: 320px">
            <h2>No active election now, See you next time.</h2>
            <a class="btn" href="controllers/app.php?action=logout">Logout</a>
        </section>

    </div><!-- /.No active election section -->
<?php } ?>