<?php
include "function.php";
connectdb();
session_start();
$cusid = $_SESSION["cusid"];

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";
$i = 0;
$bookarr = array();


if (isset($_SESSION['coin']) && isset($_SESSION['total'])) {
    $coin = $_SESSION['coin'];
    $total = $_SESSION['total'];
}

$sqlcart = "select * from cart inner join book on book_id = cart_bookid
where cart_cusid = '$cusid'";
$result = connectdb()->query($sqlcart);



if ($result->num_rows > 0) {
    $newcoin = floatval($coin);

    while ($row = $result->fetch_assoc()) {
        $bookarr[] = $row['cart_bookid'];
    }
    
    $newcoin -= $total;
    $sqlupcoin = "update customer set cus_coin = '$newcoin' where cus_id = '$cusid'";
    $excoin = connectdb()->query($sqlupcoin);

    //query lastid
    $lastreceiptid = receiptautoid();

    $sqlins_receipt = "insert into receipt (rec_id,rec_total,rec_date,rec_cusid)
     values ('$lastreceiptid','$total',NOW(),'$cusid')";
    $result3 = connectdb()->query($sqlins_receipt);

    foreach ($bookarr as $bookid) {
        $sqlbook_shelf = "select * from bookshelf
        where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid' and bshelf_status = '0'";
        $result2 = connectdb()->query($sqlbook_shelf);
        if ($result2->num_rows > 0) {
            $i++;

            if (!$result3) {
                die(mysqli_error(connectdb()));
            } else {
                $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid)
                values ('$i','$lastreceiptid','$bookid')";
                $result4 = connectdb()->query($sqlins_detail);

                if (!$result4) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                    where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                    $result5 = connectdb()->query($sqlupdate_shelf);

                    if (!$result5) {
                        die(mysqli_error(connectdb()));
                    }
                    else {
                        $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                        $result6 = connectdb()->query($sqldel_cart);

                        if (!$result6) {
                            die(mysqli_error(connectdb()));
                        } 
                        else {
                             // ตรวจสอบว่ามี session ที่เก็บอยู่หรือไม่
                               if (isset($_SESSION['coin'])) {
                                // ลบข้อมูล session 
                                unset($_SESSION["coin"]);
                                unset($_SESSION['total']);
                            }
                            echo '
                            <script>
                                sweetalerts("สั่งซื้อสำเร็จ!!","success","","mybook.php");
                            </script>
                                ';

                        }
                    }
                }
            }
        } else {
            $sqlinsert_shelf = "insert into bookshelf (bshelf_bookid,bshelf_cusid,bshelf_status)
            values ('$bookid','$cusid','0')";
            $result = connectdb()->query($sqlinsert_shelf);
            if (!$result) {
                die(mysqli_error(connectdb()));
            } else {
                $i++;

                if (!$result2) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid)
                    values ('$i','$lastreceiptid','$bookid')";
                    $result3 = connectdb()->query($sqlins_detail);

                    if (!$result3) {
                        die(mysqli_error(connectdb()));
                    } else {
                        $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                        where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                        $result4 = connectdb()->query($sqlupdate_shelf);

                        if (!$result4) {
                            die(mysqli_error(connectdb()));
                        }
                        else {
                            $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                            $result5 = connectdb()->query($sqldel_cart);

                            if (!$result5) {
                                die(mysqli_error(connectdb()));
                            } 
                            else {
                                // ตรวจสอบว่ามี session ที่เก็บอยู่หรือไม่
                            if (isset($_SESSION['coin'])) {
                                // ลบข้อมูล session 
                                unset($_SESSION["coin"]);
                                unset($_SESSION['total']);
                            }
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
