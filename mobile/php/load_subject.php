<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}

include_once("dbconnect.php");
$results_per_page = 5;
$pageno = (int)$_POST['pageno'];
$search = $_POST['search'];
$page_first_result = ($pageno - 1) * $results_per_page;

$sqlloadsubjects = "SELECT tbl_subjects.subject_id, tbl_subjects.subject_name, tbl_subjects.subject_description, tbl_subjects.subject_price, tbl_subjects.subject_sessions, tbl_subjects.subject_rating, tbl_subjects.tutor_id, tbl_tutors.tutor_name FROM tbl_subjects INNER JOIN tbl_tutors ON tbl_subjects.tutor_id = tbl_tutors.tutor_id AND tbl_subjects.subject_name LIKE '%$search%'";
$result = $conn->query($sqlloadsubjects);
$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlloadsubjects = $sqlloadsubjects . " LIMIT $page_first_result , $results_per_page";
$result = $conn->query($sqlloadsubjects);
if ($result->num_rows > 0) {
    $subjects["subjects"] = array();
    while ($row = $result->fetch_assoc()) {
        $sjlist = array();
        $sjlist['subject_id'] = $row['subject_id'];
        $sjlist['subject_name'] = $row['subject_name'];
        $sjlist['subject_description'] = $row['subject_description'];
        $sjlist['subject_price'] = $row['subject_price'];
        $sjlist['tutor_id'] = $row['tutor_id'];
        $sjlist['subject_sessions'] = $row['subject_sessions'];
        $sjlist['subject_rating'] = $row['subject_rating'];
        $sjlist['tutor_name'] = $row['tutor_name'];
        array_push($subjects["subjects"],$sjlist);
    }
    $response = array('status' => 'success', 'pageno'=>"$pageno",'numofpage'=>"$number_of_page", 'data' => $subjects);
    sendJsonResponse($response);
} else {
    $response = array('status' => 'failed', 'pageno'=>"$pageno",'numofpage'=>"$number_of_page", 'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}

?>