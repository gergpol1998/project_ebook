<?php
if($_POST['submit']){
    include "function.php";
    connectdb();
    session_start();
    $pubid = $_SESSION['cusid'];
    $penname = $_POST['penname'];
    $bankid = $_POST['bankid'];
    $pubacc = $_POST['pubacc'];

    $values = "pub_id,pub_penname,pub_date,pub_bid,pub_account";
    $data = "'$pubid','$penname',NOW(),'$bankid','$pubacc'";
    $result = insertdata("publisher",$values,$data);

    echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";

    if ($result) {
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","index.php");
            </script>
            ';
    } else {
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลไม่สำเร็จ!!","warning","","publis_register.php");
            </script>
            ';
    }
    
    connectdb()->close();
}
?>