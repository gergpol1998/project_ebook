<?php
session_start();
echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (!isset($_SESSION['cusid'])) {
    echo '
        <script>
            sweetalerts("กรุณาลงชื่อเข้าใช้งานก่อน!!","warning","","login.php");
        </script>
        ';
} else {
    $cusid = $_SESSION['cusid'];
      
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <?php
    include "nav.php";
    ?>
    <div class="container px-4 px-lg-5 mt-3">
        <div class="row">
            <div class="col-md-10">
                <h2 class="text-center my-3">ตะกร้าสินค้า</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>รูปภาพ</th>
                            <th>รหัส</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $total = 0;
                        $sum1 = 0;
                        $sum2 = 0;


                        $sqlcart = "select *from book inner join cart on book_id = cart_bookid
                        where book_status = '2' and cart_cusid = '$cusid'";
                        $result = connectdb()->query($sqlcart);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $bookid = $row['book_id'];
                                $price = $row['book_price'];
                                
                                $sqlcart_pro = "select *,book_price - pro_discount as discount
                                from promotion inner join bookpro on pro_id = bpro_proid 
                                inner join book on bpro_bookid = book_id
                                where bpro_bookid = '$bookid' and book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY";
                                $ex_cartpro = connectdb()->query($sqlcart_pro);
                                

                            ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td><img src="<?php echo $row['book_cover'] ?>" class="card-img-top" width="80px" height="100px"></td>
                                    <td>
                                        <?= $row['book_id'] ?>
                                    </td>
                                    <td>
                                        <?= $row['book_name'] ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($ex_cartpro->num_rows > 0){
                                            $row2 = $ex_cartpro->fetch_assoc();
                                            echo "<del class='text-danger'>$row[book_price]</del>";
                                            echo number_format($row2['discount'], 2);
                                            $sum1 = $sum1 + $row2['discount'];
                                            //echo $sum1;
                                        }
                                        else{
                                            echo number_format($price, 2);
                                            $sum2 = $sum2 + $price;
                                            
                                        }
                                        if (isset($sum1)&&isset($sum2)){
                                            $total = $sum1+$sum2;
                                        }
                                        else{
                                            $total = $total + $sum2;
                                        }
                                        
                                        ?>
                                        
                                    </td>
                                    <script>
                                        function canclebook(cancle) {
                                            let agreecancle = confirm("ต้องการลบ");
                                            if (agreecancle) {
                                                window.location = cancle;
                                            }
                                        }
                                    </script>
                                    <td>

                                        <a onclick="canclebook(this.href); return false;" href="remove_cart.php?bookid=<?php echo $row['book_id'] ?>&act=remove"><button type='button' class='btn btn-danger'>ลบ</button></a>

                                    </td>

                                </tr>
                                <?php
                                $i++;
                                
                                ?>
                        </tbody>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="text-end" colspan="4" id="nn">ราคารวมสุทธิ</td>
                                <td class="text-center"><b class="text-danger">
                                        <?php echo number_format($total, 2) ?>
                                    </b></td>
                                <td>เหรียญ</td>
                            </tr>
                            <tr>
                            <td class="text-end" colspan="4"><a href='index.php'><button type='button' class="btn btn-outline-secondary">เลือกสินค้า</button></a></td>
                                <?php
                                    $sqlcus = select_where("cus_coin", "customer", "cus_id = '$cusid'");
                                    $sqlprice = "select book_id,book_price from book
                                    where book_id = '$bookid'";
                                    $ex_price = connectdb()->query($sqlprice);
                                    if ($sqlcus->num_rows > 0 ) {
                                        $row = $ex_price->fetch_assoc();
                                        $row2 = $sqlcus->fetch_assoc();
                                        if ($row2['cus_coin'] < $total) {
                                            echo '<script>
                                                function checkcoin(mycoin) {
                                                    let conf = confirm("เหรียญไม่พอต้องเติมเหรียญก่อน");
                                                    if (conf) {
                                                        window.location = mycoin;
                                                    }
                                                }
                                            </script>';
                                        
                                            echo "<td class='text-center'><a <a onclick='checkcoin(this.href); return false;' href='add_coin.php'><button type='button' class='btn btn-primary'>ชำระเงิน</button></a></td>";
                                        } else {
                                            $_SESSION['coin'] = $row2['cus_coin'];
                                            $_SESSION['total'] = $total;
                                ?>
                            <td class="text-center"><a href='insert_receipt.php'><button type='button' class="btn btn-primary">ชำระเงิน</button></a></td>
                    <?php
                        }
                    }
                
                                }
                ?>
                <td><a href='cancle_cart.php?act=cancle'><button type='button' class='btn btn-danger'>ยกเลิกสินค้า</button></a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
    connectdb()->close();
    ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>