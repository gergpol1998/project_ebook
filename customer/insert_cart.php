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
    where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid' and bshelf_status = '0'";
    $result = connectdb()->query($sqlbook_shelf);

    if($result->num_rows > 0){
        $result2 = insertdata("cart","cart_bookid,cart_cusid","'$bookid','$cusid'");

        if ($result2) {
            echo '
                 <script>
                        sweetalerts("เพิ่มเข้าตะกร้า!!","success","","index.php");
                </script>
                                ';
        } 
    }
    else{
        $sqlinsert_shelf = "insert into bookshelf (bshelf_bookid,bshelf_cusid,bshelf_status)
        values ('$bookid','$cusid','0')";
        $result3 = connectdb()->query($sqlinsert_shelf);
        
        
        if (!isset($result3)) {
            die(mysqli_error(connectdb()));
        }
        else{
            $result4 = insertdata("cart","cart_bookid,cart_cusid","'$bookid','$cusid'");
            if (!$result4){
                die(mysqli_error(connectdb()));
            }
            else{
                echo '
                 <script>
                        sweetalerts("เพิ่มเข้าตะกร้า!!","success","","index.php");
                    </script>
                                ';
            }
            
        }
    }
    
    connectdb()->close();
}
?>