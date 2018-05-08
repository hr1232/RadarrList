#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database and create tamporary id table
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);
  $db->query("CREATE TEMPORARY TABLE t (tId bigint(20) UNSIGNED NOT NULL, PRIMARY KEY (tId)) ENGINE=MEMORY");
  

  // inguest collections
  if ($json = getCollectionDump()) {
    $db->query("INSERT INTO t (tId) SELECT collectionId FROM collections");
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
        $db->query("INSERT INTO collections (collectionId, collectionTitle) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE collectionTitle=VALUES(collectionTitle)");
        $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
        $rows = array();
        $rows2 = array();
      }
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO collections (collectionId, collectionTitle) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE collectionTitle=VALUES(collectionTitle)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $db->query("DELETE collections
                FROM collections
                LEFT JOIN t ON collections.collectionId=t.tId
                WHERE tId IS NOT NULL");
    $db->query("TRUNCATE TABLE t");
  }

  // inguest companies
  if ($json = getCompanyDump()) {
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
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO companies (companyId, companyName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE companyName=VALUES(companyName)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $db->query("DELETE companies
                FROM companies
                LEFT JOIN t ON companies.companyId=t.tId
                WHERE tId IS NOT NULL");
    $db->query("TRUNCATE TABLE t");
  }

  // inguest keywords
  if ($json = getKeywordDump()) {
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
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO keywords (keywordId, keywordName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE keywordName=VALUES(keywordName)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $db->query("DELETE keywords
                FROM keywords
                LEFT JOIN t ON keywords.keywordId=t.tId
                WHERE tId IS NOT NULL");
    $db->query("TRUNCATE TABLE t");
  }

  // inguest tv networks
  if ($json = getNetworkDump()) {
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
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO networks (networkId, networkName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE networkName=VALUES(networkName)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $db->query("DELETE networks
                FROM networks
                LEFT JOIN t ON networks.networkId=t.tId
                WHERE tId IS NOT NULL");
    $db->query("TRUNCATE TABLE t");
  }

  // inguest people
  if ($json = getPeopleDump()) {
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
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO persons (personId, personName, personPopularity, personAdult) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE personName=VALUES(personName), personPopularity=VALUES(personPopularity), personAdult=VALUES(personAdult)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $db->query("DELETE persons
                FROM persons
                LEFT JOIN t ON persons.personId=t.tId
                WHERE tId IS NOT NULL");
    $db->query("TRUNCATE TABLE t");
  }

  // inguest tv series
  if ($json = getTvDump()) {
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
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO series (seriesId, seriesOriginalTitle, seriesPopularity) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE seriesOriginalTitle=VALUES(seriesOriginalTitle), seriesPopularity=VALUES(seriesPopularity)");
      $db->query("DELETE FROM t WHERE tId IN (".implode(',',$rows2).")");
    }
    $db->query("DELETE series
                FROM series
                LEFT JOIN t ON series.seriesId=t.tId
                WHERE tId IS NOT NULL");
    $db->query("TRUNCATE TABLE t");
  }

  // inguest movies
  if ($json = getMovieDump()) {
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
    }
    if (count($rows) > 0) {
      $db->query("INSERT INTO movies (movieId, movieOriginalTitle, moviePopularity, movieAdult, movieVideo) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE movieOriginalTitle=VALUES(movieOriginalTitle), moviePopularity=VALUES(moviePopularity), movieAdult=VALUES(movieAdult), movieVideo=VALUES(movieVideo)");
      $db->query("TRUNCATE TABLE t");
    }
    $db->query("DELETE movies
                FROM movies
                LEFT JOIN t ON movies.movieId=t.tId
                WHERE tId IS NOT NULL");
    $db->query("TRUNCATE TABLE t");
  }

  // destroy temporary table and disconnect from database
  $db->query("DROP TABLE t");
  $db->close();

?>
