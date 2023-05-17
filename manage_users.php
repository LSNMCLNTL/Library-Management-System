<link href="style_manage_books.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin Page</title>
</head>
<body>
<?php 
    include 'config_database.php';
    require_once 'navbar.php';
    session_start();
    get_navbar();
        // Check if the user is logged in
    if (!isset($_SESSION['uname'])) {
        header("Location: ./admin/login.php");
        exit();
    }
    // Set default values
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $records_per_page = 10; // Number of records to display per page

    // Query to fetch total number of records in the database
    $count_query = "SELECT COUNT(*) FROM users";
    $result = mysqli_query($conn, $count_query);
    $row = mysqli_fetch_row($result);
    $total_records = $row[0];

    // Calculate total number of pages
    $total_pages = ceil($total_records / $records_per_page);

    // Calculate offset for current page
    $offset = ($page - 1) * $records_per_page;

    // Start building the SQL query
    $query = "SELECT * FROM users";

    // Check if a search query has been submitted
    if (isset($_GET['search'])) {
        $search_query = $_GET['search'];
        // Add the search condition to the SQL query
        $query .= " WHERE first_name LIKE '%$search_query%' OR last_name LIKE '%$search_query%'";
    }

    // Add the limit and offset to the SQL query
    $query .= " LIMIT $offset, $records_per_page";

    $result = mysqli_query($conn, $query);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    get_navbar();
?>
    
    <div class="main_content">
        <div class="container rounded-top bg-white mt-5 mb-6 p-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Manage Users</h2>
            <a href="add_users.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Users</a>
            </div>   
            <div class="pull-right">
                <form class="form-inline" action="manage_users.php" method="get">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" name="search">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Username</th>
                <th scope="col">Contact No.</th>             
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($users as $user) { ?>
                <tr>
                  <td><?php echo $user['first_name'] ?></td>
                  <td><?php echo $user['last_name'] ?></td>
                  <td><?php echo $user['username'] ?></td>
                  <td><?php echo $user['contact_no'] ?></td>

                  
                  <td>
                   <a href="update_users.php?id=<?php echo $user['user_id'] ?>"><i class="fas fa-edit"></i></a>
                   <button onClick="deleteme(<?php echo $user['user_id']; ?>)" class="btn btn-link text-danger"><i class="fas fa-trash"></i></button>
                </td>
                <script language="javascript">
                function deleteme(delid)
                {
                  if(confirm("Do you want delete a user?")){
                    window.location.href='delete_users.php?id=' +delid+'';
                    return true;
                  }
                }		
                </script>

                  </td>
                </tr>
              <?php }  ?>
            </tbody>
          </table>
            <nav aria-label="Page navigation example" >
              <ul class="pagination justify-content-end" style="font-size: 15px;">
                <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $page-1; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                  </a>
                </li>
                <?php for($i=1;$i<=$total_pages;$i++) { ?>
                  <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
                <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $page+1; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                  </a>
                </li>
              </ul>
            </nav>

        </div>
        
    </div>
</div>


</body>
</html>