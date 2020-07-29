<?php
  // database details
  $DB_HOST = "127.0.0.1";
  $DB_USERNAME = "DB_USER";
  $DB_PASSWORD = "DB_PASS";
  $DB_NAME = "DB_NAME";

  // create connection to database
  $connection = new mysqli($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
  $query = "SELECT  * FROM users";
  $result = $connection->query($query);
  if (!$result) die ("Database access failed: " . $connection->error);
  $rows = $result->num_rows;
  if ($rows > 0){
    for ($j = 0 ; $j < $rows ; ++$j){
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $ids = $row['user_id'];
    }
  }
  // function has no return value
  function multiCurl($ids){
    // array of curl handles
    $multiCurl = array();
    // data to be returned
    $result = array();
    // multi handle
    $mh = curl_multi_init();
    
    foreach ($ids as $i => $id) {
      // URL from which data will be fetched
      $fetchURL = 'https://webkul.com&customerId='.$id;
      $multiCurl[$i] = curl_init();
      curl_setopt($multiCurl[$i], CURLOPT_URL,$fetchURL);
      curl_setopt($multiCurl[$i], CURLOPT_HEADER,0);
      curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER,1);
      curl_multi_add_handle($mh, $multiCurl[$i]);
    }
    $index=null;
    do {
      curl_multi_exec($mh,$index);
    } while($index > 0);
    // get content and remove handles
    foreach($multiCurl as $k => $ch) {
      $result[$k] = curl_multi_getcontent($ch);
      curl_multi_remove_handle($mh, $ch);
    }
    // close
    curl_multi_close($mh);

  }
  ?>