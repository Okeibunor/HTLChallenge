<?php
  include_once('connect_db.php');
  
  const TOTAL_USERS = 500;
  const SLICER = 5;
  
  $users = [];
  // sql query to get all users from database
  $query = "SELECT  * FROM users";
  $result = $connection->query($query);
  if (!$result) die ("Database access failed: " . $connection->error);

  $rows = $result->num_rows;
  if ($rows > 0){
    for ($j = 0 ; $j < TOTAL_USERS ; ++$j){
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $users[] = $row['id'];
    }
    $START_TIME = time();
    multiCurl($users);
  }


  // function has no return value
  function multiCurl($row){
    // array of curl handles
    $multiCurl = array();
    // data to be returned
    $result = array();
    
    foreach ($row as $i => $id) {
      // URL from which data will be fetched
      $fetchURL = 'https://jsonplaceholder.typicode.com/photos/'.$id;
      $multiCurl = curl_init();
      curl_setopt($multiCurl, CURLOPT_URL,$fetchURL);
      curl_setopt($multiCurl, CURLOPT_HEADER,0);
      // curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER,1);
      // curl_multi_add_handle($mh, $multiCurl[$i]);
      $result = curl_exec($multiCurl);
      var_dump ($result);
    }
    // close
    curl_close($multiCurl);
    global $START_TIME;
    echo 'Start time: '.date('h:i:s',$START_TIME) .'=>> Execution time: '.(time()-$START_TIME).' seconds';
  }
  ?>