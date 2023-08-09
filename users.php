<?php
include 'config/dbconnection.php';

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
            <i class="bi bi-arrows-expand"></i> Scroll Horizontally
        </span>
        <div class="card-body table-responsive">
            <table id="users-table" class="table table-striped text-nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row as $key => $user) { ?>
                        <tr class="align-items-center">
                            <td>
                                <a href="#" class="logo">
                                    <img src="assets/img/logo.jpg" style="width: 40px;" alt="">
                                    <span class="avatar-badge idle" title="idle"></span>
                                </a>
                            </td>
                            <td>
                                <h6 class="">
                                    <a href="#"><?php echo $user['name']; ?></a> <small class="text-muted">@<?php echo $user['username']; ?></small>
                                </h6>
                                <h6 class="card-subtitle text-muted">Social Worker</h6>
                            </td>
                            <?php
                            echo '<td><a href="#" name="status" class="btn badge rounded-pill ' . ($user['type'] === 0 ? 'btn-primary' : 'btn-secondary') . ' user-type" data-id="' . $user['id'] . '" data-type="' . ($user['type'] === 0 ? 1 : 0) . '" data-name="' . ($user['type'] === 0 ? 'Admin' : 'Normal User') . '">' . ($user['type'] === 0 ? 'Admin' : 'User') . '</a></td>';
                            ?>
                            <td><a href="#" class="btn btn-sm btn-secondary reset" data-id="1" data-name="Reset" data-type="1">Reset</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>