#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database and create tamporary id table
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);
  $db->query("CREATE TEMPORARY TABLE t (tId bigint(20) UNSIGNED NOT NULL, PRIMARY KEY (tId)) ENGINE=MEMORY");
  
  // remember current time
  $thisupdate = gmdate('Y-m-d H:i:s');

  // update tv series
  if ($result = $db->query("SELECT seriesId FROM series WHERE seriesUpdated IS NULL ORDER BY seriesPopularity DESC")) {
    if ($result->num_rows) {
      $updates = array();
      while ($row = $result->fetch_row())
        $updates[] = $row[0];
      if (is_array($updates) && count($updates)) {
        $i=0;
        foreach ($updates as $update) {
          echo number_format((($i++)/count($updates)),5,",",".")."%: ";
          if (!in_array($update,$updated['series'])) {
            if ($item = getSeries($update))
              updateSeries($item);
            else
              $db->query("DELETE FROM series WHERE seriesId=".$update);
            echo $item->original_name."\n";
          }
        }
      }
    }
  }

  // update movies
  if ($result = $db->query("SELECT movieId FROM movies WHERE movieUpdated IS NULL ORDER BY movieVoteAverage DESC LIMIT 200")) {
    if ($result->num_rows) {
      $updates = array();
      while ($row = $result->fetch_row())
        $updates[] = $row[0];
      if (is_array($updates) && count($updates)) {
        echo "Updating ".count($updates)." movies...\n";
        foreach ($updates as $update) {
          if (!in_array($update,$updated['movie'])) {
            if ($item = getMovie($update))
              updateMovie($item);
            else
              $db->query("DELETE FROM movies WHERE movieId=".$update);
          }
        }
        updateTempMovies();
      }
    }
  }

  // update persons
  //$updates = getPersonUpdates();

  // disconnect from database
  $db->close();

?>