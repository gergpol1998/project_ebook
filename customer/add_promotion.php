<?php
session_start();
include "function.php";
connectdb();
echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";
if (!isset($_SESSION["cusid"])) {
    echo '
        <script>
            sweetalerts("กรุณาลงชื่อเข้าใช้งานระบบก่อน!!","warning","","login.php");
        </script>
    ';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add promotion</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <script src="function.js"></script>
</head>


<body>
    <div class="container">
        <br><br>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5 bg-light text-dark">
                <br>
                <div class="alert alert-primary h4 text-center mb-4 mt-4 " role="alert">
                    เพิ่มโปรโมชั่น
                </div>
                <form method="POST" action="insert_promotion.php">
                    <label>ชื่อโปรโมชั่น</label>
                    <input type="text" name="proname" class="form-control" required>
                    <label>ส่วนลด</label>
                    <input type="number" name="discount" class="form-control" required>
                    <label>วันที่เริ่มต้น</label>
                    <input type="date" name="sdate" class="form-control" required>
                    <label>วันที่สิ้นสุด</label>
                    <input type="date" name="edate" class="form-control" required>
                    <label>เลือกหนังสือ</label><br>
                    <?php
                    //query typebook
                    $result = select("book_id,book_name", "book");
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <input type="checkbox" name="book[]" value="<?= $row['book_id'] ?>">
                        <?= $row['book_name'] ?>
                    <?php
                    }
                    ?><br><br>
                    <input type="submit" class="btn btn-primary" name="submit" value="เพิ่มโปรโมชั่น">
                    <input type="reset" class="btn btn-danger" name="cancel" value="ยกเลิก"><br><br>
                </form>
            </div>
        </div>
    </div>
</body>
</html>