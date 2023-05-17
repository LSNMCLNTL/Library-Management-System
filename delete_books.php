<?php
    include_once("config_database.php");
    $select = "DELETE from books where id='".$_GET['id']."'";
    $query = mysqli_query($conn, $select) or die($select);
    header ("Location: manage_books.php");
?>