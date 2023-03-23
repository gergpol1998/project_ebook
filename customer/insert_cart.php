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

    $sqlbook_shelf = "select * from bookshelf
    where bs_bookid = '$bookid' and bs_uid = '$cusid' and bs_status = '1'";
    $result = connectdb()->query($sqlbook_shelf);

    if($result->num_rows > 0){
        $result2 = insertdata("carts","cart_bookid,cart_cusid","'$bookid','$cusid'");

        if ($result2) {
            echo '
                <script>
                    sweetalerts("เพิ่มเข้าตะกร้าเรียบร้อย!!","success","","index.php");
                </script>
                ';
        } else {
            echo '
                <script>
                    sweetalerts("ไม่สามารถพิ่มเข้าตะกร้าได้!!","warning","","index.php");
                </script>
                ';
        }
    }
    else{
        $sqlinsert_shelf = "insert into bookshelf (bs_bookid,bs_uid,bs_status)
        values ('$bookid','$cusid','1')";
        $result3 = connectdb()->query($sqlinsert_shelf);
        
        if (!isset($result3)) {
            die(mysqli_error(connectdb()));
        }
        else{
            $result4 = insertdata("carts","cart_bookid,cart_cusid","'$bookid','$cusid'");

        if ($result4) {
            echo '
                <script>
                    sweetalerts("เพิ่มเข้าตะกร้าเรียบร้อย!!","success","","index.php");
                </script>
                ';
        } else {
            echo '
                <script>
                    sweetalerts("ไม่สามารถพิ่มเข้าตะกร้าได้!!","warning","","index.php");
                </script>
                ';
        }
        }
    }
    
    connectdb()->close();
}
?>