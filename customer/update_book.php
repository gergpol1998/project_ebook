<?php
include "function.php";
connectdb();
session_start();
$pub_id = $_SESSION["cusid"];

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";
if($_POST['submit']){
    $bookid = $_POST['bid'];
    $bookname = $_POST['bname'];
    $summary = $_POST['summary'];
    $price = $_POST['price'];
    $type_book = $_POST['type_book'];

    //upload cover
    $file = $_FILES['file1'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    //upload pdf
    $file2 = $_FILES['file2'];
    $file_name2 = $file2['name'];
    $file_tmp2 = $file2['tmp_name'];
    $file_size2 = $file2['size'];
    $file_error2 = $file2['error'];

    //upload test pdf
    $file3 = $_FILES['file3'];
    $file_name3 = $file3['name'];
    $file_tmp3 = $file3['tmp_name'];
    $file_size3 = $file3['size'];
    $file_error3 = $file3['error'];

    // Get the current month and year
    $current_month = date('m');
    $current_year = date('Y');

    // Create a new folder using the current month and year
    $new_folder1 = 'uploads/'.$pub_id.'/'.$current_year.'/'.$current_month;
    if (!file_exists($new_folder1)) {
        mkdir($new_folder1, 0777, true);
    }

    // Create a new folder using the current month and year
    $new_folder2 = 'pdf/'.$pub_id.'/'.$current_year.'/'.$current_month;
    if (!file_exists($new_folder2)) {
        mkdir($new_folder2, 0777, true);
    }

    // Create a new folder using the current month and year
    $new_folder3 = 'test/'.$pub_id.'/'.$current_year.'/'.$current_month;
    if (!file_exists($new_folder3)) {
        mkdir($new_folder3, 0777, true);
    }
    // Check for file errors
    if ($file_error === 0 && $file_error2 === 0 && $file_error3 === 0) {
        // Check the file type
        $file_type1 = exif_imagetype($file_tmp);
        $allowed_types1 = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);

        // Check the file type
        $file_type2 = mime_content_type($file_tmp2);
        if ($file_type2 !== 'application/pdf') {
            die('Error: Only PDF files are allowed.');
        }

        // Check the file type
        $file_type3 = mime_content_type($file_tmp3);
        if ($file_type3 !== 'application/pdf') {
            die('Error: Only PDF files are allowed.');
        }


        if (in_array($file_type1, $allowed_types1) && isset($file_type2) && isset($file_type3)) {
            // Update the file destination to the new folder
            $file_destination1 = $new_folder1.'/'.$file_name;
            move_uploaded_file($file_tmp, $file_destination1);

            $file_destination2 = $new_folder2.'/'.$file_name2;
            move_uploaded_file($file_tmp2, $file_destination2);

            $file_destination3 = $new_folder3.'/'.$file_name3;
            move_uploaded_file($file_tmp3, $file_destination3);
            
            // update the new file path into the database
            $col = "book_id = '$bookid',book_name='$bookname',book_cover = '$file_destination1',book_content = '$file_destination2'
            ,book_test = '$file_destination3',book_summary = '$summary',book_price = '$price'";
            
            $where = "book_id = '$bookid'";
            $result = updatedata("book",$col,$where);
        }
        else {
            echo "Invalid file type. Please upload a JPEG, PNG.";
        }
    }
    if (!isset($result)) {
        die(mysqli_error(connectdb()));
    } else {
        foreach ($type_book as $type_books) {
            $result2 = deletedata("book_type","btype_bookid = '$bookid'");
        }
        foreach($type_book as $type_books) {
            $col_type = "btype_bookid,btype_typeid";
            $values_type = "'$bookid','$type_books'";
            $result3 = insertdata("book_type",$col_type,$values_type);
        }
        if (!isset($result3)) {
            die(mysqli_error(connectdb()));
        }
        else{
            echo '
            <script>
                sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","draf.php");
            </script>
                ';
        }
        
    }
}
?>