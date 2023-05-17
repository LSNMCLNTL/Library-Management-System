<link href="style_manage_books.css?v=2" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin Page</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php 
    include 'config_database.php';
    require_once 'navbar.php';
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['uname'])) {
        header("Location: ./admin/login.php");
        exit();
    }

 
        // Total Books
    $query = "SELECT * FROM books";
    $result = mysqli_query($conn, $query);
    $books = array();

    // Put all books into an array
    if($query) {
        while($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
    }
    // Total Books Borrowed
    $query1 = "SELECT COUNT(*) AS count FROM book_issue WHERE status = 'Borrowed'";
    $result1 = mysqli_query($conn, $query1);
    $row1 = mysqli_fetch_assoc($result1);
    $borrowed_count = $row1['count'];

    // Total Books Returned
    $query2 = "SELECT COUNT(*) AS count FROM book_issue WHERE status = 'Returned'";
    $result2 = mysqli_query($conn, $query2);
    $row2 = mysqli_fetch_assoc($result2);
    $returned_count = $row2['count'];

    // Total Users Registered
    $query3 = "SELECT COUNT(*) AS count FROM users";
    $result3 = mysqli_query($conn, $query3);
    $row3 = mysqli_fetch_assoc($result3);
    $users_count = $row3['count'];

    get_navbar();
    ?>
<div class="main_content">
    <div class="container">
    <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">   
      <div class="card">
        <div class="card-body text-center">
          <div class="d-flex align-items-center justify-content-center mb-4"> 
            <h1 class="card-title mb-0">Total Books</h1>
             <i class="fa fa-book fa-4x mr-3"></i>
          </div>
          <h1 class="card-text"><?php echo count($books); ?></h1>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12">      
    <div class="card">
        <div class="card-body text-center">
            <div class="d-flex align-items-center justify-content-center mb-4"> 
              <h1 class="card-title mb-0">Total Books Borrowed</h1>
               <i class="fa fa-archive fa-4x mr-3"></i>
            </div>
          <h1 class="card-text"><?php echo $borrowed_count; ?></h1>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">   
      <div class="card">
        <div class="card-body text-center">
            <div class="d-flex align-items-center justify-content-center mb-4"> 
              <h1 class="card-title mb-0">Total Books Returned</h1>
               <i class="fa fa-recycle fa-4x mr-3"></i>
            </div>
          <h1 class="card-text"><?php echo $returned_count; ?></h1>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">   
      <div class="card">
        <div class="card-body text-center">
            <div class="d-flex align-items-center justify-content-center mb-4"> 
              <h1 class="card-title mb-0">Total Users Registered</h1>
               <i class=" fa fa-user-plus fa-4x mr-3"></i>
            </div>
          <h1 class="card-text"><?php echo $users_count; ?></h1>
        </div>
      </div>
    </div>
  </div>
</div>

    </div>
</body>
</html>




  

