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
  $voting_status = $show_results['can_vote'];

  // Check if user has already voted
  $user_id = $_SESSION['login_id'];
  $sql = "SELECT * FROM votes WHERE voter_id = '$user_id' AND election_id = '$check_election'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<script> location.href = 'index.php?page=vote_details' </script>";
    exit();
  }

  $stmt = $conn->prepare("
    SELECT c.*, cat.name AS category_name, e.title AS election_name, e.id AS election_id, e.endtime AS endtime, e.can_vote
    FROM candidates c
    JOIN categories cat ON c.category_id = cat.id
    JOIN election e ON c.election_id = e.id
    WHERE e.status = ? AND e.can_vote = ?
");
  $stmt->bind_param('ii', $status, $status);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($row) {
    $election_title = $row['election_name'];
    $election_id = $row['election_id'];
    $endtime = $row['endtime'];
?>

    <div class="pagetitle">
      <h1><?php echo $election_title; ?></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Vote</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard row">
      <!-- Customers Card -->
      <form id="vote-form" class="col-xxl-4 col-xl-12">

        <!-- .Remaining-time-message -->
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="bi bi-info-circle me-1"></i> Time Remaining: <span class="text-muted" id="countdown"></span>
          <!-- <div class="progress mt-3">
            <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
          </div> -->
          <button type="button" class="btn-close" aria-label="Close" onclick="$(this).parent().fadeOut()"></button>
        </div><!-- /.Remaining-time-message -->

        <input type="hidden" name="election_id" value="<?php echo $election_id; ?>">
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
              <h5 class="card-title"><?php echo $categoryName; ?> <span>| Choose one</span></h5>
              <?php
              foreach ($categoryCandidates as $candidate) {
              ?>
                <div class="d-flex align-items-center row pt-3 pb-2"> <!-- test-x -->
                  <?php if ($candidate['candidate_photo']) { ?>
                    <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <img class="card-icon rounded-circle" src="assets/img/profile/<?php echo $candidate['candidate_photo']; ?>" alt="Candidate Photo">
                      </div>
                      <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold"><?php echo $candidate['name']; ?></span>
                    </div>
                  <?php } else { ?>
                    <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-person"></i>
                      </div>
                      <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold"><?php echo $candidate['name']; ?></span>
                    </div>
                  <?php }
                  if ($candidate['fellow_candidate_photo']) { ?>
                    <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <img class="card-icon rounded-circle" src="assets/img/profile/<?php echo $candidate['fellow_candidate_photo']; ?>" alt="Candidate Photo">
                      </div>
                      <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold"><?php echo $candidate['fellow_candidate_name']; ?></span>
                    </div>
                  <?php } else { ?>
                    <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-person"></i>
                      </div>
                      <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold"><?php echo $candidate['fellow_candidate_name']; ?></span>
                    </div>
                  <?php } ?>
                  <div class="ps-3 col-4 d-flex flex-column align-items-center justify-content-center">
                    <label class="vote-check-label card-icon rounded-circle"><input type="radio" class="vote-radio" name="vote-<?php echo strtolower(str_replace(' ', '-', $categoryName)); ?>" data-id="<?php echo $candidate['id']; ?>" data-category="<?php echo $candidate['category_id']; ?>" data-election="<?php echo $election_id; ?>">
                      <div class="checkmark"></div>
                    </label>
                    <span class="d-flex small small-text pt-2 text-nowrap text-sm-start text-md-center fw-bold">Vote</span>
                  </div>
                </div>
              <?php
                if (next($categoryCandidates)) {
                  echo '<hr>';
                }
              }
              ?>
            </div>
          </div>
        <?php
        }
        ?>

        <!-- One vote for One Candidate -->
        <?php
        $currentCategory = null;
        $candidateCount = 0;

        foreach ($result as $out) {
          if (empty($out['fellow_candidate_name'])) {
            if ($currentCategory !== $out['category_name']) {
              if (!is_null($currentCategory)) {
                echo '</div></div></div>';
              }
              $currentCategory = $out['category_name'];
              $candidateCount = 0;
        ?>
              <div class="card info-card candidates-card">
                <div class="card-body">
                  <h5 class="card-title"><?php echo $out['category_name']; ?> <span>| Choose One</span></h5>
                  <div class="d-flex align-items-center row">
                  <?php
                }

                if ($candidateCount > 0 && $candidateCount % 2 === 0) {
                  echo '</div><hr><div class="d-flex align-items-center row">';
                }
                $candidateCount++;
                  ?>
                  <div class="col-6 border-end d-flex justify-content-center" style="height: 100px;">
                    <div class="row pt-3 pb-2">
                      <?php if ($out['candidate_photo']) { ?>
                        <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <img class="card-icon rounded-circle" src="assets/img/profile/<?php echo $out['candidate_photo']; ?>" alt="Candidate Photo">
                          </div>
                        </div>
                      <?php } else { ?>
                        <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person"></i>
                          </div>
                        </div>
                      <?php } ?>
                      <div class="ps-3 col-6 d-flex flex-column align-items-center justify-content-center">
                        <label class="vote-check-label card-icon rounded-circle"><input type="radio" class="vote-radio" name="vote-<?php echo strtolower(str_replace(' ', '-', $out['category_name'])); ?>" data-id="<?php echo $out['id']; ?>" data-category="<?php echo $out['category_id']; ?>" data-election="<?php echo $election_id; ?>">
                          <div class="checkmark"></div>
                        </label>
                      </div>
                      <span class="small small-text pt-2 text-nowrap text-center fw-bold"><?php echo $out['name']; ?></span>
                    </div>
                  </div>
              <?php
            }
          }
          if (!is_null($currentCategory)) {
            echo '</div></div></div>';
          }
              ?>
              <!--./ End of One vote for One Candidate -->

              <div class="card candidates-card">
                <div class="card-body">
                  <div class=" d-flex justify-content-between pt-3">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
              </div>

      </form><!-- End Contestants Card -->
    </section>
    <?php } else {
    if ($voting_status == 0) { ?>
      <div class="pagetitle">
        <h1>Online Voting System</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Vote</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <!-- .Voting time ended -->
      <section class="section error-404 d-flex flex-column align-items-center justify-content-center">
        <img src="assets/img/img-2.svg" class="img-fluid py-5" alt="No active election!" style="max-width: 320px">
        <h2>Sorry, Voting time has ended.</h2>
        <a class="btn" href="controllers/app.php?action=results">View Results</a>
      </section>

      </div><!-- /.Voting time ended -->
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

      <!-- .No Candidates added -->
      <section class="section error-404 d-flex flex-column align-items-center justify-content-center">
        <img src="assets/img/img-2.svg" class="img-fluid py-5" alt="No active election!" style="max-width: 320px">
        <h2>No candidates added, Try to come back later.</h2>
        <a class="btn" href="controllers/app.php?action=logout">Logout</a>
      </section>

      </div><!-- /.No Candidates added -->
  <?php }
  }
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
      <h2>No active election now, See you next time.</h2>
      <a class="btn" href="controllers/app.php?action=logout">Logout</a>
    </section>

  </div><!-- /.No active election section -->
<?php } ?>