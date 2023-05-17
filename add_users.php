<link href="style_manage_books.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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
// Define variables and initialize with empty values
$first_name = $last_name = $password = $username = $contact_no= "";
$first_name_err = $last_name_err = $password_err = $username_err = $contact_no_err= "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter first name.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter last name.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT user_id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate contact number
    if(empty(trim($_POST["contact_no"]))){
        $contact_no_err = "Please enter contact number.";
    } else if (!is_numeric($_POST["contact_no"])) {
        $contact_no_err = "Please enter a valid contact number.";
    } else {
        $contact_no = trim($_POST["contact_no"]);
    }


    // Check input errors before inserting in database
    // Check input errors before inserting in database
if(!empty($first_name_err) || !empty($last_name_err) || !empty($password_err) || !empty($username_err) || !empty($contact_no_err)){

    // Display error messages
    echo "Please correct the following errors:<br>";
    echo $first_name_err . "<br>";
    echo $last_name_err . "<br>";
    echo $password_err . "<br>";
    echo $username_err . "<br>";
    echo $contact_no_err . "<br>";

} else {

    // Prepare an insert statement
    $sql = "INSERT INTO users (first_name, last_name, password, username, contact_no) VALUES (?, ?, ?, ?, ?)";

    // Attempt to prepare an insert statement
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssi", $param_first_name, $param_last_name, $param_password, $param_username, $param_contact_no);

        // Set parameters
        $param_first_name = $first_name;
        $param_last_name = $last_name;
        $param_password = $password;
        $param_username = $username;
        $param_contact_no = $contact_no;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            header("location: manage_users.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);

}
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
                    <h2 class="mt-5">Add User</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                            <span class="invalid-feedback"><?php echo $first_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                            <span class="invalid-feedback"><?php echo $last_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>UserName</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Contact No.</label>
                            <input type="number" name="contact_no" class="form-control <?php echo (!empty($contact_no_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $contact_no; ?>">
                            <span class="invalid-feedback"><?php echo $contact_no;?></span>
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