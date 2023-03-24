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
    $pubid = $_SESSION['cusid'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my promotion</title>
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
                <a class="btn btn-success mb-4 me-2" href="add_promotion.php" role="button">
                    <h4>+เพิ่มโปรโมชั่น</h4>
                </a>

                <a class="btn btn-primary mb-4" href="add_book.php" role="button">
                    <h4>+เพิ่มผลงาน</h4>
                </a>
            </div>
        </div>
        <div class="alert alert-primary h4 text-start mb-4 mt-4 " role="alert">
            รายรับที่ได้ 0
        </div>
        <h4>
            <div>โปรโมชั่นของฉัน</div>
        </h4>
        <div class="mb-3">
            <a href="promotion.php"><button type="button" class="btn btn-outline-success">โปรโมชั่นที่ใช้งานได้</button></a>
            <a href="end_promotion.php"><button type="button" class="btn btn-outline-success">โปรโมชั่นที่หมดอายุ</button></a>
        </div>
        
        <div class="row">
            <div class="col-md-10">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ชื่อโปรโมชั่น</th>
                            <th>ส่วนลด</th>
                            <th>วันที่เริ่มสร้าง</th>
                            <th>วันที่สิ้นสุด</th>
                            <th>ชื่อหนังสือ</th>
                            <th>แก้ไข</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlpro = "select pro_id,book_id,pro_name,pro_discount,pro_sdate,pro_edate,book_name 
                        from promotion inner join book_promotion on pro_id = bp_proid
                        inner join book on bp_bookid = book_id
                        where pro_pubid = '$pubid'and pro_edate < CURDATE()+ INTERVAL 1 DAY";
                        $result = connectdb()->query($sqlpro);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                
                        ?>
                                <tr>
                                    <td>
                                        <?php echo $row['pro_name']; ?>
                                    </td>
                                    <td><?php echo $row['pro_discount']?></td>
                                    <td>
                                        <?= $row['pro_sdate'] ?>
                                    </td>
                                    <td>
                                        <?= $row['pro_edate'] ?>
                                    </td>
                                    <td>
                                        <?php echo $row['book_name']?>
                                    </td>
                                    <td>
                                    <a href="edit_promotion.php?proid=<?php echo $row['pro_id'] ?>&bookid=<?php echo $row['book_id']?>"><button type='button' class='btn btn-warning'>แก้ไข</button></a>
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

                                        <a onclick="canclebook(this.href); return false;" href="remove_promotion.php?proid=<?php echo $row['pro_id']?>&bookid=<?php echo $row['book_id']?>"><button type='button' class='btn btn-danger'>ลบ</button></a>

                                    </td>

                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<?php
connectdb()->close();
?>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>