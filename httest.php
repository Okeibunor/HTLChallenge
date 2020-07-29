<?php
  include_once('connect_db.php');

  $query = "SELECT  * FROM users";
  $result = $connection->query($query);
  if (!$result) die ("Database access failed: " . $connection->error);
  $rows = $result->num_rows;
  if ($rows > 0){
    for ($j = 0 ; $j < $rows ; ++$j){
      $result->data_seek($j);
      $row = $result->fetch_array(MYSQLI_ASSOC);
      multiCurl($row);
    }
  }

  // function has no return value
  function multiCurl($row){
    // array of curl handles
    $multiCurl = array();
    // data to be returned
    $result = array();
    // multi handle
    $mh = curl_multi_init();
    
    foreach ($row as $i => $id) {
      // URL from which data will be fetched
      $fetchURL = 'https://jsonplaceholder.typicode.com/users/'.$id;
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