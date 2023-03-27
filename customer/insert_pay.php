<?php
include "function.php";
connectdb();
session_start();
if (isset($_GET['bookid']) && isset($_SESSION['cusid'])) {

    echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";

    $bookid = $_GET['bookid'];
    $cusid = $_SESSION['cusid'];
    $total = 0;

    $sqlbook_shelf = "select * from bookshelf
    where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid' and bshelf_status = '0'";
    $ex1 = connectdb()->query($sqlbook_shelf);

    if (isset($_SESSION['coin']) && isset($_GET['discount'])) {
        $coin = $_SESSION['coin'];
        $total = $_GET['discount'];
        $newcoin = floatval($coin);
        $newcoin -= $total;

    }
    elseif (isset($_SESSION['coin']) && isset($_GET['price'])){
        $coin = $_SESSION['coin'];
        $total = $_GET['price'];
        $newcoin = floatval($coin);
        $newcoin -= $total;
    }

    if ($ex1->num_rows > 0) {
        $ex2 = insertdata("cart", "cart_bookid,cart_cusid", "'$bookid','$cusid'");
       

        if ($ex2) {
            $i = 0;
            $bookarr = array();

            $sqlcart = "select * from cart inner join book on book_id = cart_bookid
            where cart_cusid = '$cusid'";
            $result = connectdb()->query($sqlcart);

            if ($result->num_rows > 0) {
                

                while ($row = $result->fetch_assoc()) {
                    $bookarr[] = $row['cart_bookid'];
                    
                }
                
                
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
                                } else {
                                    $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                                    $result6 = connectdb()->query($sqldel_cart);

                                    if (!$result6) {
                                        die(mysqli_error(connectdb()));
                                    } else {
                                        if (isset($_SESSION['coin'])) {
                                            // ลบข้อมูล session 
                                            unset($_SESSION["coin"]);
                                            
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
                                    } else {
                                        $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                                        $result5 = connectdb()->query($sqldel_cart);

                                        if (!$result5) {
                                            die(mysqli_error(connectdb()));
                                        } else {
                                            if (isset($_SESSION['coin'])) {
                                                // ลบข้อมูล session 
                                                unset($_SESSION["coin"]);
                                                
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
        } else {
            die(mysqli_error(connectdb()));
        }
    } else {
        $sqlinsert_shelf = "insert into bookshelf (bshelf_bookid,bshelf_cusid,bshelf_status)
        values ('$bookid','$cusid','0')";
        $ex3 = connectdb()->query($sqlinsert_shelf);

        if (!isset($ex3)) {
            die(mysqli_error(connectdb()));
        } else {
            $ex4 = insertdata("cart", "cart_bookid,cart_cusid", "'$bookid','$cusid'");

            if ($ex4) {
                $i = 0;
                $bookarr = array();

                $sqlcart = "select * from cart inner join book on book_id = cart_bookid
                where cart_cusid = '$cusid'";
                $result = connectdb()->query($sqlcart);

                if ($result->num_rows > 0) {
                    

                    while ($row = $result->fetch_assoc()) {
                        $bookarr[] = $row['cart_bookid'];
                    }
                    

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
                                    } else {
                                        $sqldel_cart = "delete from cart  where cart_cusid = '$cusid'";
                                        $result6 = connectdb()->query($sqldel_cart);

                                        if (!$result6) {
                                            die(mysqli_error(connectdb()));
                                        } else {
                                            if (isset($_SESSION['coin'])) {
                                                // ลบข้อมูล session 
                                                unset($_SESSION["coin"]);
                                                
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
                                        } else {
                                            $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                                            $result5 = connectdb()->query($sqldel_cart);
                                            // ตรวจสอบว่ามี session ที่เก็บอยู่หรือไม่

                                            if (!$result5) {
                                                die(mysqli_error(connectdb()));
                                            } else {
                                                if (isset($_SESSION['coin'])) {
                                                    // ลบข้อมูล session 
                                                    unset($_SESSION["coin"]);
                                                    
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
            } else {
                die(mysqli_error(connectdb()));
            }
        }
    }

    connectdb()->close();
}
