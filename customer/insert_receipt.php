<?php
include "function.php";
connectdb();
session_start();
$pub_id = $_SESSION["cusid"];

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";
$i = 0;
$total = 0;
$bookarr = array();
$coin = $_SESSION['coin'];

$sqlcart = "select * from carts inner join book on book_id = cart_bookid
where cart_cusid = '$pub_id'";
$result = connectdb()->query($sqlcart);

if ($result->num_rows > 0) {
    $newcoin = floatval($coin);

    while ($row = $result->fetch_assoc()) {
        $bookarr[] = $row['cart_bookid'];
        $total += $row['book_price'];
    }
    $newcoin -= $total;
    $sqlupcoin = "update customer set cus_coin = '$newcoin' where cus_id = '$pub_id'";
    $excoin = connectdb()->query($sqlupcoin);

    //query lastid
    $lastreceiptid = autoid('R#', 'rec_id', 'receipts', '00001');

    $sqlins_receipt = "insert into receipts (rec_id,rec_date,rec_total,rec_cusid)
            values ('$lastreceiptid',NOW(),'$total','$pub_id')";
            $result3 = connectdb()->query($sqlins_receipt);

    foreach ($bookarr as $bookid) {
        $sqlbook_shelf = "select * from bookshelf
        where bs_bookid = '$bookid' and bs_uid = '$pub_id' and bs_status = '1'";
        $result2 = connectdb()->query($sqlbook_shelf);
        if ($result2->num_rows > 0) {
            $i++;

            if (!$result3) {
                die(mysqli_error(connectdb()));
            } 
            else {
                $sqlins_detail = "insert into receipts_detail (rec_no,recd_recid,rec_bookid,rec_proid)
                values ('$i','$lastreceiptid','$bookid',NULL)";
                $result4 = connectdb()->query($sqlins_detail);

                if (!$result4) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlupdate_shelf = "update bookshelf set bs_status = '2' 
                    where bs_bookid = '$bookid' and bs_uid = '$pub_id'";
                    $result5 = connectdb()->query($sqlupdate_shelf);

                    if (!$result5) {
                        die(mysqli_error(connectdb()));
                    } 
                    else {
                        $sqldel_cart = "delete from carts where cart_cusid = '$pub_id'";
                        $result6 = connectdb()->query($sqldel_cart);

                        if (!$result6) {
                            die(mysqli_error(connectdb()));
                        } 
                        else {
                            echo '
                            <script>
                                sweetalerts("สั่งซื้อสำเร็จ!!","success","","mybook.php");
                            </script>
                                ';
                        }
                    }
                }
            }
        }
        else{
            $sqlinsert_shelf = "insert into bookshelf (bs_bookid,bs_uid,bs_status)
            values ('$bookid','$pub_id','1')";
            $result = connectdb()->query($sqlinsert_shelf);
            if (!$result){
                die(mysqli_error(connectdb()));
            }
            else{
                $i++;

                if (!$result2) {
                    die(mysqli_error(connectdb()));
                } 
                else {
                    $sqlins_detail = "insert into receipts_detail (rec_no,recd_recid,rec_bookid,rec_proid)
                    values ('$i','$lastreceiptid','$bookid',NULL)";
                    $result3 = connectdb()->query($sqlins_detail);

                    if (!$result3) {
                        die(mysqli_error(connectdb()));
                    } else {
                        $sqlupdate_shelf = "update bookshelf set bs_status = '2' 
                        where bs_bookid = '$bookid' and bs_uid = '$pub_id'";
                        $result4 = connectdb()->query($sqlupdate_shelf);

                        if (!$result4) {
                            die(mysqli_error(connectdb()));
                        } 
                        else {
                            $sqldel_cart = "delete from carts where cart_cusid = '$pub_id'";
                            $result5 = connectdb()->query($sqldel_cart);
                            // ตรวจสอบว่ามี session ที่เก็บอยู่หรือไม่
                            if (isset($_SESSION['coin'])) {
                                // ลบข้อมูล session 
                                unset($_SESSION["coin"]);
                            }

                            if (!$result5) {
                                die(mysqli_error(connectdb()));
                            } 
                            else {
                                echo '
                                <script>
                                    sweetalerts("สั่งซื้อสำเร็จ!!","success","","mybook.php");
                                </script>
                                    ';
                            }
                        }
                    }
                }
            }
        }
    }
    
}
connectdb()->close();
?>
