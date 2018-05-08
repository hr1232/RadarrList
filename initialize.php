#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);

  // get time of the last update to not screw up the hourly update cronjob
  // get current time if not initialized yet
  $thisupdate = $db->query("SELECT IF(MAX(lastUpdate) IS NULL,'".gmdate('Y-m-d H:i:s')."',DATE_FORMAT(MAX(lastUpdate),'%Y-%m-%d %H:%i:%s')) AS lastUpdate FROM (
                              SELECT MAX(movieUpdated) AS lastUpdate FROM movies
                              UNION
                              SELECT MAX(personUpdated) AS lastUpdate FROM persons
                              UNION
                              SELECT MAX(seriesUpdated) AS lastUpdate FROM series
                            ) AS temp");
  $thisupdate = $thisupdate->fetch_array(MYSQLI_ASSOC);
  $thisupdate = $thisupdate['lastUpdate'];

  // get list of updated movies
  echo "Initializing database, please wait...\n";
  $complete = $db->query("SELECT COUNT(*) FROM movies");
  $complete = $complete->fetch_row();
  $complete = $complete[0];
  do {
    $rest = $db->query("SELECT COUNT(*)
                        FROM movies
                        WHERE (movieUpdated IS NULL) AND (movieAdult=0) AND (movieVideo=0)");
    $rest = $rest->fetch_row();
    if ($rest[0]) {
      echo $rest[0]." ".number_format(($rest[0]/$complete)*100,5,'.',',')."%: ";
      $update = $db->query("SELECT movieId
                            FROM (
                              SELECT movieId
                              FROM movies
                              WHERE (movieUpdated IS NULL) AND (movieAdult=0) AND (movieVideo=0)
                              ORDER BY moviePopularity
                              DESC LIMIT 100
                            ) AS temp
                            ORDER BY RAND()
                            LIMIT 1");
      $update = $update->fetch_row();
      if (($movie = getMovie($update[0])) || ($movie = getMovie($update[0]))) {
        updateMovie($movie);
        echo $movie->title." (Voted: ".($movie->vote_average).", Popularity: ".number_format($movie->popularity,3,'.',',').")\n";
      } else {
        $db->query("UPDATE movies SET movieUpdated=DATE_ADD(NOW(),INTERVAL 1 MONTH) WHERE movieId=".$update[0]);
        echo "API-Error ID ".$update[0]."\n";
      }
    } else
      break;
  } while (true);
  echo "Updating temporary table...\n";
  updateTempMovies();

  // get list of updated series
  //$updates = getTvUpdates();

  // get list of updated persons
  //$updates = getPersonUpdates();

  // disconnect from database
  $db->close();

?>
