<?php
include '../config/dbconnection.php';

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
        GROUP BY c.id
        ORDER BY votes_count DESC
    ");
        $stmt->bind_param('i', $status);
        $stmt->execute();
        $result = $stmt->get_result();

        $categories = array();
        foreach ($result as $key => $value) {
            // if (!empty($value['fellow_candidate_name'])) {
            $categories[$value['category_name']][] = $value;
            // }
        }

        while ($rows = mysqli_fetch_assoc($result)) {
            $categories[] = $rows;
        }

        $filename = date('Ymdhis') . "_Results.xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        foreach ($categories as $categoryName => $categoryCandidates) {
            $show_column = false;
            if (!empty($categories)) {
                foreach ($categoryCandidates as $candidate) {
                    if (!$show_column) {
                        echo implode("\t", array_keys($candidate)) . "\n";
                        $show_column = true;
                    }
                    echo implode("\t", array_values($candidate)) . "\n";
                }
            }
        }
    }
}
