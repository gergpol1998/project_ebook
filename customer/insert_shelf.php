<?php
include "function.php";
connectdb();
session_start();
if (isset($_GET['bookid']) && isset($_SESSION['cusid'])){

    echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";
    
    $bookid = $_GET['bookid'];
    $cusid = $_SESSION['cusid'];

    $sqlinsert_shelf = "insert into bookshelf (bs_bookid,bs_uid,bs_status)
        values ('$bookid','$cusid','1')";
        $result = connectdb()->query($sqlinsert_shelf);

        if ($result) {
            echo '
                <script>
                    sweetalerts("เพิ่มเข้าชั้นหนังสือเรียบร้อย!!","success","","index.php");
                </script>
                ';
        } else {
            echo '
                <script>
                    sweetalerts("ไม่สามารถพิ่มเข้าชั้นหนังสือได้!!","warning","","index.php");
                </script>
                ';
        }
    connectdb()->close();
}
?>