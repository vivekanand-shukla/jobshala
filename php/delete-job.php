<?php
// delete job handler
// only providers can delete their own jobs

include '../includes/db.php';
include '../includes/auth.php';

// check if provider is logged in
requireLogin('provider');

// get job id from form
$jobId = $_POST['job_id'];
$jobId = intval($jobId);

$userId = userId();

// delete job only if it belongs to this provider
if ($jobId > 0) {
    $deleteQuery = "DELETE FROM jobs WHERE id = $jobId AND provider_id = $userId";
    mysqli_query($conn, $deleteQuery);
    $_SESSION['flash'] = 'Job deleted successfully.';
}

// go back to dashboard
header('Location: /jobshala/provider/dashboard.php');
exit();
?>
