<?php
  // database details
  $DB_HOST = "localhost";
  $DB_USERNAME = "favourokeibunor@gmail.com";
  $DB_PASSWORD = "ilove1998";
  $DB_NAME = "htlchallenge";

  for ($i = 1; $i <= 10000; $i++) {
    // create connection to database
    $connection = new mysqli($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
    $time = date("Y-m-d H:i:s");
    $query = "INSERT INTO user VALUES('','user@gmail.com','user_pass',now(),now())";
    $result = $connection->query($query);
    $connection->close();
  }
?>