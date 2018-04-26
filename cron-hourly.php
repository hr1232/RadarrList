#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);

  // get time for current and last update
  $thisupdate = date('Y-m-d H:i:s');
  $lastupdate = getLastUpdate();

  // get list of updated movies
  $updates = getMovieUpdates($lastupdate);
  print_r($updates);

  // get list of updated series
  $updates = getTvUpdates($lastupdate);
  print_r($updates);

  // get list of updated persons
  $updates = getPersonUpdates($lastupdate);
  print_r($updates);

  // disconnect from database
  $db->close();

?>
