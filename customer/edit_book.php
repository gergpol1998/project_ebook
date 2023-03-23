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
if(isset($_GET['bookid'])){
    $bookid = $_GET['bookid'];
    $col = "book_name,book_cover,book_status,book_content,book_test,book_sumary,book_price";
    $table = "book";
    $where = "book_id = '$bookid'";
    $sqlbook = select_where($col, $table, $where);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>editbook</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>


<body>
    <div class="container">
        <br><br>
        <div class="row d-flex justify-content-center">
         <?php
          if($sqlbook->num_rows > 0){
               $row = $sqlbook->fetch_assoc();
          ?>
            <div class="col-md-5 bg-light text-dark">
                <br>
                <div class="alert alert-primary h4 text-center mb-4 mt-4 " role="alert">
                    แก้ไขหนังสือ
                </div>
                <form method="POST" action="update_book.php" enctype="multipart/form-data">

                    <label>ชื่อหนังสือ</label>
                    <input type="text" name="bid" class="form-control" required placeholder="name" hidden value="<?php echo $bookid ?>">
                    <input type="text" name="bname" class="form-control" required placeholder="name" value="<?php echo $row['book_name'] ?>">
                    <?php
          }
                    ?>
                    <label>หน้าปก</label>
                    <input type="file" name="file1" class="form-control" required>
                    <p class="text-danger">upload a JPEG, PNG</p>
                    <label>เนื้อหา</label>
                    <input type="file" name="file2" class="form-control" required>
                    <p class="text-danger">upload a PDF</p>
                    <label>ทดลองอ่าน</label>
                    <input type="file" name="file3" class="form-control" required>
                    <p class="text-danger">upload a PDF</p>
                    <label>หมวดหมู่</label><br>
                    <?php
                    //query typebook
                    $sqltypeid = select("type_id", "typebook");
                    $sqltypename = select("type_name", "typebook");
                    $sqlbook_type = select_where("bt_typeid","book_type","bt_bookid='$bookid'");
                    $typearr = array();
                    while ($row = $sqltypeid->fetch_assoc()){
                        $typearr[]=$row['type_id'];
                    }
                    $typeid = array();
                    while ($row2 = $sqlbook_type->fetch_assoc()) {
                        $typeid[] = $row2['bt_typeid'];
                    }
                    foreach ($typearr as $value){
                        // Check if the current value is in the database result
                        $row3 = $sqltypename->fetch_assoc();
                        $typename = $row3['type_name'];

                        $isChecked = in_array($value, $typeid) ? 'checked' : '';
                        // Output the checkbox with the pre-selected value
                        echo '<input type="checkbox" name="type_book[]" value="' . $value . '" ' . $isChecked . '> ' . $typename;
                    }
                    ?>
                    <br>
                    <?php
                    $sqlbooks = select_where($col, $table, $where);
                    $row4 = $sqlbooks->fetch_assoc();
                    ?>
                    <label>เรื่องย่อ</label>
                    <textarea name="summary" class="form-control" required placeholder="summary"><?php echo $row4['book_sumary']?></textarea>
                    <label>ราคา</label>
                    <input type="number" name="price" class="form-control" required placeholder="price" value="<?php echo number_format($row4['book_price'],2)?>"><br>
                    
                    <div style="text-align: center;">
                        <input type="submit" class="btn btn-primary" name="submit" value="บันทึกข้อมูล">
                        <input type="reset" class="btn btn-danger" name="cancel" value="ยกเลิก"> <br><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<?php
connectdb()->close();
?>
</html>
