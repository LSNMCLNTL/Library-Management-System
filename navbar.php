<?php
// Create a function that will return the HTML for the navbar
function get_navbar()
{

?>

<div class="wrapper">
    <div class="sidebar">
        <h2>Antipolo City Library</h2>
        <ul>
            <li><a href="index.php">Admin Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_books.php">Library Catalogue</a></li>
            <li><a href="manage_admin.php">Manage Admin</a></li>
            <li><a href="borrowing_history.php">Borrowing History</a></li>
                

        </ul> 
        <div class="logout">
            <ul>
            <li><a href="./admin/logout.php">Logout</a></li>
            </ul> 
        </div>

        
    </div>

<?php
}
?>