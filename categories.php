<div class="pagetitle">
      <h1>MUSTSO 2023 Election</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Election Categories</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="card-title m-0">Categories</h5>
              <a href="#" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategory">Add New</a>
          </div>
          <span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
              <i class="bi bi-arrows-expand"></i> Scroll Horizontally
          </span>
            <div class="card-body table-responsive">

              <!-- Categories list Table -->
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Category</th>
                    <th class="text-center" scope="col">Action</th>
                  </tr>
                </thead>
                <tbody class="election">
                  <?php
                    $i = 1;
                    $election = $conn->prepare("SELECT * FROM categories ORDER BY created_at ASC");
                    $election->execute();
                    $result = $election->get_result();
                    while($row = $result->fetch_assoc()){
                      
                  echo '<tr>
                    <th scope="row">'.$i++.'</th>
                    <td>'.$row['name'].'</td>
                    <td class="text-center"><a href="#" class="btn btn-primary btn-sm category-modal" data-bs-toggle="modal" data-bs-target="#editCategory" data-id="'.$row['id'].'" data-name="'. $row['name'].'"><i class="bi bi-pencil"></i> Edit</a>
                    <a href="#" class="btn btn-danger btn-sm category-delete" data-id="'.$row['id'].'" data-name="'.$row['name'].'"><i class="bi bi-trash"></i> Delete</a></td>
                  </tr>';
                    } ?>
                </tbody>
              </table><!-- End Categories lists Table -->

              <!-- Add Category Modal -->
              <div class="modal fade" id="addCategory" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Add Category</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="add-category-form">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="category" class="form-label">Category Name</label>
                                    <input type="text" name="category" class="form-control" id="category" data-error-message="Category is required">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div><!-- End Add Category Modal Dialog -->

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