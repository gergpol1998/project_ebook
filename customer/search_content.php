<?php
session_start();
$bookid = $_GET['bookid'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
        <?php
        $sqlbookname = "select book_name from book
        where book_status = '2' and book_id = '$bookid'";
        $ex_bookname = connectdb()->query($sqlbookname);
        if ($ex_bookname->num_rows > 0){
            $row = $ex_bookname->fetch_assoc();
        }
        ?>
        <h3><?php echo $row['book_name']?></h3>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 ">
            <?php
            $col = "*";
            $table = "book inner join publisher on pub_id = book_pubid";
            $where = "book_status = '2' and book_id = '$bookid' ORDER BY book_app DESC LIMIT 10";
            $sqlbook = select_where($col, $table, $where);
            if ($sqlbook->num_rows > 0) {
                while ($row = $sqlbook->fetch_assoc()) {
                    $bookdate = $row['book_app'];
                    $passdate = strtotime("+4 days", strtotime($bookdate)); // วันหมดอายุ
                    $currentdate = time(); // วันที่ปัจจุบัน
            ?>
                    <div class="col sm-3">
                        <div class="text-center mb-3">
                            <img src="<?php echo $row['book_cover'] ?>" class="card-img-top" width="200px" height="250px">

                            <?php
                            if ($currentdate <= $passdate) {
                                echo "<h6 class='card-title text-center text-danger'>NEW</h6>";
                            } else {
                                echo "";
                            }
                            ?>
                            <h5 class="card-title text-center">ชื่อเรื่อง</h5>
                            <h5 class="card-title text-center text-success"><?php echo $row['book_name'] ?></h5>
                            <h5 class="card-title text-center">ราคา</h5>
                            <h5 class="card-text text-center text-danger"><?php echo number_format($row['book_price'], 2) ?> <i class="fas fa-coins"></i></h5>
                            <h5 class="card-title text-center">ผู้เผยแพร่</h5>
                            <h5 class="card-text text-center text-success"><?php echo $row['pub_name'] ?></h5>
                            <?php
                            if (isset($cusid)) {
                                
                                $sqlcus = select_where("cus_coin", "customer", "cus_id = '$cusid'");
                                if ($sqlcus->num_rows > 0) {
                                    $row2 = $sqlcus->fetch_assoc();

                                    $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                        if ($sqlcheck->num_rows > 0){
                                            echo '<button class="btn btn-danger mb-2" disabled>ชำระเงิน</button>';
                                            
                                        }
                                        else{
                                            if ($row2['cus_coin'] < $row['book_price']) {
                                                echo '<script>
                                                            function checkcoin(mycoin) {
                                                                let conf = confirm("เหรียญไม่พอต้องเติมเหรียญก่อน");
                                                                if (conf) {
                                                                    window.location = mycoin;
                                                                }
                                                            }
                                                        </script>';
                                                echo '<a onclick="checkcoin(this.href); return false;" href="add_coin.php" class="btn btn-danger mb-2">ชำระเงิน</a>';
        
                                                
                                            } 
                                            else {
                                                $_SESSION['coin'] = $row2['cus_coin'];
                                                $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                                $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '" . $row['book_id'] . "'");
                                                if ($sqlcheck->num_rows > 0 || $sqlcart->num_rows > 0){ 
                                    ?>          
                                                <button class="btn btn-danger mb-2" disabled>ชำระเงิน</button>
                                        <?php
                                                }
                                                else{
                                                    
                                                
                                                ?>
                                                <a href="insert_pay.php?bookid=<?php echo $row['book_id'] ?>&price=<?php echo $row['book_price']?>" class="btn btn-danger mb-2">ชำระเงิน</a>
                                                <?php
                                                }
                                            }
                                        }
                                        }
                                    
                                ?>
                                <?php
                                $sql = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '" . $row['book_id'] . "'");
                                if ($sql->num_rows > 0 || $sqlcart->num_rows > 0) {

                                ?>
                                    <button class="btn btn-primary mb-2" disabled>เพิ่มเข้าตะกร้า</button>
                                <?php
                                } else {

                                ?>
                                    <a href="insert_cart.php?bookid=<?php echo $row['book_id'] ?>" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                <?php
                                }
                                $sqlshelf = "select * from bookshelf
                                        where bshelf_bookid = '" . $row['book_id'] . "' and bshelf_cusid = '$cusid'";
                                $result = connectdb()->query($sqlshelf);
                                if ($result->num_rows > 0) {

                                ?>
                                    <button class="btn btn-warning mb-2" disabled>เพิ่มเข้าชั้นหนังสือ</button>
                                <?php
                                } else {
                                ?>
                                    <a href="insert_shelf.php?bookid=<?php echo $row['book_id'] ?>" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                <?php
                                }
                                ?>

                            <?php
                            } else {

                            ?>
                                <script>
                                    function register(mypage2) {
                                        let conf = confirm("ต้องเป็นสมาชิกก่อน");
                                        if (conf) {
                                            window.location = mypage2;
                                        }
                                    }
                                </script>
                                <a onclick="register(this.href); return false;" href="register.php" class="btn btn-danger mb-2">ชำระเงิน</a>
                                <a onclick="register(this.href); return false;" href="register.php" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                <a onclick="register(this.href); return false;" href="register.php" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                            <?php
                            }
                            ?>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">รายละเอียด</button>
                            <!-- Modal -->
                            <div class="modal fade" id="<?php echo $row['book_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">รายละเอียด</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                                            <?php
                                            echo "<h5>ชื่อเรื่อง</h5>";
                                            echo "<h4>" . $row['book_name'] . "</h4>";
                                            echo "<h5>ราคา</h5>";
                                            echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) ." <i class='fas fa-coins'></i></h4>";
                                            echo "<h5>เนื้อเรื่องย่อ</h5>";
                                            echo "<p>" . $row['book_summary'] . "</p>";
                                            echo "<h5>ผู้เผยแพร่</h5>";
                                            echo "<h4>" . $row['pub_name'] . "</h4>";
                                            echo "<a href='testread.php?bookid=".$row['book_id']."'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
                                            echo "<a href='mypage.php?pubid=".$row['book_pubid']."'><button class='btn btn-success'>หน้าร้าน</button></a>";
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            else{
                echo "<h2>ไม่มีหนังสือมาใหม่</h2>";
            }
            connectdb()->close();
            ?>
        </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>