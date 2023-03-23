<?php
include "function.php";
connectdb();
echo "<script src='function.js'></script>";

if (isset($_GET['bookid'])) {
    $bookid = $_GET['bookid'];
    $sqlbook = select_where("book_name,book_test","book","book_id = '$bookid'");
        if($sqlbook->num_rows > 0){
            $row = $sqlbook->fetch_assoc();
        }
} 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>testread</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

</head>

<body class="mb-3">
    <div class="text-center">
    <h1 class="text-center"><?php echo $row['book_name']?></h1>
    <embed src="<?php echo $row['book_test']?>#toolbar=0" height="100%" width="100%"/>
    </div>
</body>
<?php
connectdb()->close();
?>
</html>