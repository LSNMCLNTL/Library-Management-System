<?php
    include_once("config_database.php");
    $select = "DELETE from admin where admin_id='".$_GET['id']."'";
    $query = mysqli_query($conn, $select) or die($select);
    header ("Location: manage_admin.php");
?>