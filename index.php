<?php
  error_reporting(0);
  include_once 'include/db.php';

  function cURL($url) {
    $curl = curl_init();
    if(!$curl) {
      die("Couldn't initialize a cURL handle");
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

    $html = curl_exec($curl);
    return $html;
    curl_close($curl);
  }

  $url = "https://jsonplaceholder.typicode.com/posts";
  $data = cURL($url);

  // Insert function
  $data_json = json_decode($data, true);
  foreach ($data_json as $key => $value) { 
    $sql = "INSERT INTO `users`(`id`, `userId`, `title`, `body`) 
      VALUES ('" . $value['id'] . "', '" . $value['userId'] . "', '" . $value['title'] . "', '" . $value['body'] . "')";
    $run = mysqli_query($connection, $sql);
  }
  
  if(isset($_POST['more'])) {
    $query = "SELECT * FROM users WHERE userid = 2";
    $search_result = mysqli_query($connection, $query);
  }
?>

<!doctype html>

<!-- If multi-language site, reconsider usage of html lang declaration here. -->
<html lang="en"> 

<head> 
  <meta charset="utf-8">
  <title>cURL</title>
  <!-- Place favicon.ico in the root directory: mathiasbynens.be/notes/touch-icons -->
  <link rel="shortcut icon" href="favicon.ico">
  <!--font-awesome link for icons-->
  <link rel="stylesheet" media="screen" href="assets/vendor/fontawesome-free-5.13.0-web/css/all.min.css">
  <!-- Default style-sheet is for 'media' type screen (color computer display).  -->
  <link rel="stylesheet" media="screen" href="assets/css/style.css">
  <!-- bootstrap link -->
  <!-- <link rel="stylesheet" media="screen" href="assets/vendor/bootstrap/css/bootstrap.min.css" > -->
</head>

<body>
  <!--container start-->
  <div class="container">
    <!--main section start-->
    <main>
      <div class="wrapper">
        <div class="records">
          <?php 
            if(isset($search_result)) {
              $no = mysqli_num_rows($search_result);
              while($row = mysqli_fetch_assoc($search_result)) {
                $t_id = $row['id'];
                $t_userid = $row['userId'];
                $t_title = $row['title'];
                $t_body = $row['body'];
                ?>
                <ul>
                  <li><span>id: </span><?php echo $t_id; ?></li>              
                  <li><span>title: </span><?php echo $t_title; ?></li>
                  <li><span>body: </span><?php echo $t_body; ?></li>
                </ul>
          <?php }} ?>
        </div>
        <div>
          <form action="index.php" method="POST">
            <?php
              if ($t_id === $no) {
                echo '
                  <div>
                    <input type="submit" value="Learn More" name="more">
                  </div>
                ';
              }
            ?>
          </form>
        </div>
      </div>
    </main>
    <!--main section end-->
  </div>
  <!--container end-->
</body>
</html>