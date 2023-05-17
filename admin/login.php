<?php 
session_start(); 
include "../config_database.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = $_POST['uname'];
    $password = $_POST['password'];

    // Check if both fields are filled
    if (empty($uname) || empty($password)) {
        header("Location: login.php?error=Please fill in all fields");
        exit();
    }

    // Query the database for the user
   $sql = "SELECT * FROM admin WHERE username='$uname' AND password='$password'";

   $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) == 1) {
        // User is authenticated - start a session and redirect to home.php
        session_start();
        $_SESSION['uname'] = $uname;
        header("Location: ../index.php");
        exit();
    } else {
        // User is not authenticated - redirect to login.php with an error message
        header("Location: login.php?error=Invalid%20username%20or%20password.");
        exit();
    }

    // If the user does not exist or the password is incorrect, redirect back to the login page with an error message
    header("Location: ./login.php?error=Invalid login credentials");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
	<link href="style.css?v=2" rel="stylesheet">

</head>
<body>
     <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div style="text-align: center;">
	<img src="../images/antipolo_lib_logo.png" alt="Image Description" style="max-width: 100%; height: auto;">
		</div>
        <h2>Antipolo Library Admin</h2>
     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
     	<label>User Name</label>
     	<input type="text" name="uname" placeholder="User Name"><br>

     	<label>Password</label>
     	<input type="password" name="password" placeholder="Password"><br>

     	<button type="submit">Login</button>
     </form>
</body>
</html>
