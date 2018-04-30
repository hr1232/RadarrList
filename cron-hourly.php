#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);

  // remember current time
  $thisupdate = gmdate('Y-m-d H:i:s');

  // get list of updated movies
  $updates = getMovieUpdates(50);
  $i = 1;
  foreach ($updates as $update) {
    if ($movie = getMovie($update))
      updateMovie($movie);
    else {
      $db->query("UPDATE movies SET movieUpdated=NULL WHERE movieId=".$update);
      echo "\n";
    }
  }

  // get list of updated series
  //$updates = getTvUpdates();

  // get list of updated persons
  //$updates = getPersonUpdates();

  // disconnect from database
  $db->close();

?>
