<?php
include "function.php";
connectdb();


echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";
if($_POST['submit']){
    $proid = $_POST['proid'];
    $proname = $_POST['proname'];
    $discount = $_POST['discount'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];

    $sqlupdate_pro = "update promotion set pro_name = '$proname',pro_discount = '$discount',
    pro_sdate = '$sdate',pro_edate = '$edate'
    where pro_id = '$proid'";
    $result = connectdb()->query($sqlupdate_pro);
    if (!$result) {
        die(mysqli_error(connectdb()));
    } 
    else {
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","promotion.php");
            </script>
                ';
    }
}
?>