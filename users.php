<?php
include 'config/dbconnection.php';
include 'config/session.php';
include 'config/isadmin.php';

$users = $conn->prepare("SELECT * FROM users");
$users->execute();
$row = $users->get_result();
?>

<div class="pagetitle">
    <h1>Users</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Voters</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section users-list">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0">All Users</h5>
        </div>
        <span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
            <i class="bi bi-arrow-left-right"></i> Scroll Horizontally
        </span>
        <div class="card-body table-responsive">
            <table id="users-table" class="table text-nowrap" style="width:100%">
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
</section>