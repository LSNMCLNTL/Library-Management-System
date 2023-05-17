<link href="style_manage_books.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin Page</title>
</head>
<script>
function returnBook(issueId) {
    if (confirm("Are you sure you want to return this book?")) {
        $.ajax({ //Uncaught ReferenceError: $ is not definedat returnBook
            type: "POST",
            url: "return_book.php",
            data: {issue_id: issueId},
            success: function(data) {
                alert(data);
                location.reload();
            }
        });
    }
}
</script>
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
        // include database configuration file

        // retrieve borrowing records from the database
        $sql = "SELECT book_issue.*, books.title, users.first_name, users.last_name
                FROM book_issue
                INNER JOIN books ON book_issue.book_id = books.id
                INNER JOIN users ON book_issue.user_id = users.user_id
                ORDER BY book_issue.issue_date DESC";
        $result = mysqli_query($conn, $sql);
        get_navbar();
    ?>
    
    <div class="main_content">
        <div class="container rounded-top bg-white mt-5 mb-6 p-7">
        <?php
        // display borrowing records in a table
        echo "<h2>Borrowing History</h2>";
        echo ' <table class="table table-striped table-">
                <thead>
                <tr>
                <th scope="col" >Book Title</th>
                <th scope="col" >First Name</th>
                <th scope="col" >Last Name</th>
                <th scope="col" >Issue Date</th>
                <th scope="col" >Return Date</th>
                <th scope="col" >Status</th>
                <th scope="col"  class="col-xs-1">Actions</th>
              </tr>
              </head>';
            while ($row = mysqli_fetch_assoc($result)) {
                $returnButton = "<button type='button' class='btn btn-primary' onclick='returnBook(".$row['issue_id'].")'>Return</button>";
                if ($row['status'] == 'Returned') {
                    $returnButton = "Already returned";
                }
                echo "
                    <tbody>
                    <tr>
                        <td>".$row['title']."</td>
                        <td>".$row['first_name']."</td>
                        <td>".$row['last_name']."</td>
                        <td>".$row['issue_date']."</td>
                        <td>".$row['return_date']."</td>
                        <td>".$row['status']."</td>
                        <td>".$returnButton."</td>
                      </tr>
                      </tbody>";
            }
            echo "</table>";
        // close database connection
        mysqli_close($conn);
        ?>

    </div>
</div>


</body>
</html>