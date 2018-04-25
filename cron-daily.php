#!/usr/bin/php
<?php

  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // connect to database
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);

  // inguest companies
  $json = getCompanyDump();

  // inguest people
  $json = getPeopleDump();
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
    if (isset($value->imdb_id) && strlen($value->imdb_id))
      $row[] = "'".$db->escape_string($value->imdb_id)."'";
    else
      $row[] = "null";
    $rows[] = "(".implode(',',$row).")";
    if (count($rows) == 5000) {
      $db->query("INSERT IGNORE INTO persons (p_id, p_name, p_imdb) VALUES ".implode(', ',$rows));
      $rows = array();
    }
  }
  if (count($rows) > 0)
    $db->query("INSERT IGNORE INTO persons (p_id, p_name, p_imdb) VALUES ".implode(', ',$rows));
  $db->query("DELETE FROM persons WHERE p_id NOT IN (".implode(',',$list).")");

  // inguest keywords
  $json = getKeywordDump();
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
      $db->query("INSERT IGNORE INTO keywords (k_id, k_keyword) VALUES ".implode(', ',$rows));
      $rows = array();
    }
  }
  if (count($rows) > 0)
    $db->query("INSERT IGNORE INTO keywords (k_id, k_keyword) VALUES ".implode(', ',$rows));
  $db->query("DELETE FROM keywords WHERE k_id NOT IN (".implode(',',$list).")");

  // inguest tv networks
  $json = getNetworkDump();
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
      $db->query("INSERT IGNORE INTO networks (n_id, n_name) VALUES ".implode(', ',$rows));
      $rows = array();
    }
  }
  if (count($rows) > 0)
    $db->query("INSERT IGNORE INTO networks (n_id, n_name, n_country, n_homepage, n_headquaters) VALUES ".implode(', ',$rows));
  $db->query("DELETE FROM networks WHERE n_id NOT IN (".implode(',',$list).")");

  // inguest tv series
  $json = getTvDump();

  // inguest movies
  $json = getMovieDump();

  // disconnect from database
  $db->close();

?>
