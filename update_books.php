<link href="style_manage_books.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<?php
// Include config file
require_once 'config_database.php';
require_once 'navbar.php';
session_start();
get_navbar();
    // Check if the user is logged in
if (!isset($_SESSION['uname'])) {
    header("Location: ./admin/login.php");
    exit();
}
// Define variables and initialize with empty values
$title = $author = $publisher = $copyright_year = $year_published = $address = $isbn = $page = $cm = $acc_no = $type_id ="";
$title_err = $copyright_year_err = $page_err = $cm_err = $acc_no_err = $type_id_err ="" ;
    
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter the title of the book.";
    } else{
        $title = trim($_POST["title"]);
    }
    //Validate author
    $author = !empty(trim($_POST['author'])) ? trim($_POST['author']) : NULL;
    //Validate publisher
    $publisher = !empty(trim($_POST['publisher'])) ? trim($_POST['publisher']) : NULL;
   
    $copyright_year = !empty(trim($_POST['copyright_year'])) ? trim($_POST['copyright_year']) : NULL;
    
    $year_published = !empty(trim($_POST['year_published'])) ? trim($_POST['year_published']) : NULL;
  
    // Validate address
    $address = !empty(trim($_POST['address'])) ? trim($_POST['address']) : NULL;
    
    //Validate isbn 
    $isbn = !empty(trim($_POST['isbn'])) ? trim($_POST['isbn']) : NULL;

    // Validate no of pages
    $page = !empty(trim($_POST['page'])) ? trim($_POST['page']) : NULL;
    
    //Validate cm
    $cm = !empty(trim($_POST['cm'])) ? trim($_POST['cm']) : NULL;
    
    $acc_no = !empty(trim($_POST['acc_no'])) ? trim($_POST['acc_no']) : NULL;
    
    
 
    
        $sql = "UPDATE books SET title=?, author=?, publisher=?, copyright_year=?, year_published=?, address=?, isbn=?, page=?, cm=?, acc_no=? WHERE id=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssiissiiis", $param_title, $param_author, $param_publisher, $param_copyright_year,
                                    $param_year_published, $param_address, $param_isbn, $param_page, $param_cm, $param_acc_no, $param_id);

            // Set parameters
            $param_title = $title;
            $param_author = $author;
            $param_publisher = $publisher;
            $param_copyright_year = $copyright_year;
            $param_year_published = $year_published;
            $param_address = $address;
            $param_isbn = $isbn;
            $param_page = $page;
            $param_cm = $cm;
            $param_acc_no = $acc_no;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            header("location: manage_books.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

// Close connection
mysqli_close($conn);

} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM books WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $title = $row["title"];
                    $author = $row["author"];
                    $publisher = $row["publisher"];
                    $copyright_year = $row["copyright_year"];
                    $year_published= $row["year_published"];
                    $address = $row["address"]; 
                    $isbn = $row["isbn"];
                    $page = $row["page"];
                    $cm = $row["cm"];
                    $acc_no = $row["acc_no"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: index.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($conn);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: index.php");
        exit();
    }
}
get_navbar();
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
            <li><a href="#">Logout</a></li>
            </ul> 
        </div>
        
    </div>
    <div class="main_content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Add Book</h2>
                    <p>Please fill this form and submit to add a book to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Title of the Book</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err;?></span>
                        </div> 
                        <div class="form-group">
                            <label>Author of the Book</label>
                            <input type="text" name="author" class="form-control" value="<?php echo $author; ?>">
                        </div>
                        <div class="form-group">
                            <label>Publisher</label>
                            <input type="text" name="publisher" class="form-control" value="<?php echo $publisher; ?>">
                        </div>
                         <div class="form-group">
                            <label>Copyright Year</label>
                            <input type="number" name="copyright_year" class="form-control" value="<?php echo $copyright_year; ?>">
                        </div>
                        <div class="form-group">
                            <label>Year of Publication.</label>
                            <input type="number" name="year_published" class="form-control" value="<?php echo $year_published; ?>">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address;?></span>
                        </div>
                        <div class="form-group">
                            <label>ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="<?php echo $isbn; ?>">
                        </div>
                        <div class="form-group">
                            <label>Pages</label>
                            <input type="number" name="page" class="form-control" value="<?php echo $page; ?>">
                        </div>      
                        <div class="form-group">
                            <label>CM</label>
                            <input type="number" name="cm" class="form-control" value="<?php echo $cm; ?>">
                        </div> 
                         <div class="form-group">
                            <label>Acc No.</label>
                            <input type="number" name="acc_no" class="form-control" value="<?php echo $acc_no; ?>">
                        </div>  
                         <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div> 
        </div>
</body>
</html>