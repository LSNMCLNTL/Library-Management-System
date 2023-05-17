<?php
    include_once("config_database.php");
    $select = "DELETE from users where user_id='".$_GET['id']."'";
    $query = mysqli_query($conn, $select) or die($select);
    header ("Location: manage_users.php");
?>