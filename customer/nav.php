<?php
include "function.php";
connectdb();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">My Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">หน้าแรก</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            หมวดหมู่
          </a>
          <?php
          $sqltypebook = "select *from typebook";
          $result = connectdb()->query($sqltypebook);
          ?>
          <ul class="dropdown-menu">
            <?php
            if ($result->num_rows > 0){
              while ($row = $result->fetch_assoc()){
  
            ?>
            <li><a class="dropdown-item" href="typebook_page.php?typeid=<?php echo $row['type_id']?>&typename=<?php echo $row['type_name']?>"><?php echo $row['type_name']?></a></li>
            <li>
              <hr class="dropdown-divider"></hr>
            </li>
            <?php
              }
            }
            ?>
          </ul>
        </li>
        <?php
        if(isset($_SESSION['cusid']) && isset($_SESSION['cusname'])){
          $cusid = $_SESSION['cusid'];
          $cusname = $_SESSION['cusname'];
          $result = select_where("pub_id","publisher","pub_id = '$cusid'");
        }
        if(isset($cusid)){
        ?>
        <li class="nav-item">
          <a class="nav-link" href="shelf.php">ชั้นหนังสือ</a>
        </li>
        <?php
        }
        else{
        ?>
          <script>
            function registt(mypages) {
              let agrees = confirm("ต้องเป็นสมาชิกก่อน");
              if (agrees) {
                window.location = mypages;
              }
            }
          </script>
          <li class="nav-item"><a class="nav-link" onclick="registt(this.href); return false;" href="register.php">ชั้นหนังสือ</a></li>
        <?php
        }

        if(isset($cusid)){
          if (isset($result) && $result->num_rows > 0) {
        ?>
          <li class="nav-item"><a class="nav-link" href="my_work.php">หน้าผู้เผยแพร่</a></li>
        <?php
        } else {
        ?>
          <script>
            function regist(mypage) {
              let agree = confirm("ต้องสมัครเป็นผู้เผยแพร่ก่อน");
              if (agree) {
                window.location = mypage;
              }
            }
          </script>
          <li class="nav-item"><a class="nav-link" onclick="regist(this.href); return false;" href="publis_register.php">หน้าผู้เผยแพร่</a></li>
        <?php
          }
      }
        ?>
        <?php
        if (!isset($cusid)) {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="register.php">สมัครสมาชิก</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">เข้าสู่ระบบ</a>
          </li>
        <?php
        }
        else{
        ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php
            echo $cusname;
            ?>
          </a>
          <ul class="dropdown-menu">
            <?php
            $sqlcoin = select_where("cus_coin","customer","cus_id = '$cusid'");
            if ($sqlcoin->num_rows > 0){
                $row = $sqlcoin->fetch_assoc();
            
            ?>
            <h5 class="text-danger text-center"><?php echo $row['cus_coin']?></h5>
            <li><a class="dropdown-item" href="add_coin.php">เติมเหรียญ</a></li>
            <?php
            }
            ?>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
          </ul>
        </li>
        <?php
        }
        ?>
      </ul>
    <ul class="navbar-nav ms-auto">
      <?php
          $sql_cart = select_where("count(cart_bookid) as countbook","carts","cart_cusid = '$cusid'");
          if ($sql_cart->num_rows > 0){
            $row = $sql_cart->fetch_assoc();

      ?>
      <li class="nav-item">
        <a class="nav-link" href="cart.php">ตะกร้าสินค้า <i class="fas fa-shopping-cart"></i> <span class="badge bg-secondary"><?php echo  $row['countbook'] ?></span></a>
      </li>
    </ul>
    <?php
          }
          else{
            echo "<a class='nav-link' href='cart.php'>ตะกร้าสินค้า <i class='fas fa-shopping-cart'></i> <span class='badge bg-secondary'>0</span></a>";
          }
    ?>
    </div>
  </div>
</nav>