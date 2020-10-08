<?php
$pid = $_GET["pid"];
$vid = $_GET["vid"];
session_start();
$user_type = $_SESSION["user_type"];
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: 微軟正黑體;
    }
  </style>
  <title>my playlist</title>

</head>

<body>
  playlist
  <hr>
  <?php include "../includes/menu.php"; ?>
  <hr>
  <?php
  $servername = "localhost";
  $username = "root";
  $password = "12345678";
  $dbname = "bbs";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  //以下建立SQL查詢指令
  $sql = "SELECT  * FROM video WHERE pid='$pid'";
  //以下執行SQL查詢指令，並把結果傳回給$result變數
  $result = $conn->query($sql);
  // echo $result->num_rows;
  if ($user_type == NULL) {
    //如果還沒登入的話，要顯示登入表單
    //以下建立一個用來輸入密碼的表單
    //使用者按下「登入」之後，即會前往chkpass.php檢查密碼
    echo "<form method=POST action=chkpass.php>";
    echo "ur password：<input type=password name=password>";
    echo "<input type=submit value=login>";
    echo "</form>";
  } else {
    //如已登入的話，要有張貼訊息的表單
    echo "<form method=POST action=vpost.php>";
    echo "add new title：<input type=text name=title size=40> ";
    echo "vid：<input type=text name=vid size=20>";
    echo "<input type=submit value=add>";
    echo "</form>";
    echo "<button><a href=logout.php>logout</a></button>";
  }
  if ($result->num_rows > 0) { //檢查記錄的數量，看看是否有資料
    // output data of each row
    echo "<table width=800 bgcolor=white>";
    //以下是表格標題列
    if ($user_type == NULL)
      echo "<tr bgcolor=#cccccc><td>TITLE</td><td>VIDEO ID</td></tr>";
    else
      //如果login的話，才能使用貼文管理
      echo "<tr bgcolor=#cccccc><td>TITLE</td><td>VIDEO ID</td><td>MANAGE</td></tr>";
    while ($row = $result->fetch_assoc()) {
      $id = $row["id"]; //指定各訊息的id
      echo "<tr bgcolor=pink>";
      echo "<td><a>" . $row["title"] . "</a></td>";
      echo "<td><a>" . $row["vid"] . "</a></td>";
      if ($user_type != NULL) {
        echo "<td>";
        echo "<a href='vedit.php?id=$id'>edit</a>"; //把拿到的id指定在edit後面，而後只要點選到該id就能要edit.php去修改它
        echo " - ";
        echo "<a href='vdelete.php?id=$id'>delete</a>";
        echo "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "0 results"; // 如果資料表中沒有記錄，要顯示的內容
  }
  $conn->close();

  ?>
  <hr>
  <hr>
  <?php include "../includes/footer.php"; ?>
</body>

</html>