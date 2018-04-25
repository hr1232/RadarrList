<?php

  require_once('/etc/tmdb/settings.inc.php');

  //////////////////////////////////////////////////////////
  // CONSTANTS
  // This sections defines constants for the entire program
  //////////////////////////////////////////////////////////

  define("DUMP_MOVIES",      "http://files.tmdb.org/p/exports/movie_ids_'.date('m_d_Y').'.json.gz'");
  define("DUMP_SERIES",      "http://files.tmdb.org/p/exports/tv_series_ids_'.date('m_d_Y').'.json.gz'");
  define("DUMP_PEOPLE",      "http://files.tmdb.org/p/exports/person_ids_'.date('m_d_Y').'.json.gz'");
  define("DUMP_COLLECTIONS", "http://files.tmdb.org/p/exports/collection_ids_'.date('m_d_Y').'.json.gz'");
  define("DUMP_NETWORKS",    "http://files.tmdb.org/p/exports/tv_network_ids_'.date('m_d_Y').'.json.gz'");
  define("DUMP_KEYWORDS",    "http://files.tmdb.org/p/exports/keyword_ids_'.date('m_d_Y').'.json.gz'");
  define("DUMP_COMPANIES",   "http://files.tmdb.org/p/exports/production_company_ids_'.date('m_d_Y').'.json.gz'");

  //////////////////////////////////////////////////////////
  // COMMONS
  // This section holds some common functions to be used later on
  //////////////////////////////////////////////////////////

  // function to get and decode json objects from the web
  function getJsonObject($url) {
    if ($dump = @file_get_contents($url))
      if ($dump = @gzdecode($dump))
        if ($dump = json_decode($dump))
          return $dump
        else
          return false;
      else
        return false;
    else
      return false;
  }

  //////////////////////////////////////////////////////////
  // DUMPS
  // This section provides functions to deal with dumps
  //////////////////////////////////////////////////////////

  // function to retrieve dump of all movies
  function getMovieDump() {
    if ($json = getJsonObject($DUMP_MOVIES)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all tv series
  function getMovieDump() {
    if ($json = getJsonObject($DUMP_SERIES)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all people
  function getMovieDump() {
    if ($json = getJsonObject($DUMP_PEOPLE)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all collections
  function getMovieDump() {
    if ($json = getJsonObject($DUMP_COLLECTIONS)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all tv networks
  function getMovieDump() {
    if ($json = getJsonObject($DUMP_NETWORKS)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all keywords
  function getMovieDump() {
    if ($json = getJsonObject($DUMP_KEYWORDS)) {
      return $json;
    } else
      return false;
  }

  // function to retrieve dump of all production companies
  function getMovieDump() {
    if ($json = getJsonObject($DUMP_COMPANIES)) {
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
