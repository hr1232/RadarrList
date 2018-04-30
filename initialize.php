#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);

  // get time of the last update to not screw up the hourly update cronjob
  // get current time if not initialized yet
  $thisupdate = $db->query("SELECT DATE_FORMAT(IF(MAX(movieUpdated) IS NULL,DATE_SUB(NOW(),INTERVAL 3 HOUR),MAX(movieUpdated)),'%Y-%m-%d %H:%i:%s') AS lastUpdate FROM movies");
  $thisupdate = $thisupdate->fetch_array(MYSQLI_ASSOC);
  $thisupdate = $thisupdate['lastUpdate'];

  // get list of updated movies
  echo "Initializing database, please wait...\n";
  $complete = $db->query("SELECT COUNT(*) AS movieCount FROM movies");
  $complete = $complete->fetch_array(MYSQLI_ASSOC);
  do {
    $updates = $db->query("SELECT COUNT(*) AS movieCount FROM movies WHERE movieUpdated IS NULL");
    $updates = $updates->fetch_array(MYSQLI_ASSOC);
    if ($updates['movieCount']) {
      echo $updates['movieCount']." ".number_format((1-$updates['movieCount']/$complete['movieCount'])*100,5,'.',',')."%: ";
      $updates = $db->query("SELECT movieId FROM movies WHERE movieUpdated IS NULL ORDER BY moviePopularity DESC LIMIT 1");
      if ($update = $updates->fetch_array(MYSQLI_ASSOC)) {
        if ($movie = getMovie($update['movieId'])) {
          updateMovie($movie);
          echo $movie->title." (Voted: ".($movie->vote_average).", Popularity: ".number_format($movie->popularity,3,'.',',').")\n";
        } else {
          $db->query("UPDATE movies SET movieUpdated=NULL WHERE movieId=".$update);
          echo "API-Error\n";
        }
      }
    }
  } while ($updates->num_rows);
  echo "Done.\n";

  // get list of updated series
  //$updates = getTvUpdates();

  // get list of updated persons
  //$updates = getPersonUpdates();

  // disconnect from database
  $db->close();

?>
