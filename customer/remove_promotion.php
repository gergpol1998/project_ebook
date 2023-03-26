<?php
include "function.php";
connectdb();

echo "<script src='https://code.jquery.com/jquery-3.6.1.min.js'></script>";
echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>";
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (isset($_GET['proid'])) {
    $proid = $_GET['proid'];
    $bookid = $_GET['bookid'];

    $sqlselbook = "select book_id from book where book_id = '$bookid'";
    $result = connectdb()->query($sqlselbook);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $sqlselbookpro = "select bpro_bookid from bookpro where bpro_bookid = '$bookid'";
        $result2 = connectdb()->query($sqlselbookpro);
        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            if ($row['book_id'] === $row2['bpro_bookid']) {
                $sqldel_bookpro = "delete from bookpro where bpro_bookid = '$bookid' and bpro_proid = '$proid'";
                $result3 = connectdb()->query($sqldel_bookpro);

                $sqlselpro = "SELECT pro_id FROM promotion
                inner join bookpro on pro_id = bpro_proid and pro_id = '$proid'";
                $result4 = connectdb()->query($sqlselpro);
                if ($result4->num_rows > 0) {
                        echo '
                    <script>
                        sweetalerts("ลบข้อมูลสำเร็จ!!","success","","promotion.php");
                    </script>
                    ';
                } 
                else {
                    $sqldelpro = "delete from promotion where pro_id = '$proid'";
                    $result5 = connectdb()->query($sqldelpro);
                    if (!$result5) {
                        die(mysqli_error(connectdb()));
                    } else {
                        echo '
                    <script>
                        sweetalerts("ลบข้อมูลสำเร็จ!!","success","","promotion.php");
                    </script>
                    ';
                    }
                }
            }
        }
    }
}
?>
