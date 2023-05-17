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

if(isset($_POST['borrow'])) {
    // retrieve the form data
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];

    // check if the book is available
    $sql = "SELECT * FROM books WHERE id=$book_id AND status='Available'";
    $result = mysqli_query($conn, $sql);
    
    if(strtotime($return_date) < strtotime($issue_date)) {
        echo "<script>alert('Return date cannot be earlier than issue date!');</script>";
    } else {
    
        if(mysqli_num_rows($result) > 0) {
            $sql = "UPDATE books SET status='Borrowed' WHERE id=$book_id";
            if(mysqli_query($conn, $sql)) {
                $sql = "INSERT INTO book_issue (user_id, book_id, issue_date, return_date, status) VALUES ('$user_id', '$book_id', '$issue_date', '$return_date', 'Borrowed')";
                if(mysqli_query($conn, $sql)) {
                    echo "<script>alert('Borrowing record created successfully.');</script>";
                    header("Location: manage_books.php");
                    exit();
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "<script>alert('The book is already borrowed. Please choose another book.');</script>";
        }
    }
}

// retrieve the list of users from the database
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// retrieve the book ID from the URL
$book_id = $_GET['id'];

// retrieve the book details from the database
$sql = "SELECT * FROM books WHERE id = $book_id";
$result2 = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result2);

// display the borrowing form
?>
<style>
    form {

        height: 650px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }
    label, h2{
        font-size: 24px;
        margin: 10px 0;


    }
    input, select {
        width: 70%;
        height: 100px;
        font-size: 24px;
        margin: 10px 0;
        border: none;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }
    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
    }
</style>

    <div class="main_content">
        <div class="container rounded-top bg-white mt-5 mb-6 p-7">
            <div class="tae" style="padding: 50px; ">  
        <h2>Borrow Book: <?php echo $row['title']; ?></h2>
        <form method="post">
            <h4><label for="user_id">Select User:</label></h4>
            <select name="user_id">
                <?php while($user = mysqli_fetch_assoc($result)): ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <h4><label for="issue_date">Issue Date:</label></h4>
            <input type="date" name="issue_date" required><br><br>
            <h4><label for="return_date">Return Date:</label></h4>
            <input type="date" name="return_date" required><br><br>
            <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
            <input type="submit" name="borrow" value="Borrow">
        </form>
        </center>  
            </div>
    </div>
    </div>

</body>
</html>