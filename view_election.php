<?php
include 'config/dbconnection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM election WHERE id = $id");

    if ($result) {
        $row = $result->fetch_assoc();

        if ($row) {
        echo '<div class="card-body">
                <h3 class="card-title">'.$row['title'].'</h3>
                <p class="small fst-italic">'.$row['description'].'</p>';
                // ($row['status'] == 1) ? 'Deactivate' : 'Activate';
                if($row['status'] == 0) {
                    echo '<h5><span class="badge bg-secondary">The election is not active</span></h5>';
                   }
                elseif($row['status'] == 1) {
                    echo '<h5><span class="badge bg-success">The election is active</span></h5>';
                   }
                else {
                    echo '<h5><span class="badge bg-warning">The election is pending</span></h5>';
                   }

            echo'<div class="row">
                  <div class="col-4 label "><strong>Year:</strong></div>
                  <div class="col-8">'.$row['year'].'</div>
                </div>

                <div class="row">
                  <div class="col-4 label"><strong>Voters:</strong></div>
                  <div class="col-8">'.$row['voters'].'</div>
                </div>

                <div class="row">
                  <div class="col-4 label"><strong>Start On:</strong></div>
                  <div class="col-8 text-info">'.$row['starttime'].'</div>
                </div>

                <div class="row">
                  <div class="col-4 label"><strong>End On:</strong></div>
                  <div class="col-8 text-danger">'.$row['endtime'].'</div>
                </div>
              </div>';
        } else {
            echo "No data";
        }
    } else {
        echo "Error executing the query.";
    }

    $conn->close();
}
 else {
    
    echo "500 Error! No id found in URL.";
}
