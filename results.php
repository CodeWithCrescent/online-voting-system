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

    $stmt = $conn->prepare("
    SELECT c.*, cat.name AS category_name, e.title AS election_name, e.id AS election_id, e.created_at AS end_time, COUNT(v.id) AS votes_count
    FROM candidates c
    JOIN categories cat ON c.category_id = cat.id
    JOIN election e ON c.election_id = e.id
    LEFT JOIN votes v ON e.id = v.election_id AND c.category_id = v.category_id AND c.id = v.candidate_id
    WHERE e.status = ?
    GROUP BY c.id
    ORDER BY votes_count DESC
");
    $stmt->bind_param('i', $status);
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
                    <li class="breadcrumb-item active">Results</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard row">
            <!-- Vote Form -->
            <form id="vote-form" class="col-xxl-4 col-xl-12">

                <!-- .Remaining-time-message -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-1"></i> Time Remaining: <span class="text-muted" id="countdown"></span>
                    <button type="button" class="btn-close" aria-label="Close" onclick="$(this).parent().fadeOut()"></button>
                </div><!-- /.Remaining-time-message -->

                <input type="hidden" name="election_id" value="<?php echo $election_id; ?>">
                <!-- One Vote For Two Candidates -->
                <?php
                $categories = array();
                foreach ($result as $key => $value) {
                    if (!empty($value['fellow_candidate_name'])) {
                        $categories[$value['category_name']][] = $value;
                    }
                }

                foreach ($categories as $categoryName => $categoryCandidates) {
                ?>
                    <div class="card info-card candidates-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $categoryName; ?> <span class="float-end">Vote Counts</span></h5>
                            <!-- List of candidates -->
                            <ol class="list-group list-group-numbered">
                                <?php
                                foreach ($categoryCandidates as $key => $candidate) {
                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold"><?php echo $candidate['name']; ?></div>
                                            <div class="fw-light"><?php echo $candidate['fellow_candidate_name']; ?></div>
                                        </div>
                                        <span class="badge bg-primary rounded-pill"><?php echo $candidate['votes_count']; ?></span>
                                    </li>
                                <?php
                                }
                                ?>
                            </ol><!-- End List of candidates -->
                        </div>
                    </div>
                <?php
                }
                ?>

                <!-- One Vote For One Candidates -->
                <?php
                $categories = array();
                foreach ($result as $key => $value) {
                    if (empty($value['fellow_candidate_name'])) {
                        $categories[$value['category_name']][] = $value;
                    }
                }

                foreach ($categories as $categoryName => $categoryCandidates) {
                ?>
                    <div class="card info-card candidates-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $categoryName; ?> <span class="float-end">Vote Counts</span></h5>
                            <!-- List of candidates -->
                            <ol class="list-group list-group-numbered">
                                <?php
                                foreach ($categoryCandidates as $key => $out) {
                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-semibold"><?php echo $out['name']; ?></div>
                                        </div>
                                        <span class="badge bg-primary rounded-pill"><?php echo $out['votes_count']; ?></span>
                                    </li>
                                <?php
                                }
                                ?>
                            </ol><!-- End List of candidates -->
                        </div>
                    </div>
                <?php
                }
                ?>

            </form><!-- End Vote Form -->
        </section>
    <?php } else { ?>
        <div class="pagetitle">
            <h1>Online Voting System</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Vote</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <!-- .No active election section -->
        <section class="section error-404 d-flex flex-column align-items-center justify-content-center">
            <img src="assets/img/img-2.svg" class="img-fluid py-5" alt="No active election!" style="max-width: 320px">
            <h2>Nothing to view now, Try to come back later.</h2>
            <a class="btn" href="controllers/app.php?action=logout">Logout</a>
        </section>
    <?php }
} else { ?>
    <div class="container">
        <div class="pagetitle">
            <h1>Online Voting System</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Vote</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <!-- .No active election section -->
        <section class="section error-404 d-flex flex-column align-items-center justify-content-center">
            <img src="assets/img/img-2.svg" class="img-fluid py-5" alt="No active election!" style="max-width: 320px">
            <h2>Nothing to view now, See you next time.</h2>
            <a class="btn" href="controllers/app.php?action=logout">Logout</a>
        </section>

    </div><!-- /.No active election section -->
<?php } ?>