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
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $records_per_page = 100; // Number of records to display per page

    // Query to fetch total number of records in the database
    $count_query = "SELECT COUNT(*) FROM books";
    $result = mysqli_query($conn, $count_query);
    $row = mysqli_fetch_row($result);
    $total_records = $row[0];

    // Calculate total number of pages
    $total_pages = ceil($total_records / $records_per_page);

    // Calculate offset for current page
    $offset = ($page - 1) * $records_per_page;

    // Start building the SQL query
    $query = "SELECT books.*, types.type_name 
              FROM books 
              JOIN types ON books.type_id = types.id";

    // Check if a search query has been submitted
    if (isset($_GET['search'])) {
        $search_query = $_GET['search'];
        // Add the search condition to the SQL query
        $query .= " WHERE books.title LIKE '%$search_query%'
                    OR books.author LIKE '%$search_query%'
                    OR types.type_name LIKE '%$search_query%'";
    }

    // Add the limit and offset to the SQL query
    $query .= " LIMIT $offset, $records_per_page";

    $result = mysqli_query($conn, $query);
    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    get_navbar();
?>
<
    <div class="main_content">
        <div class="container rounded-top bg-white mt-5 mb-6 p-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Library Catalogue</h2>
            <a href="add_books.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Books</a>
            </div>   
            <div class="pull-right">
                <form class="form-inline" action="manage_books.php" method="get">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" name="search">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            
          <table class="table table-striped table-">
            <thead>
              <tr>
                <th scope="col" class="col-xs-1">Title of Book</th>
                <th scope="col">Author</th>
                <th scope="col">Publisher</th>
                <th scope="col">Copyright</th>             
                <th scope="col">ISBN</th>
                <th scope="col">Pages</th> 
                <th scope="col">Type</th>
                <th scope="col"class="col-xs-1" >Status</th>
                <th scope="col"  class="col-xs-1">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($books as $book) { ?>
                <tr>
                  <td><?php echo $book['title'] ?></td>
                  <td><?php echo $book['author'] ?></td>
                  <td><?php echo $book['publisher'] ?></td>
                  <td>
                    <?php 
                        $years = $book['copyright_year'];
                        if (!empty($years)) {
                            $years_arr = explode(',', $years);
                            foreach ($years_arr as $year) {
                                echo $year . '<br>';
                            }
                        }
                    ?> 
                 </td>
                  <td><?php echo $book['isbn'] ?></td>
                  <td><?php echo $book['page'] ?></td>
                  <td><?php echo $book['type_name'] ?></td>
                  <td ><?php echo $book['status'] ?></td>
                  
                  
                <td>
                  <a href="update_books.php?id=<?php echo $book['id'] ?>"><i class="fas fa-edit"></i></a>
                  <?php if($book['status'] == 'Available') { ?>
                    <a href="borrow.php?id=<?php echo $book['id'] ?>"><i class='fas fa-book'></i></a>
                  <?php } else { ?>
                    <span><i class='fas fa-ban'></i></span>
                  <?php } ?>
                    <button onClick="deleteme(<?php echo $book['id']; ?>)" class="btn btn-link text-danger"><i class="fas fa-trash"></i></button>
                </td>
                <script language="javascript">
                function deleteme(delid)
                {
                  if(confirm("Do you want delete a book?")){
                    window.location.href='delete_books.php?id=' +delid+'';
                    return true;
                  }
                }		
                </script>

                  
                  
                </tr>
              <?php 
                    }  
                    ?>
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