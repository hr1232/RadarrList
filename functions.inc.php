<?php

  require_once(dirname(__FILE__)."/settings.inc.php");

  //////////////////////////////////////////////////////////
  // CONSTANTS
  // This sections defines constants for the entire program
  //////////////////////////////////////////////////////////

  define("DUMP_MOVIES",      "http://files.tmdb.org/p/exports/movie_ids_".date('m_d_Y').".json.gz");
  define("DUMP_SERIES",      "http://files.tmdb.org/p/exports/tv_series_ids_".date('m_d_Y').".json.gz");
  define("DUMP_PEOPLE",      "http://files.tmdb.org/p/exports/person_ids_".date('m_d_Y').".json.gz");
  define("DUMP_COLLECTIONS", "http://files.tmdb.org/p/exports/collection_ids_".date('m_d_Y').".json.gz");
  define("DUMP_NETWORKS",    "http://files.tmdb.org/p/exports/tv_network_ids_".date('m_d_Y').".json.gz");
  define("DUMP_KEYWORDS",    "http://files.tmdb.org/p/exports/keyword_ids_".date('m_d_Y').".json.gz");
  define("DUMP_COMPANIES",   "http://files.tmdb.org/p/exports/production_company_ids_".date('m_d_Y').".json.gz");

  //////////////////////////////////////////////////////////
  // COMMONS
  // This section holds some common functions to be used later on
  //////////////////////////////////////////////////////////

  // function to get and decode json dump objects from the web
  function getJsonDump($url) {
    if ($dump = explode("\n",gzdecode(file_get_contents($url)))) {
      foreach ($dump as $id => $value)
        if (!($dump[$id] = json_decode($value)))
          unset($dump[$id]);
      return $dump;
    } else
      return false;
  }

  // function to get and decode json objects from the web
  function getJson($url) {
    if ($file = json_decode(file_get_contents($url))) {
      return $file;
    } else
      return false;
  }

  // functions to determin the time when the last update ran
  function getLastUpdate() {
    global $db;
    $result = $db->query("SELECT DATE_FORMAT(IF(MAX(lastUpdate) IS NULL,DATE_SUB(NOW(),INTERVAL 1 HOUR),MAX(lastUpdate)),'%Y-%m-%d %H:%i:%s') AS lastUpdate FROM (
                            (SELECT MAX(movieUpdated) AS lastUpdate FROM movies) UNION
                            (SELECT MAX(seriesUpdated) AS lastUpdate FROM series) UNION
                            (SELECT MAX(personUpdated) AS lastUpdate FROM persons)
                          ) AS temp");
    $row = $result->fetch_array(MYSQLI_ASSOC);
    return $row['lastUpdate'];
  }

  //////////////////////////////////////////////////////////
  // API
  // This sections provides functions for API calls
  //////////////////////////////////////////////////////////

  // function to retrieve all movies updated since the last run and up to 500 movies that have never been updated
  function getMovieUpdates($startTime) {
    global $api;
    global $db;
    $list = array();
    $page = 1;
    do {
      if ($result = getJson("https://api.themoviedb.org/3/movie/changes?api_key=".$api."&start_date=".urlencode($startTime)."&page=".$page)) {
        foreach($result->results as $value)
          $list[] = $value->id;
      }
    } while ($result->total_pages > $page++);
    $result = $db->query("SELECT movieId FROM movies WHERE movieUpdated IS NULL ORDER BY RAND() LIMIT 500");
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
      $list[] = $row['movieId'];
    return $list;
  }

  // function to retrieve all tv series updated since the last run and up to 500 series that have never been updated
  function getTvUpdates($startTime) {
    global $api;
    global $db;
    $list = array();
    $page = 1;
    do {
      if ($result = getJson("https://api.themoviedb.org/3/tv/changes?api_key=".$api."&start_date=".urlencode($startTime)."&page=".$page)) {
        foreach($result->results as $value)
          $list[] = $value->id;
      }
    } while ($result->total_pages > $page++);
    $result = $db->query("SELECT seriesId FROM series WHERE seriesUpdated IS NULL ORDER BY RAND() LIMIT 500");
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
      $list[] = $row['seriesId'];
    return $list;
  }

  // function to retrieve all persons updated since the last run and up to 500 persons that have never been updated
  function getPersonUpdates($startTime) {
    global $api;
    global $db;
    $list = array();
    $page = 1;
    do {
      if ($result = getJson("https://api.themoviedb.org/3/tv/changes?api_key=".$api."&start_date=".urlencode($startTime)."&page=".$page)) {
        foreach($result->results as $value)
          $list[] = $value->id;
      }
    } while ($result->total_pages > $page++);
    $result = $db->query("SELECT personId FROM persons WHERE personUpdated IS NULL ORDER BY RAND() LIMIT 500");
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
      $list[] = $row['personId'];
    return $list;
  }

  //////////////////////////////////////////////////////////
  // DUMPS
  // This section provides functions to deal with dumps
  //////////////////////////////////////////////////////////

  // function to retrieve dump of all movies
  function getMovieDump() {
    if ($json = getJsonDump(DUMP_MOVIES)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all tv series
  function getTvDump() {
    if ($json = getJsonDump(DUMP_SERIES)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all people
  function getPeopleDump() {
    if ($json = getJsonDump(DUMP_PEOPLE)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all collections
  function getCollectionDump() {
    if ($json = getJsonDump(DUMP_COLLECTIONS)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all tv networks
  function getNetworkDump() {
    if ($json = getJsonDump(DUMP_NETWORKS)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all keywords
  function getKeywordDump() {
    if ($json = getJsonDump(DUMP_KEYWORDS)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all production companies
  function getCompanyDump() {
    if ($json = getJsonDump(DUMP_COMPANIES)) {
      return $json;
    } else
      return false;
  }

  //////////////////////////////////////////////////////////
  // MOVIES
  // This section provides functions to deal with movies
  //////////////////////////////////////////////////////////

  // function to retrieve details for one movie
  function getMovieDetails($id) {
    
  };

  // function go get al updated movies for a defined timeframe
  function getUpdatedMovies() {

  };

  //////////////////////////////////////////////////////////
  // COLLECTIONS
  // This section provides functions to deal with collections
  //////////////////////////////////////////////////////////

?>
