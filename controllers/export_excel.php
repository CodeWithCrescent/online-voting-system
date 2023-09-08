<?php
session_start();
require '../config/dbconnection.php';
include '../config/isadmin.php';

if (isset($_GET['election_id']) && $_GET['action'] == 'export_category' && is_numeric($_GET['election_id'])) {
    $election_id = $_GET['election_id'];

    $stmt = $conn->prepare("SELECT cat.election_id AS Election, cat.name AS 'Category Name' FROM categories cat
    JOIN election e ON cat.election_id = '$election_id' WHERE e.id = ?");
    $stmt->bind_param('i', $election_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $records = array();

    while ($rows = mysqli_fetch_assoc($result)) {
        $records[] = $rows;
    }

    $filename = date('Ymdhis') . "_Categories.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $show_column = false;
    if (!empty($records)) {
        foreach ($records as $record) {
            if (!$show_column) {
                echo implode("\t", array_keys($record)) . "\n";
                $show_column = true;
            }
            echo implode("\t", array_values($record)) . "\n";
        }
    }
}


if (isset($_GET['election_id']) && $_GET['action'] == 'export_results' && is_numeric($_GET['election_id'])) {
    $election_id = $_GET['election_id'];
    $user_id = $_SESSION['login_id'];

    $status = 1;
    $query = $conn->prepare("SELECT * FROM election WHERE status = ?");
    $query->bind_param('i', $status);
    $exec = $query->execute();
    $temp = $query->get_result();
    $show_results = $temp->fetch_assoc();

    if ($show_results) {
        $stmt = $conn->prepare("
            SELECT c.*, cat.name AS category_name, e.title AS election_name, e.id AS election_id, e.endtime AS endtime, COUNT(v.id) AS votes_count
            FROM candidates c
            JOIN categories cat ON c.category_id = cat.id
            JOIN election e ON c.election_id = e.id
            LEFT JOIN votes v ON e.id = v.election_id AND c.category_id = v.category_id AND c.id = v.candidate_id
            WHERE e.status = ?
            GROUP BY cat.id, c.id
            ORDER BY cat.id, votes_count DESC
        ");
        $stmt->bind_param('i', $status);
        $stmt->execute();
        $result = $stmt->get_result();

        try {

            $filename = date('Ymdhis') . '_' . str_replace(" ", "_", $show_results['title']) . '_' . $show_results['year'] . "_Results.xls";
            $filepath = '../assets/reports/' . $filename;

            $fp = fopen($filepath, 'w');

            // Headers
            fwrite($fp, $show_results['title'] . "\n\n");
            fwrite($fp, "Category Name\tCandidate Name\tFellow Candidate Name\tVote Counts\tStatus\n");

            $currentCategory = null;
            $firstIteration = true;
            $excluded_columns = ['id', 'category_id', 'election_id', 'election_name', 'candidate_year', 'candidate_photo', 'fellow_candidate_year', 'fellow_candidate_photo', 'added_by', 'updated_by', 'created_at', 'updated_at', 'endtime'];

            while ($row = mysqli_fetch_assoc($result)) {
                $filtered_row = array_diff_key($row, array_flip($excluded_columns));
                $categoryName = $filtered_row['category_name'];

                if ($categoryName !== $currentCategory) {
                    if ($currentCategory !== null) {
                        fwrite($fp, "\n");
                    }
                    $currentCategory = $categoryName;
                    $firstIteration = true;
                }
                $status = $firstIteration ? "WINNER" : "";

                fwrite($fp, "$categoryName\t{$row['name']}\t{$row['fellow_candidate_name']}\t{$row['votes_count']}\t$status\n");

                $firstIteration = false;
            }

            fclose($fp);

            $save = $conn->prepare("UPDATE election SET report_path = ?, updated_by = ? WHERE id = ?");
            if (!$save) {
                throw new Exception("Failed to prepare the query.");
            }

            $save->bind_param("sii", $filename, $user_id, $election_id);

            if (!$save->execute()) {
                throw new Exception("Failed to execute the query.");
            }

            $msg = "Successful Excel Report Generated!";
            echo '<script>window.location.href = "../index.php?page=reports&msg=' . urlencode($msg) . '";</script>';
        } catch (\Throwable $th) {
            $err = "Failed to generate report!";
            echo '<script>window.location.href = "../index.php?page=results&err=' . urlencode($err) . '";</script>';
        }
    } else {
        $err = "Failed to load data!";
        echo '<script>window.location.href = "../index.php?page=results&err=' . urlencode($err) . '";</script>';
    }
}

// Download Report
if (isset($_GET['election_id']) && $_GET['action'] == 'download_report') {
    $election_id = $_GET['election_id'];

    $query = $conn->prepare("SELECT * FROM election WHERE id = ?");
    $query->bind_param('i', $election_id);
    $query->execute();
    $temp = $query->get_result();
    $show_results = $temp->fetch_assoc();


    if ($show_results) {

        try {
            $file = '../assets/reports/' . $show_results['report_path'];
            $filename = date('Ymdhis') . '_' . str_replace(" ", "_", $show_results['title']) . '_' . $show_results['year'] . "_Results.xls";

            // Check if the file exists
            if (file_exists($file)) {
                // Set headers for file download
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));

                // Output the file contents
                readfile($file);
                $msg = "Excel downloaded successfully!";
                echo '<script>window.location.href = "../index.php?page=reports&msg=' . urlencode($msg) . '";</script>';
            } else {
                // File not found
                $msg = "Excel not found!";
                echo '<script>window.location.href = "../index.php?page=reports&msg=' . urlencode($msg) . '";</script>';
            }
        } catch (\Throwable $th) {
            $msg = "Failed to load excel";
            echo '<script>window.location.href = "../index.php?page=reports&msg=' . urlencode($msg) . '";</script>';
        }
    } else {
        $msg = "Failed to load data";
        echo '<script>window.location.href = "../index.php?page=reports&msg=' . urlencode($msg) . '";</script>';
    }
}


if (isset($_GET['election_id']) && $_GET['action'] == 'export_excel' && is_numeric($_GET['election_id'])) {
    $election_id = $_GET['election_id'];

    $status = 1;
    $query = $conn->prepare("SELECT * FROM election WHERE status = ?");
    $query->bind_param('i', $status);
    $exec = $query->execute();
    $temp = $query->get_result();
    $show_results = $temp->fetch_assoc();

    if ($show_results) {
        $stmt = $conn->prepare("
        SELECT c.*, cat.name AS category_name, e.title AS election_name, e.id AS election_id, e.endtime AS endtime, COUNT(v.id) AS votes_count
        FROM candidates c
        JOIN categories cat ON c.category_id = cat.id
        JOIN election e ON c.election_id = e.id
        LEFT JOIN votes v ON e.id = v.election_id AND c.category_id = v.category_id AND c.id = v.candidate_id
        WHERE e.status = ?
        GROUP BY cat.id, c.id
        ORDER BY cat.id, votes_count DESC
        ");
        $stmt->bind_param('i', $status);
        $stmt->execute();
        $result = $stmt->get_result();

        $filename = date('Ymdhis') . '_' . str_replace(" ", "_", $show_results['title']) . '_' . $show_results['year'] . "_Results.xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // The columns to exclude from export
        $excluded_columns = ['id', 'category_id', 'election_id', 'election_name', 'candidate_year', 'candidate_photo', 'fellow_candidate_year', 'fellow_candidate_photo', 'added_by', 'updated_by', 'created_at', 'updated_at', 'endtime']; // Add columns to be excluded here

        // Headers
        echo $show_results['title'] . "\n\n";
        echo "Category Name\tCandidate Name\tFellow Candidate Name\tVote Counts\tStatus\n";

        $currentCategory = null;
        $firstIteration = true;

        while ($row = mysqli_fetch_assoc($result)) {
            $filtered_row = array_diff_key($row, array_flip($excluded_columns));
            $categoryName = $filtered_row['category_name'];

            if ($categoryName !== $currentCategory) {
                if ($currentCategory !== null) {
                    echo "\n";
                }
                $currentCategory = $categoryName;
                $firstIteration = true;
            }
            $status = $firstIteration ? "WINNER" : "";

            echo "$categoryName\t{$row['name']}\t{$row['fellow_candidate_name']}\t{$row['votes_count']}\t$status\n";

            $firstIteration = false;
        }
    }
}
