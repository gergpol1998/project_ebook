<?php
require_once __DIR__ . '/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/tmp',
    ]),
    'fontdata' => $fontData + [ // lowercase letters only in font key
        'sarabun' => [
            'R' => 'THSarabunNew.ttf',
            'I' => 'THSarabunNew Italic.ttf',
            'B' => 'THSarabunNew Bold.ttf',
            'BI' => 'THSarabunNew BoldItalic.ttf'
        ]
    ],
    'default_font' => 'sarabun'
]);

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
    <title>report</title>
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
                <div>รายงานของฉัน</div>
            </h2>
            <div class="d-flex justify-content-end">
                <a class="btn btn-success mb-4 me-2" href="promotion.php" role="button">
                    <h4>+โปรโมชั่น</h4>
                </a>

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
            if ($ex_round->num_rows > 0) {
                
                if ($checkdate === $currentdate) {
                    // Check whether the data has already been inserted
                    $sql = "SELECT date_day FROM date WHERE DATE_FORMAT(date_day,'%d/%m') = '$currentdate'";
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
            else {
                $lastdateid = dateid();
                $sqlins_date = "insert into date (date_id,date_day)
                values ('$lastdateid',NOW())";
                $result = connectdb()->query($sqlins_date);
                if (!$result) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlins_round_date = "insert into round_date (rd_roundid,rd_dateid)
                    values ('001','$lastdateid')";
                    $result2 = connectdb()->query($sqlins_round_date);
                    if (!$result2) {
                        die(mysqli_error(connectdb()));
                    } else {
                        $sqlpub = "select pub_id,pub_round from publisher inner join customer on pub_cusid = cus_id
                        where pub_cusid = '$cusid'";
                        $ex_pub = connectdb()->query($sqlpub);
                        if ($ex_pub->num_rows > 0) {
                            $row = $ex_pub->fetch_assoc();
                            $pubid = $row['pub_id'];

                            if ($row['pub_round'] === NULL) {
                                $sqlup_pub = "update publisher set pub_round = '001'
                                where pub_id = '$pubid'";
                                $result3 = connectdb()->query($sqlup_pub);
                                if (!$result3) {
                                    die(mysqli_error(connectdb()));
                                }
                            }
                            else{
                                $sqlup_pub = "update publisher set pub_round = '001'
                                where pub_id = '$pubid'";
                                $result3 = connectdb()->query($sqlup_pub);
                            }
                        }
                    }
                }
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
                    <span class= 'text-danger'>เลือกได้อีกทีวันที่ 1 เดือนถัดไป</span>
                </form>
            <?php
            }
            ?>
        </div>
        <a class="btn btn-success mb-4 me-2" href="income.php" role="button">
            <h6>รายได้</h6>
        </a>

        <form action="report_bestselling_book.php" method="get">
            <div class="mb-3">
                <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </form>
        <?php
        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            // รับค่าช่วงเวลาจากฟอร์ม
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
            $cusid = $_SESSION['cusid'];

            $sqlpub = "select pub_id from publisher inner join customer on cus_id = pub_cusid
            where pub_cusid = '$cusid'";
            $ex_pub = connectdb()->query($sqlpub);
            if ($ex_pub->num_rows > 0) {
                $row = $ex_pub->fetch_assoc();
                $pubid = $row['pub_id'];

                $col = "*,count(recd_bookid) as total_quantity";
                $table = "book
                INNER JOIN receipt_detail ON book.book_id = receipt_detail.recd_bookid
                INNER JOIN receipt ON receipt.rec_id = receipt_detail.recd_recid
                INNER JOIN publisher ON publisher.pub_id = book.book_pubid
                INNER JOIN customer ON customer.cus_id = publisher.pub_cusid";
                $where = "DATE_FORMAT(rec_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' AND pub_id = '$pubid'
                GROUP BY recd_bookid";
                $sqlbook = select_where($col, $table, $where);
                if ($sqlbook->num_rows > 0) {
                    ob_start(); // เริ่มเก็บข้อมูลลงหน่วยความจำ

                    echo '<h2 class="text-center">รายการหนังสือขายดี</h2>';
                    echo '<table class="table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>รหัสหนังสือ</th>';
                    echo '<th>ชื่อหนังสือ</th>';
                    echo '<th>จำนวนที่ขายได้</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($row = $sqlbook->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['book_id'] . '</td>';
                        echo '<td>' . $row['book_name'] . '</td>';
                        echo '<td>' . $row['total_quantity'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    $html = ob_get_contents(); // เก็บลงไปในตัวแปร html
                    $mpdf->WriteHTML($html);
                    $mpdf->Output("MyReport.pdf");
                    ob_end_flush(); // สิ้นสุดการเก็บข้อมูลลงหน่วยความจำ
                    echo '<a class="btn btn-success mb-4" href="MyReport.pdf" role="button">โหลดรายงาน</a>';
                } else {
                    echo "ไม่พบข้อมูล";
                }
            }
        }
        connectdb()->close();
        ?>
    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>