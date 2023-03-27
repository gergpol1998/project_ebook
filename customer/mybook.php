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
    <title>my_shelf</title>
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
        <h4>
            <div>ชั้นหนังสือ</div>
        </h4>
        <div class="mb-3">
            <a href="shelf.php"><button type="button" class="btn btn-outline-success">ยังไม่ได้เป็นเจ้าของ</button></a>
            <a href="mybook.php"><button type="button" class="btn btn-outline-success">เป็นเจ้าของ</button></a>
        </div>
        <?php
            $sqlbook = "select *
            from book inner join publisher on pub_id = book_pubid
            inner join bookshelf on book_id = bshelf_bookid
            where bshelf_cusid = '$cusid' and book_status = '2' and bshelf_status = '1'";
            $result = connectdb()->query($sqlbook);
        ?>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4">
        <?php
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
        ?>
        <div class="col sm-3">
            <div class="text-center mb-3">
                <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                <?php
                    echo "<h5>ชื่อเรื่อง</h5>";
                    echo "<h4>".$row['book_name']."</h4>";
                    echo "<h5>ผู้เผยแพร่</h5>";
                    echo "<h4>".$row['pub_name']."</h4>";
                ?>
                <!-- Button trigger modal -->
                <a href='readbook.php?bookid=<?php echo $row['book_id']?>'><button class='btn btn-danger'>อ่าน</button></a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">รายละเอียด</button>
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
                            echo "<h4>".$row['book_name']."</h4>";
                            echo "<h5>เนื้อเรื่องย่อ</h5>";
                            echo "<p>".$row['book_summary']."</p>";
                            echo "<h5>ผู้เผยแพร่</h5>";
                            echo "<h4>".$row['pub_name']."</h4>";
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
            echo "<h2>ไม่มีหนังสือในชั้น</h2>";
        }
        connectdb()->close();
        ?>         
        </div>
    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>