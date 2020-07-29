<?php
  include_once('connect_db.php');

  for ($i = 1; $i <= 10000; $i++) {
    $amount_to_bill = rand(1000,2000);
    $query = "INSERT INTO users VALUES('','user','09012345678','$amount_to_bill')";
    $result = $connection->query($query);
  }
  $connection->close();
?>