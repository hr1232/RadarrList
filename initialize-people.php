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

  // get list of updated people
  $counter = 0;
  $start = time();
  $complete = $db->query("SELECT COUNT(*) FROM persons");
  $complete = $complete->fetch_row();
  $complete = $complete[0];
  do {
    $rest = $db->query("SELECT COUNT(*) FROM persons WHERE personUpdated IS NULL");
    $rest = $rest->fetch_row();
    $rest = $rest[0];
    if ($rest) {
      $updates = $db->query("SELECT personId FROM persons WHERE (personUpdated IS NULL) ORDER BY personAdult ASC, personPopularity DESC LIMIT 50");
      while($update = $updates->fetch_row()) {
        echo $rest." ".number_format((($complete-$rest)/$complete)*100,4,'.',',')."% ";
        if ($person = getPerson($update[0])) {
          $error = 0;
          updatePerson($person);
        } else {
          $error = 1;
          $db->query("UPDATE persons SET personUpdated=DATE_ADD(NOW(),INTERVAL 1 MONTH) WHERE personId=".$update[0]);
        }
        $rest--;
        $counter++;
        $elapsed = time()-$start;
        $speed = ceil($elapsed/$counter);
        $togo = $speed*$rest;
        echo "[";
        if ($togo >= 86400) {
          echo floor($togo/86400)."d ";
          $togo = $togo%86400;
        }
        if ($togo >= 3600) {
          echo floor($togo/3600)."h ";
          $togo = $togo%3600;
        }
        if ($togo >= 60) {
          echo floor($togo/60)."m ";
          $togo = $togo%60;
        }
        echo $togo."s";
        echo "]: ";
        if ($error)
          echo "API-Error ID ".$update[0]."\n";
        else
          echo $person->name." (Popularity: ".number_format($person->popularity,3,'.',',').")\n";
      }
    } else
      break;
  } while (true);

  // disconnect from database
  $db->close();

?>
