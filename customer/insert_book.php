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

if (isset($_POST['submit'])) {
    //query lastid
    $lastbookid = autoid('BOOK-', 'book_id', 'book', '00001');

    $bname = $_POST['bname'];
    $summary = $_POST['summary'];
    $price = $_POST['price'];
    $tag = $_POST['tag'];
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
    $new_folder1 = 'uploads/' . $pub_id . '/' . $current_year . '/' . $current_month;
    if (!file_exists($new_folder1)) {
        mkdir($new_folder1, 0777, true);
    }

    // Create a new folder using the current month and year
    $new_folder2 = 'pdf/' . $pub_id . '/' . $current_year . '/' . $current_month;
    if (!file_exists($new_folder2)) {
        mkdir($new_folder2, 0777, true);
    }

    // Create a new folder using the current month and year
    $new_folder3 = 'test/' . $pub_id . '/' . $current_year . '/' . $current_month;
    if (!file_exists($new_folder3)) {
        mkdir($new_folder3, 0777, true);
    }

    if ($file_error === 0 && $file_error2 === 0 && $file_error3 === 0) {
        // Check the file type
        $file_type1 = exif_imagetype($file_tmp);
        $allowed_types1 = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);

        // Check the file type
        $file_type2 = mime_content_type($file_tmp2);
        if ($file_type2 !== 'application/pdf') {
            echo 'Error: Only PDF files are allowed.';
        }

        // Check the file type
        $file_type3 = mime_content_type($file_tmp3);
        if ($file_type3 !== 'application/pdf') {
            echo 'Error: Only PDF files are allowed.';
        }


        if (in_array($file_type1, $allowed_types1) && isset($file_type2) && isset($file_type3)) {
            // Update the file destination to the new folder
            $file_destination1 = $new_folder1 . '/' . $file_name;
            move_uploaded_file($file_tmp, $file_destination1);


            $file_destination2 = $new_folder2 . '/' . $file_name2;
            move_uploaded_file($file_tmp2, $file_destination2);

            $file_destination3 = $new_folder3 . '/' . $file_name3;
            move_uploaded_file($file_tmp3, $file_destination3);

            // Insert the new file path into the database
            $col = "book_id,book_price,book_sumary,book_content,
            book_test,book_dateapp,book_dateup,book_name,book_status,book_cover,
            book_emp,book_pubid";

            $values = "'$lastbookid','$price','$summary','$file_destination2',
            '$file_destination3',NULL,NOW(),'$bname','1','$file_destination1',
            NULL,'$pub_id'";
            $result = insertdata("book", $col, $values);
        } else {
            echo "Invalid file type. Please upload a JPEG, PNG.";
        }
    }

    if (!isset($result)) {
        die(mysqli_error(connectdb()));
    } else {
        foreach ($tag as $tags) {
            $lasttagid = autoid("TAG-", "tag_id", "tag", "00001");
            $result2 = insertdata("tag", "tag_id,tag_name", "'$lasttagid','$tags'");
        }
    }
    if (isset($result) && isset($result2)) {
        foreach ($type_book as $type_books) {
            $col_type = "bt_bookid,bt_typeid";
            $values_type = "'$lastbookid','$type_books'";
            $result3 = insertdata("book_type", $col_type, $values_type);
        }
    }

    if (isset($result) && isset($result2) && isset($result3)) {
        $sql_tag = select("tag_id", "tag");
        while ($row = $sql_tag->fetch_assoc()) {
            $tag_id = $row['tag_id'];
            $result4 = insertdata("tag_book", "tb_tagid,tb_bookid", "'$tag_id','$lastbookid'");
        }
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","draf.php");
            </script>
                ';
    }
}

connectdb()->close();
