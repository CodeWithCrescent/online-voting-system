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
            <div class="col-12">
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
    </div>
    <div class="row">
        <?php
        $users = $conn->prepare("SELECT * FROM users");
        $users->execute();
        $row = $users->get_result();
        ?>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title m-0">System Users</h3>
            </div>
            <span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
                <i class="bi bi-arrow-left-right"></i> Scroll Horizontally
            </span>
            <div class="card-body table-responsive">
                <table id="users-table" class="table text-nowrap table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Profile</th>
                            <th scope="col">Name</th>
                            <th scope="col">Role</th>
                            <th scope="col">Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($row as $key => $user) { ?>
                            <tr>
                                <th scope="row" class="text-center">
                                    <a href="#">
                                        <?php if ($user['profile_picture']) { ?>
                                            <img src="assets/img/profile/users/<?php echo $user['profile_picture']; ?>" class="rounded-circle" style="width: 60px;" alt="Profile Picture">
                                            <span class="avatar-badge idle" title="idle"></span>
                                        <?php } else { ?>
                                            <img src="assets/img/user.png" class="rounded-circle" style="width: 60px;" alt="Profile Picture">
                                            <span class="avatar-badge idle" title="idle"></span>
                                        <?php } ?>
                                    </a>
                                </th>
                                <td>
                                    <h6 class="fw-semibold">
                                        <a href="#"><?php echo $user['name']; ?></a> <small class="text-muted">@<?php echo $user['username']; ?></small>
                                    </h6>
                                    <h6 class="card-subtitle text-lowercase fst-italic fw-light"><?php echo $user['email']; ?></h6>
                                </td>
                                <?php
                                if ($user['type'] === 0) {
                                    echo '<td><a href="#" name="type" class="btn badge rounded-pill btn-primary user-type" data-id="' . $user['id'] . '" data-type="1" data-name="Normal user">Admin</a></td>';
                                } else {
                                    echo '<td><a href="#" name="type" class="btn badge rounded-pill btn-secondary user-type" data-id="' . $user['id'] . '" data-type="0" data-name="Admin">User</a></td>';
                                } ?>
                                <td><a href="#" class="btn btn-sm btn-secondary reset" data-id="<?php echo $user['id']; ?>" data-name="Reset">Reset</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>