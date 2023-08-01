<?php
if (isset($_GET['election_id']) && is_numeric($_GET['election_id'])) {
    $election_id = $_GET['election_id'];
} else {
    echo '<script>window.history.back();</script>'; 
    exit;
}

?>

<div class="pagetitle">
      <h1>MUSTSO 2023 Election</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Election Candidates</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card info-card contenstant-card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0">Candidates</h5>
            <!-- <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Add New
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="#" data-modal="#addPresident">President & Vice</a></li>
                    <li><a class="dropdown-item" href="#" data-modal="#addCandidate">Others</a></li>
                </ul>
            </div> -->
              <a href="#" class="btn btn-primary btn-sm add-candidate" data-bs-toggle="modal" data-bs-target="#addCandidate" data-id="<?php echo $election_id; ?>">Add New</a>
          </div>
          <span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
              <i class="bi bi-arrows-expand"></i> Scroll Horizontally
          </span>
            <div class="card-title text-center">President & Vice President</div>
            <div class="card-body table-responsive">
             <!-- Candidates list Table -->
             <table class="table table-borderless text-nowrap">
                <tbody>
                    <tr class="d-flex align-items-center">
                        <td class="border-end d-flex justify-content-center px-3">
                            <div class="row pt-3 pb-2">
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <span class="small small-text pt-2 text-nowrap text-center fw-bold">Domminic E. Mbise<br>&<br>Mariam K. Lazaro</span>
                            </div>
                        </td>
                        <td class="border-end d-flex justify-content-center px-3">
                            <div class="row pt-3 pb-2">
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <span class="small small-text pt-2 text-nowrap text-center fw-bold">Domminic E. Mbise<br>&<br>Mariam K. Lazaro</span>
                            </div>
                        </td>
                        <td class="border-end d-flex justify-content-center px-3">
                            <div class="row pt-3 pb-2">
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <span class="small small-text pt-2 text-nowrap text-center fw-bold">Domminic E. Mbise<br>&<br>Mariam K. Lazaro</span>
                            </div>
                        </td>
                        <td class="d-flex justify-content-center px-3">
                            <div class="row pt-3 pb-2">
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <span class="small small-text pt-2 text-nowrap text-center fw-bold">Domminic E. Mbise<br>&<br>Mariam K. Lazaro</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
              </table>
              <!-- End Candidates lists Table -->
            </div>

            <div class="card-title text-center">6A Mbunge</div>
            <div class="card-body table-responsive">
             <!-- Candidates list Table -->
             <table class="table table-borderless text-nowrap">
                <tbody>
                    <tr class="d-flex align-items-center">
                        <td class="border-end d-flex justify-content-center px-3">
                            <div class="pt-3 pb-2">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <span class="small small-text pt-2 text-nowrap text-center fw-bold">Domminic E. Mbise</span>
                            </div>
                        </td>
                        <td class="border-end d-flex justify-content-center px-3">
                            <div class="pt-3 pb-2">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <span class="small small-text pt-2 text-nowrap text-center fw-bold">Domminic E. Mbise</span>
                            </div>
                        </td>
                        <td class="border-end d-flex justify-content-center px-3">
                            <div class="pt-3 pb-2">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <span class="small small-text pt-2 text-nowrap text-center fw-bold">Domminic E. Mbise</span>
                            </div>
                        </td>
                        <td class="d-flex justify-content-center px-3">
                            <div class="pt-3 pb-2">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <span class="small small-text pt-2 text-nowrap text-center fw-bold">Domminic E. Mbise</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
              </table>
              <!-- End Candidates lists Table -->
            </div>

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
          </div>
    </section>