<?php
//connect db
function connectdb(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "ebook_system";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password ,$db);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully";
    return $conn;
}
function autoid($label,$max_id,$table,$null_id){
    $code = $label; //กำหนดอักษรนำหน้า
    $yearMonth = substr(date("Y") + 543, -2) . date("m"); //ดึงค่าปี เดือน ปัจจุบัน
    //query MAX ID
    $sql = "SELECT MAX($max_id) AS LAST_ID FROM $table";
    $result = connectdb()->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    $maxId = substr($row['LAST_ID'],9,5); //ดึงค่าไอดีล่าสุดจากตารางข้อมูลที่จะบันทึก
    if ($maxId == '') {
        $maxId = $null_id;
    } else {
        $maxId = ($maxId + 1);  //บวกค่าเพิ่มอีก 1
    }
    $maxId = str_pad($maxId,5,'0',STR_PAD_LEFT);
    $nextId = $code . $yearMonth . $maxId; //นำข้อมูลทั้งหมดมารวมกัน
    return $nextId;
    }
}
//select none where
function select($col,$table){
    $sql = "select $col from $table";
    $result = connectdb()->query($sql);
    return $result;
}
//select have where
function select_where($col,$table,$where){
    $sql = "select $col from $table where $where ";
    $result = connectdb()->query($sql);
    return $result;
}
//insert data
function insertdata($table,$values,$inputdata){
    $sql = "insert into $table ($values)
    values ($inputdata)";
    $result = connectdb()->query($sql);
    return $result;
}
//update data
function updatedata($table,$col,$where){
    $sql = "update $table set $col where $where";
    $result = connectdb()->query($sql);
    return $result;
}
//delete data
function deletedata($table,$where){
    $sql = "delete from $table where $where";
    $result = connectdb()->query($sql);
    return $result;
}

