<?php
include 'config/session.php';
?>
<div class="pagetitle">
    <h1>Get in touch</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Contact Us</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">

    <div class="card">
        <div class="card-body row pt-5">
            <div class="col-md-5 text-center d-flex align-items-center justify-content-center">
                <div class="contact-title">
                    <div class="d-none d-md-block contact-img">
                        <img src="assets/img/logo.jpg" alt="">
                    </div>
                    <h1>Online Voting System</h1>
                    <p class="lead mb-5">Mbeya University of Science and Technology<br>
                        Mbeya
                    </p>
                </div>
            </div>
            <div class="col-md-7">
                <form class="row g-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" />
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">E-Mail</label>
                        <input type="email" name="email" id="email" class="form-control" />
                    </div>
                    <div class="col-12">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" />
                    </div>
                    <div class="col-12">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" name="message" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="btn btn-primary" value="Send message">
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>