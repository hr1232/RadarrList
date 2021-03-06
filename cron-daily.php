#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database and create tamporary id table
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);
  $db->query("CREATE TEMPORARY TABLE t (tId bigint(20) UNSIGNED NOT NULL, PRIMARY KEY (tId)) ENGINE=MEMORY");
  
  // remember current time
  $thisupdate = gmdate('Y-m-d H:i:s');

  // inguest collections
  if ($json = getCollectionDump()) {
    echo "Importing collection dump: ";
    $db->query("INSERT INTO t (tId) SELECT collectionId FROM collections");
    $rows = array();
    $rows2 = array();
    foreach ($json as $value) {
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      $rows[] = "(".implode(',',$row).")";
      $rows2[] = $value->id;
      if (count($rows) == 5000) {
        $db->query("INSERT INTO collections (collectionId, collectionTitle) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE collectionTitle=VALUES(collectionTitle)");
        $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
        $rows = array();
        $rows2 = array();
      }
      unset($row);
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO collections (collectionId, collectionTitle) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE collectionTitle=VALUES(collectionTitle)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $temp = $db->query("SELECT * FROM t LIMIT 1");
    if ($temp->num_rows) {
      $images = $db->query("SELECT collectionPoster
                            FROM t
                            LEFT JOIN collections ON t.tId=collections.collectionId");
      while ($image = $images->fetch_row())
        delImage($image[0]);
      $db->query("DELETE collections
                  FROM collections
                  LEFT JOIN t ON collections.collectionId=t.tId
                  WHERE tId IS NOT NULL");
      $db->query("TRUNCATE TABLE t");
    }
    echo count($json)."\n";
  } else
    echo "ERROR\n";

  // inguest companies
  if ($json = getCompanyDump()) {
    echo "Importing company dump: ";
    $db->query("INSERT INTO t (tId) SELECT companyId FROM companies");
    $rows = array();
    $rows2 = array();
    foreach ($json as $value) {
      $row = array();
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      $rows[] = "(".implode(',',$row).")";
      $rows2[] = $value->id;
      if (count($rows) == 5000) {
        $db->query("INSERT INTO companies (companyId, companyName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE companyName=VALUES(companyName)");
        $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
        $rows = array();
        $rows2 = array();
      }
      unset($row);
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO companies (companyId, companyName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE companyName=VALUES(companyName)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $temp = $db->query("SELECT * FROM t LIMIT 1");
    if ($temp->num_rows) {
      $images = $db->query("SELECT companyLogo
                            FROM t
                            LEFT JOIN companies ON t.tId=companies.companyId");
      while ($image = $images->fetch_row())
        delImage($image[0]);
      $db->query("DELETE companies
                  FROM companies
                  LEFT JOIN t ON companies.companyId=t.tId
                  WHERE tId IS NOT NULL");
      $db->query("TRUNCATE TABLE t");
    }
    echo count($json)."\n";
  } else
    echo "ERROR\n";

  // inguest keywords
  if ($json = getKeywordDump()) {
    echo "Importing keyword dump: ";
    $db->query("INSERT INTO t (tId) SELECT keywordId FROM keywords");
    $rows = array();
    $rows2 = array();
    foreach ($json as $value) {
      $row = array();
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      $rows[] = "(".implode(',',$row).")";
      $rows2[] = $value->id;
      if (count($rows) == 5000) {
        $db->query("INSERT INTO keywords (keywordId, keywordName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE keywordName=VALUES(keywordName)");
        $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
        $rows = array();
        $rows2 = array();
      }
      unset($row);
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO keywords (keywordId, keywordName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE keywordName=VALUES(keywordName)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $temp = $db->query("SELECT * FROM t LIMIT 1");
    if ($temp->num_rows) {
      $db->query("DELETE keywords
                  FROM keywords
                  LEFT JOIN t ON keywords.keywordId=t.tId
                  WHERE tId IS NOT NULL");
      $db->query("TRUNCATE TABLE t");
    }
    echo count($json)."\n";
  } else
    echo "ERROR\n";

  // inguest tv networks
  if ($json = getNetworkDump()) {
    echo "Importing network dump: ";
    $db->query("INSERT INTO t (tId) SELECT networkId FROM networks");
    $rows = array();
    $rows2 = array();
    foreach ($json as $value) {
      $row = array();
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      $rows[] = "(".implode(',',$row).")";
      $rows2[] = $value->id;
      if (count($rows) == 5000) {
        $db->query("INSERT INTO networks (networkId, networkName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE networkName=VALUES(networkName)");
        $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
        $rows = array();
        $rows2 = array();
      }
      unset($row);
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO networks (networkId, networkName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE networkName=VALUES(networkName)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $temp = $db->query("SELECT * FROM t LIMIT 1");
    if ($temp->num_rows) {
      $db->query("DELETE networks
                  FROM networks
                  LEFT JOIN t ON networks.networkId=t.tId
                  WHERE tId IS NOT NULL");
      $db->query("TRUNCATE TABLE t");
    }
    echo count($json)."\n";
  } else
    echo "ERROR\n";

  // inguest people
  if ($json = getPeopleDump()) {
    echo "Importing people dump: ";
    $db->query("INSERT INTO t (tId) SELECT personId FROM persons");
    $rows = array();
    $rows2 = array();
    foreach ($json as $value) {
      $list[] = $value->id;
      $row = array();
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      if (isset($value->popularity) && is_numeric($value->popularity))
        $row[] = $value->popularity;
      else
        $row[] = 0;
      if (isset($value->adult) && $value->adult)
        $row[] = 1;
      else
        $row[] = 0;
      $rows[] = "(".implode(',',$row).")";
      $rows2[] = $value->id;
      if (count($rows) == 5000) {
        $db->query("INSERT INTO persons (personId, personName, personPopularity, personAdult) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE personName=VALUES(personName), personPopularity=VALUES(personPopularity), personAdult=VALUES(personAdult)");
        $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
        $rows = array();
        $rows2 = array();
      }
      unset($row);
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO persons (personId, personName, personPopularity, personAdult) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE personName=VALUES(personName), personPopularity=VALUES(personPopularity), personAdult=VALUES(personAdult)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $temp = $db->query("SELECT * FROM t LIMIT 1");
    if ($temp->num_rows) {
      $images = $db->query("SELECT personPicture
                            FROM t
                            LEFT JOIN persons ON t.tId=persons.personId");
      while ($image = $images->fetch_row())
        delImage($image[0]);
      $db->query("DELETE persons
                  FROM persons
                  LEFT JOIN t ON persons.personId=t.tId
                  WHERE tId IS NOT NULL");
      $db->query("TRUNCATE TABLE t");
    }
    echo count($json)."\n";
  } else
    echo "ERROR\n";

  // inguest tv series
  if ($json = getTvDump()) {
    echo "Importing series dump: ";
    $db->query("INSERT INTO t (tId) SELECT seriesId FROM series");
    $rows = array();
    $rows2 = array();
    foreach ($json as $value) {
      $list[] = $value->id;
      $row = array();
      $row[] = $value->id;
      if (isset($value->original_name) && strlen($value->original_name))
        $row[] = "'".$db->escape_string($value->original_name)."'";
      else
        $row[] = "null";
      if (isset($value->popularity) && is_numeric($value->popularity))
        $row[] = $value->popularity;
      else
        $row[] = 0;
      $rows[] = "(".implode(',',$row).")";
      $rows2[] = $value->id;
      if (count($rows) == 5000) {
        $db->query("INSERT INTO series (seriesId, seriesOriginalTitle, seriesPopularity) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE seriesOriginalTitle=VALUES(seriesOriginalTitle), seriesPopularity=VALUES(seriesPopularity)");
        $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
        $rows = array();
        $rows2 = array();
      }
      unset($row);
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO series (seriesId, seriesOriginalTitle, seriesPopularity) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE seriesOriginalTitle=VALUES(seriesOriginalTitle), seriesPopularity=VALUES(seriesPopularity)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $temp = $db->query("SELECT * FROM t LIMIT 1");
    if ($temp->num_rows) {
      $db->query("DELETE series
                  FROM series
                  LEFT JOIN t ON series.seriesId=t.tId
                  WHERE tId IS NOT NULL");
      $db->query("TRUNCATE TABLE t");
    }
    echo count($json)."\n";
  } else
    echo "ERROR\n";

  // inguest movies
  if ($json = getMovieDump()) {
    echo "Importing movie dump: ";
    $db->query("INSERT INTO t (tId) SELECT seriesId FROM series");
    $rows = array();
    $rows2 = array();
    foreach ($json as $value) {
      $row = array();
      $row[] = $value->id;
      if (isset($value->original_title) && strlen($value->original_title))
        $row[] = "'".$db->escape_string($value->original_title)."'";
      else
        $row[] = "null";
      if (isset($value->popularity) && is_numeric($value->popularity))
        $row[] = $value->popularity;
      else
        $row[] = 0;
      if (isset($value->adult) && $value->adult)
        $row[] = 1;
      else
        $row[] = 0;
      if (isset($value->video) && $value->video)
        $row[] = 1;
      else
        $row[] = 0;
      $rows[] = "(".implode(',',$row).")";
      $rows2[] = $value->id;
      if (count($rows) == 5000) {
        $db->query("INSERT INTO movies (movieId, movieOriginalTitle, moviePopularity, movieAdult, movieVideo) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE movieOriginalTitle=VALUES(movieOriginalTitle), moviePopularity=VALUES(moviePopularity), movieAdult=VALUES(movieAdult), movieVideo=VALUES(movieVideo)");
        $db->query("TRUNCATE TABLE t");
        $rows = array();
        $rows2 = array();
      }
      unset($row);
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO movies (movieId, movieOriginalTitle, moviePopularity, movieAdult, movieVideo) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE movieOriginalTitle=VALUES(movieOriginalTitle), moviePopularity=VALUES(moviePopularity), movieAdult=VALUES(movieAdult), movieVideo=VALUES(movieVideo)");
      $db->query("TRUNCATE TABLE t");
    }
    $temp = $db->query("SELECT * FROM t LIMIT 1");
    if ($temp->num_rows) {
      $images = $db->query("SELECT moviePoster
                            FROM t
                            LEFT JOIN movies ON t.tId=movies.movieId");
      while ($image = $images->fetch_row())
        delImage($image[0]);
      $db->query("DELETE movies
                  FROM movies
                  LEFT JOIN t ON movies.movieId=t.tId
                  WHERE tId IS NOT NULL");
      $db->query("DELETE tempMovies
                  FROM tempMovies
                  LEFT JOIN t ON tempMovies.movieId=t.tId
                  WHERE tId IS NOT NULL");
      $db->query("TRUNCATE TABLE t");
    }
    echo count($json)."\n";
  } else
    echo "ERROR\n";

  // check for movie updates
  echo "Checking API for updated movies: ";
  $updates = getMovieUpdates(50);
  if (is_array($updates) && count($updates)) {
    $db->query("UPDATE movies SET movieUpdated=NULL WHERE (movieId IN (".implode(',',$updates).")) AND (movieUpdated IS NOT NULL)");
  }
  echo count($updates)."\n";

  // check for tv updates
  echo "Checking API for updated series: ";
  $updates = getTvUpdates(50);
  if (is_array($updates) && count($updates)) {
    $db->query("UPDATE series SET seriesUpdated=NULL WHERE (seriesId IN (".implode(',',$updates).")) AND (seriesUpdated IS NOT NULL)");
  }
  echo count($updates)."\n";

  // disconnect from database
  $db->close();

?>
