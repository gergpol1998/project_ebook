<?php
include "function.php";
connectdb();

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if(isset($_GET['bookid'])){
    $bookid = $_GET['bookid'];
    $result = deletedata("tag_book","tb_bookid = '$bookid'");
    if (!$result) {
        die(mysqli_error(connectdb()));
    } 
    else {
        $result2 = deletedata("book_type","bt_bookid = '$bookid'");

        if (!$result2) {
            die(mysqli_error(connectdb()));
        }
        else{
            $result3 = deletedata("book","book_id = '$bookid'");
            echo '
            <script>
                sweetalerts("ลบข้อมูลสำเร็จ!!","success","","cancle_book.php");
            </script>
                ';
        }
    }
}
?>