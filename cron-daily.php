#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);

  // inguest collections
  if ($json = getCollectionDump()) {
    $list = array();
    $rows = array();
    foreach ($json as $value) {
      $list[] = $value->id;
      $row = array();
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      $rows[] = "(".implode(',',$row).")";
      if (count($rows) == 5000) {
        $db->query("INSERT INTO collections (collectionId, collectionTitle) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE collectionTitle=VALUES(collectionTitle)");
        $rows = array();
      }
    }
    if (count($rows) > 0)
      $db->query("INSERT INTO collections (collectionId, collectionTitle) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE collectionTitle=VALUES(collectionTitle)");
    $db->query("DELETE FROM collections WHERE collectionId NOT IN (".implode(',',$list).")");
  }

  // inguest companies
  if ($json = getCompanyDump()) {
    $list = array();
    $rows = array();
    foreach ($json as $value) {
      $list[] = $value->id;
      $row = array();
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      $rows[] = "(".implode(',',$row).")";
      if (count($rows) == 5000) {
        $db->query("INSERT INTO companies (companyId, companyName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE companyName=VALUES(companyName)");
        $rows = array();
      }
    }
    if (count($rows) > 0)
      $db->query("INSERT INTO companies (companyId, companyName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE companyName=VALUES(companyName)");
    $db->query("DELETE FROM companies WHERE companyId NOT IN (".implode(',',$list).")");
  }

  // inguest keywords
  if ($json = getKeywordDump()) {
    $list = array();
    $rows = array();
    foreach ($json as $value) {
      $list[] = $value->id;
      $row = array();
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      $rows[] = "(".implode(',',$row).")";
      if (count($rows) == 5000) {
        $db->query("INSERT INTO keywords (keywordId, keywordName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE keywordName=VALUES(keywordName)");
        $rows = array();
      }
    }
    if (count($rows) > 0)
      $db->query("INSERT INTO keywords (keywordId, keywordName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE keywordName=VALUES(keywordName)");
    $db->query("DELETE FROM keywords WHERE keywordId NOT IN (".implode(',',$list).")");
  }

  // inguest tv networks
  if ($json = getNetworkDump()) {
    $list = array();
    $rows = array();
    foreach ($json as $value) {
      $list[] = $value->id;
      $row = array();
      $row[] = $value->id;
      if (isset($value->name) && strlen($value->name))
        $row[] = "'".$db->escape_string($value->name)."'";
      else
        $row[] = "null";
      $rows[] = "(".implode(',',$row).")";
      if (count($rows) == 5000) {
        $db->query("INSERT INTO networks (networkId, networkName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE networkName=VALUES(networkName)");
        $rows = array();
      }
    }
    if (count($rows) > 0)
      $db->query("INSERT INTO networks (networkId, networkName) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE networkName=VALUES(networkName)");
    $db->query("DELETE FROM networks WHERE networkId NOT IN (".implode(',',$list).")");
  }

  // inguest people
  if ($json = getPeopleDump()) {
    $list = array();
    $rows = array();
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
      if (count($rows) == 5000) {
        $db->query("INSERT INTO persons (personId, personName, personPopularity, personAdult) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE personName=VALUES(personName), personPopularity=VALUES(personPopularity), personAdult=VALUES(personAdult)");
        $rows = array();
      }
    }
    if (count($rows) > 0)
      $db->query("INSERT INTO persons (personId, personName, personPopularity, personAdult) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE personName=VALUES(personName), personPopularity=VALUES(personPopularity), personAdult=VALUES(personAdult)");
    $db->query("DELETE FROM persons WHERE personId NOT IN (".implode(',',$list).")");
  }

  // inguest tv series
  if ($json = getTvDump()) {
    $list = array();
    $rows = array();
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
      if (count($rows) == 5000) {
        $db->query("INSERT INTO series (seriesId, seriesOriginalTitle, seriesPopularity) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE seriesOriginalTitle=VALUES(seriesOriginalTitle), seriesPopularity=VALUES(seriesPopularity)");
        $rows = array();
      }
    }
    if (count($rows) > 0)
      $db->query("INSERT INTO series (seriesId, seriesOriginalTitle, seriesPopularity) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE seriesOriginalTitle=VALUES(seriesOriginalTitle), seriesPopularity=VALUES(seriesPopularity)");
    $db->query("DELETE FROM series WHERE seriesId NOT IN (".implode(',',$list).")");
  }

  // inguest movies
  if ($json = getMovieDump()) {
    $list = array();
    $rows = array();
    foreach ($json as $value) {
      $list[] = $value->id;
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
      if (count($rows) == 5000) {
        $db->query("INSERT INTO movies (movieId, movieOriginalTitle, moviePopularity, movieAdult, movieVideo) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE movieOriginalTitle=VALUES(movieOriginalTitle), moviePopularity=VALUES(moviePopularity), movieAdult=VALUES(movieAdult), movieVideo=VALUES(movieVideo)");
        $rows = array();
      }
    }
    if (count($rows) > 0)
      $db->query("INSERT INTO movies (movieId, movieOriginalTitle, moviePopularity, movieAdult, movieVideo) VALUES ".implode(', ',$rows)." ON DUPLICATE KEY UPDATE movieOriginalTitle=VALUES(movieOriginalTitle), moviePopularity=VALUES(moviePopularity), movieAdult=VALUES(movieAdult), movieVideo=VALUES(movieVideo)");
    $db->query("DELETE FROM movies WHERE movieId NOT IN (".implode(',',$list).")");
    $db->query("DELETE FROM tempMovies WHERE movieId NOT IN (".implode(',',$list).")");
  }

  // disconnect from database
  $db->close();

?>
