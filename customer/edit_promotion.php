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
else{
    $proid = $_GET['proid'];
    $bookid = $_GET['bookid'];

    $sqlpro = "select pro_id,book_id,pro_name,pro_discount,pro_sdate,pro_edate,book_name 
    from promotion inner join book_promotion on pro_id = bp_proid
    inner join book on bp_bookid = book_id
    where pro_id = '$proid'";
    $result = connectdb()->query($sqlpro);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit promotion</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <script src="function.js"></script>
</head>


<body>
    <div class="container">
        <br><br>
        <div class="row d-flex justify-content-center">
        <?php
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            ?>
            <div class="col-md-5 bg-light text-dark">
                <br>
                <div class="alert alert-primary h4 text-center mb-4 mt-4 " role="alert">
                    แก้ไขโปรโมชั่น
                </div>
                <form method="POST" action="update_promotion.php">
                    <label>ชื่อโปรโมชั่น</label>
                    <input type="text" name="proid" class="form-control" required  hidden value="<?php echo $row['pro_id'] ?>">
                    <input type="text" name="proname" class="form-control" value="<?php echo $row['pro_name']?>" required>
                    <label>ส่วนลด</label>
                    <input type="number" name="discount" class="form-control" value="<?php echo $row['pro_discount']?>" required>
                    <label>วันที่เริ่มต้น</label>
                    <input type="date" name="sdate" class="form-control" value="<?php echo $row['pro_sdate']?>" required>
                    <label>วันที่สิ้นสุด</label>
                    <input type="date" name="edate" class="form-control" value="<?php echo $row['pro_edate']?>" required>
                    <label>เลือกหนังสือ</label><br>
                    <?php
                    //query book
                    $sqlbookid = select("book_id", "book ORDER BY book_id");
                    $sqlbookname = select("book_name", "book");
                    $sqlbook_pro = select_where("bp_bookid", "book_promotion", "bp_bookid='$bookid'");
                    $bookarr = array();
                    while ($row = $sqlbookid->fetch_assoc()) {
                        $bookarr[] = $row['book_id'];
                        
                    }
                    $books = array();
                    while ($row2 = $sqlbook_pro->fetch_assoc()) {
                        $books[] = $row2['bp_bookid'];
                       
                    }
                    foreach ($bookarr as $value) {
                        // Check if the current value is in the database result
                        $row3 = $sqlbookname->fetch_assoc();
                        $bookname = $row3['book_name'];
                        
                        $isChecked = in_array($value, $books) ? 'checked' : '';
                        // Output the checkbox with the pre-selected value and readonly attribute
                        echo '<input type="checkbox" disabled value="' . $value . '" ' . $isChecked . '> ' . $bookname;
                    }                                        
                }
                connectdb()->close();
                    ?><br><br>
                    <input type="submit" class="btn btn-primary" name="submit" value="แก้ไขโปรโมชั่น">
                    <input type="reset" class="btn btn-danger" name="cancel" value="ยกเลิก"><br><br>
                </form>
            </div>
        </div>
    </div>
</body>
</html>