<?php
include 'config_database.php';

$issueId = $_POST['issue_id'];

$sql = "UPDATE book_issue SET status='Returned' WHERE issue_id=$issueId";
if (mysqli_query($conn, $sql)) {
    $sql = "UPDATE books SET status='Available' WHERE id=(SELECT book_id FROM book_issue WHERE issue_id=$issueId)";
    if (mysqli_query($conn, $sql)) {
        echo "Book has been returned successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>


