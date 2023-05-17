<link href="style_manage_books.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<?php
// Include database connection
include 'config_database.php';
require_once 'navbar.php';
session_start();
get_navbar();
    // Check if the user is logged in
if (!isset($_SESSION['uname'])) {
    header("Location: ./admin/login.php");
    exit();
}
$title = $author = $publisher = $copyright_year = $year_published = $address = $isbn = $page = $cm = $acc_no = $type_id ="";
$title_err = $copyright_year_err = $page_err = $cm_err = $acc_no_err = $type_id_err ="" ;
$status = "Available";
    
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    

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
   
    // Validate copyright year
    if(isset($_POST["copyright_year"])){
        $copyright_year = trim($_POST["copyright_year"]);
        if(empty($copyright_year)){
            $year = null;
        } else{
            $copyright_year = (int) $copyright_year;
            if($copyright_year < 1900 || $copyright_year > date("Y")){
                $copyright_year_err = "Please enter a valid year between 1900 and " . date("Y") . ".";
            }
        }
    } else {
        $copyright_year = null;
    }
    
    // Validate year of publication
    if(isset($_POST["year_published"])){
        $year_published = trim($_POST["year_published"]);
        if(empty($year_published)){
            $year_published = null;
        } else{
            $year = (int) $year_published;
            if($year_published < 1900 || $year_published > date("Y")){
                $year_published_err = "Please enter a valid year between 1900 and " . date("Y") . ".";
            }
        }
    } else {
        $year_published = null;
    }
    // Validate address
    $address = !empty(trim($_POST['address'])) ? trim($_POST['address']) : NULL;
    
    //Validate isbn 
    $isbn = !empty(trim($_POST['isbn'])) ? trim($_POST['isbn']) : NULL;

    // Validate no of pages
    if(empty(trim($_POST["page"]))){
    $page_err = "<script>alert('Please enter the number of pages')</script>";
    } else if (!is_numeric($_POST["page"])) {
        $page_err = "Please enter a valid number.";
    } else {
        $page = trim($_POST["page"]);
    }
    
    //Validate cm
    if(empty(trim($_POST["cm"]))){
        $cm_err = "Please enter the cm of book.";
    } else if (!is_numeric($_POST["cm"])) {
        $cm_err = "Please enter a valid cm.";
    } else {
        $cm= trim($_POST["cm"]);
    }
    // Validate type
    $type_ids = $_POST["type_ids"] ?? array(); // Initialize $type_ids to an empty array if it's not set in $_POST
    if(empty($type_ids)){
        $type_id_err = "Please select the type of book.";
    } else {
        $param_type_id = $type_ids[0]; // Get the first selected type
        // Check if the type_id value is valid and exists in the types table
        $sql = "SELECT id FROM types WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_type_id);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) != 1){
                    $type_id_err = "Invalid type selected.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }


     // Validate username
    if(empty(trim($_POST["acc_no"]))){
        $username_err = "Please enter an acc no.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM books WHERE acc_no = ?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["acc_no"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $acc_no_err = "This acc no is already taken.";
                } else{
                    $acc_no = trim($_POST["acc_no"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    $status = "Available";
    $param_status ="Available";
    // Attempt to prepare an insert statement
    $sql = "INSERT INTO books (title, author, publisher, copyright_year, year_published, address, isbn, page, cm, acc_no, type_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
           mysqli_stmt_bind_param($stmt, "sssiissiisis", $param_title, $param_author, $param_publisher, $param_copyright_year,
                                $param_year_published, $param_address, $param_isbn, $param_page, $param_cm, $param_acc_no, $param_type_id, $param_status);

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
        $param_status = $status;
        $param_type_id = $type_ids[0]; // assuming only one type is selected

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

}
?>
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
                            <span class="invalid-feedback"><?php echo $author;?></span>
                        </div>
                        <div class="form-group">
                            <label>Publisher</label>
                            <input type="text" name="publisher" class="form-control" value="<?php echo $publisher; ?>">
                            <span class="invalid-feedback"><?php echo $publisher;?></span>
                        </div>
                         <div class="form-group">
                            <label>Copyright Year</label>
                            <input type="number" name="copyright_year" class="form-control" value="<?php echo $copyright_year; ?>">
                            <span class="invalid-feedback"><?php echo $copyright_year;?></span>
                        </div>
                        <div class="form-group">
                            <label>Year of Publication.</label>
                            <input type="number" name="year_published" class="form-control" value="<?php echo $year_published; ?>">
                            <span class="invalid-feedback"><?php echo $year_published;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address;?></span>
                        </div>
                        <div class="form-group">
                            <label>ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="<?php echo $isbn; ?>">
                            <span class="invalid-feedback"><?php echo $isbn;?></span>
                        </div>
                        <div class="form-group">
                            <label>Pages</label>
                            <input type="number" name="page" class="form-control <?php echo (!empty($page_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $page; ?>">
                            <span class="invalid-feedback"><?php echo $page_err;?></span>
                        </div>      
                        <div class="form-group">
                            <label>CM</label>
                            <input type="number" name="cm" class="form-control <?php echo (!empty($cm_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cm; ?>">
                            <span class="invalid-feedback"><?php echo $cm_err;?></span>
                        </div> 
                         <div class="form-group">
                            <label>Acc No.</label>
                            <input type="number" name="acc_no" class="form-control <?php echo (!empty($acc_no_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $acc_no; ?>">
                            <span class="invalid-feedback"><?php echo $acc_no_err;?></span>
                        </div>  
                        <div class="form-group">
                            <label>Type of Book</label>
                            <?php
                                // Check if the connection is open, and open a new one if needed
                                if (!mysqli_ping($conn)) {
                                    $conn = mysqli_connect(localhost, root, '', library_db);
                                }

                                $sql = "SELECT id, type_name FROM types";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $type_id = $row["id"];
                                        $type_name = $row["type_name"];

                                        echo '<div class="form-check">';
                                        echo '<input type="checkbox" name="type_ids[]" value="' . $type_id . '" class="form-check-input" id="type-' . $type_id . '">';
                                        echo '<label for="type-' . $type_id . '" class="form-check-label">' . $type_name . '</label>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "No types found.";
                                }
                            ?>
                            <span class="invalid-feedback"><?php echo $type_id_err;?></span>
                        </div>

                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div> 
        </div>
</body>
</html>