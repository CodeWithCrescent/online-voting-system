<?php
include 'config/session.php';
include 'config/isadmin.php';

$query = "
    SELECT
        (SELECT COUNT(*) FROM election) AS total_elections,
        IFNULL((SELECT COUNT(*) FROM categories c WHERE c.election_id = e.id AND e.status = 1), 0) AS total_active_categories,
        (SELECT COUNT(*) FROM users WHERE type = 1) AS total_voters,
        COUNT(DISTINCT v.voter_id) AS total_voted,
        e.title AS election_title
    FROM
        election e
    LEFT JOIN
        users u ON u.type = 1
    LEFT JOIN
        votes v ON e.id = v.election_id
    WHERE
        e.status = 1;
";


$result = $conn->query($query);
?>
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="row">
        <?php if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); ?>
            <div class="col-lg-8">
                <div class="row">
                    <!-- Elections Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Elections <span>| All</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box-seam-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $row["total_elections"]; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">Total Elections</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Elections Card -->

                    <!-- Categories Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Categories <span>| On Active Election</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-bookmark"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $row["total_active_categories"]; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">Total Categories</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->
                </div>
                <div class="row">
                    <!-- Voters Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Voters <span>| Total</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $row["total_voters"]; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">All System Voters</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Voters Card -->

                    <!-- Voted Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Voted <span>| On Active Election</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $row["total_voted"]; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">Total Voted</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Voted Card -->
                </div>
            </div>
        <?php } ?>
        <div class="col-lg-4">
            <!-- Recent Activity -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>

                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Recent Activity <span>| Today</span></h5>

                    <div class="activity">

                        <div class="activity-item d-flex">
                            <div class="activite-label">32 min</div>
                            <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                            <div class="activity-content">
                                Crescent: Verified and uploaded the list of eligible <a href="#" class="fw-bold text-dark">candidates</a> for the election
                            </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                            <div class="activite-label">56 min</div>
                            <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                            <div class="activity-content">
                                Maziba: Approved voter registration for Justo Kimei.
                            </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                            <div class="activite-label">2 hrs</div>
                            <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                            <div class="activity-content">
                                Crescent: Sent reminder emails to registered voters about upcoming elections.
                            </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                            <div class="activite-label">1 day</div>
                            <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                            <div class="activity-content">
                                Crescent: Added new <a href="#" class="fw-bold text-dark">category</a> for <a href="#" class="fw-bold text-dark">MUSTSO 2023</a> election
                            </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                            <div class="activite-label">2 days</div>
                            <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                            <div class="activity-content">
                                Anna: Deleted Presidential candidate named Jakaya M. Kikwete
                            </div>
                        </div><!-- End activity item-->

                        <div class="activity-item d-flex">
                            <div class="activite-label">4 weeks</div>
                            <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                            <div class="activity-content">
                                Efei: Added Presidential candidate named Jakaya M. Kikwete
                            </div>
                        </div><!-- End activity item-->

                    </div>
                </div>
            </div><!-- End Recent Activity -->
        </div>
    </div>
</section>