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
    <title>draf</title>
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

        <div class="d-flex justify-content-between">
            <h2>
                <div>ผลงานของฉัน</div>
            </h2>
            <div class="d-flex justify-content-end">
            <?php
                $sqlcheckpro = "select book_id from book
                inner join publisher on pub_id = book_pubid
                inner join customer on cus_id = pub_cusid
                where pub_cusid = '$cusid' and book_status = '2'";
                $ex_sqlcheckpro = connectdb()->query($sqlcheckpro);
                if ($ex_sqlcheckpro->num_rows > 0){
                    echo '<a class="btn btn-success mb-4 me-2" href="promotion.php" role="button">
                        <h4>โปรโมชั่น</h4>
                    </a>' ;
                }
                else{
                ?>
                <script>
                    function adds(mypage) {
                    let agree = confirm("ยังไม่มีหนังสือที่เผยแพร่");
                        if (agree) {
                        window.location = mypage;
                        }
                    }
                </script>
                <a class="btn btn-success mb-4 me-2" onclick="adds(this.href); return false;" href="my_work.php"><h4>โปรโมชั่น</h4></a>
                <?php
                }
                ?>

                <a class="btn btn-primary mb-4 me-2" href="add_book.php" role="button">
                    <h4>+เพิ่มผลงาน</h4>
                </a>

                <a class="btn btn-warning mb-4 me-2" href="report_bestselling_book.php" role="button">
                    <h4>ดูรายงาน</h4>
                </a>
            </div>
        </div>
        <div class="alert alert-primary h4 text-start mb-2 mt-4 " role="alert">
            <?php
            $sqlround = "select round_id from round inner join publisher on round_id = pub_round
            where pub_cusid = '$cusid'";
            $ex_round = connectdb()->query($sqlround);
            $currentdate = date("d/m");
            $checkdate = "01/" . date("m");
            $day = date("d");
            if ($ex_round->num_rows > 0) {
                
                if ($checkdate === $currentdate) {
                    // Check whether the data has already been inserted
                    $sql = "SELECT date_date FROM date WHERE date_date = '$day'";
                    $result = connectdb()->query($sql);
                    if ($result->num_rows === 0) {
            ?>
                        <form action="insert_round.php" method="POST">
                            <label>เลือกรอบรับเงิน</label>
                            <select name="round" class="form-select mb-2">
                                <?php
                                $sqlround = "select * from round";
                                $ex_round = connectdb()->query($sqlround);
                                if ($ex_round->num_rows > 0) {
                                    while ($row = $ex_round->fetch_assoc()) {
                                ?>
                                        <option value="<?php echo $row['round_id'] ?>"><?php echo $row['round_num'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <input type="submit" class="btn btn-primary" name="submit" value="เลือก">
                        </form>

                    <?php
                    }
                    else{
                        echo '<form action="insert_round.php" method="POST">';
                            echo '<label>เลือกรอบรับเงิน</label>';
                            echo '<select name="round" class="form-select mb-2" disabled>';
                                $sqlround = "select * from round";
                                $ex_round = connectdb()->query($sqlround);
                                if ($ex_round->num_rows > 0) {
                                    while ($row = $ex_round->fetch_assoc()) {
                                
                                        echo '<option value="'.$row["round_id"].'">'.$row['round_num'].'</option>';
                                    }
                                }
                                
                            echo '</select>';
                            echo '<input type="submit" class="btn btn-primary" name="submit" value="เลือก" disabled>';
                        echo '</form>';
                        echo "<span class= 'text-danger'>เลือกได้อีกทีวันที่ 1 เดือนถัดไป</span>";
                    }
                }
                
                else{

                ?>  
                    <form action="insert_round.php" method="POST">
                        <label>เลือกรอบรับเงิน</label>
                        <select name="round" class="form-select mb-2" disabled>
                            <?php
                            $sqlround = "select * from round";
                            $ex_round = connectdb()->query($sqlround);
                            if ($ex_round->num_rows > 0) {
                                while ($row = $ex_round->fetch_assoc()) {

                            ?>
                                    <option value="<?php echo $row['round_id'] ?>"><?php echo $row['round_num'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <input type="submit" class="btn btn-primary" name="submit" value="เลือก" disabled>
                    </form>
                    <span class= 'text-danger'>เลือกได้อีกทีวันที่ 1 เดือนถัดไป</span>
                
                <?php
                }
                
            }
            ?>
        </div>
        <a class="btn btn-success mb-4 me-2" href="income.php" role="button">
            <h6>รายได้</h6>
        </a>
        <h4>
            <div>หนังสือของฉัน</div>
        </h4>
        <div class="mb-3">
            <a href="my_work.php"><button type="button" class="btn btn-outline-success">อนุมัติ</button></a>
            <a href="draf.php"><button type="button" class="btn btn-success">ฉบับร่าง</button></a>
            <a href="waitapp.php"><button type="button" class="btn btn-outline-success">รออนุมัติ</button></a>
        </div>
        <form method="POST" class="form-inline d-flex">
            <input class="form-control me-2" id="search3" type="text" placeholder="ชื่อหนังสือ/ผู้เผยแพร่/หมวดหมู่">
        </form>
        <div class="list-group list-group-item-action" id="content3"></div>


        <script>
            $(document).ready(function() {
                $('#search3').keyup(function() {
                    var Search = $('#search3').val(); // getvalue

                    if (Search != '') {
                        $.ajax({
                            url: "search_draf.php",
                            method: "POST",
                            data: {
                                search: Search
                            },
                            success: function(data) {
                                $('#content3').html(data);
                            }
                        })
                    } else {
                        $('#content3').html('');
                    }
                });
            });
        </script>
        <?php
        $col = "*";
        $table = "book inner join publisher on pub_id = book_pubid
        inner join customer on cus_id = pub_cusid";
        $where = "pub_cusid = '$cusid' and book_status = '0' ORDER BY book_dateup DESC";
        $sqlbook = select_where($col, $table, $where);

        ?>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4">
            <?php
            if ($sqlbook->num_rows > 0) {
                while ($row = $sqlbook->fetch_assoc()) {
                    $status = $row['book_status'];
                    if ($status === '0') {
                        $status = 'ฉบับร่าง';
                    }
            ?>
                    <div class="col sm-3">
                        <div class="text-center mb-3">
                            <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                            <?php
                            echo "<h4 class= 'text-success'>$status</h4>";
                            echo "<h5>ชื่อเรื่อง</h5>";
                            echo "<h4>" . $row['book_name'] . "</h4>";
                            echo "<h5>ราคา</h5>";
                            echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                            echo "<h5>ผู้เผยแพร่</h5>";
                            echo "<h4>" . $row['pub_name'] . "</h4>";
                            ?>
                            <!-- Button trigger modal -->
                            <a href='readbook.php?bookid=<?php echo $row['book_id'] ?>'><button class='btn btn-danger'>อ่าน</button></a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">เรื่องย่อ</button>
                            <a href="edit_book.php?bookid=<?php echo $row['book_id'] ?>"><button type="button" class="btn btn-warning">แก้ไข</button></a>
                            <script>
                                function sendbook(mydraf) {
                                    let agreesend = confirm("ส่งงาน");
                                    if (agreesend) {
                                        window.location = mydraf;
                                    }
                                }
                            </script>
                            <a onclick="sendbook(this.href); return false;" href="waitapp.php?bookid=<?php echo $row['book_id'] ?>"><button type="button" class="btn btn-success">ส่ง</button></a>

                            <script>
                                function deleted(mydraf) {
                                    let agree = confirm("ยืนยันการลบ");
                                    if (agree) {
                                        window.location = mydraf;
                                    }
                                }
                            </script>
                            <a onclick="deleted(this.href); return false;" href="delete_book.php?bookid=<?php echo $row['book_id'] ?>"><button type="button" class="btn btn-danger">ลบ</button></a>
                            <!-- Modal -->
                            <div class="modal fade" id="<?php echo $row['book_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">เรื่องย่อ</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                                            <?php
                                            echo "<h4 class= 'text-success'>$status</h4>";
                                            echo "<h5>ชื่อเรื่อง</h5>";
                                            echo "<h4>" . $row['book_name'] . "</h4>";
                                            echo "<h5>ราคา</h5>";
                                            echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                                            echo "<h5>เนื้อเรื่องย่อ</h5>";
                                            echo "<textarea class='form-control'>" . $row['book_summary'] . "</textarea>";
                                            echo "<h5>ผู้เผยแพร่</h5>";
                                            echo "<h4>" . $row['pub_name'] . "</h4>";
                                            echo "<a href='testread.php?bookid=" . $row['book_id'] . "'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
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
            connectdb()->close();
            ?>
        </div>
    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>