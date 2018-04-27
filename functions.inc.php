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
    $result = $db->query("SELECT movieId FROM movies WHERE movieUpdated IS NULL ORDER BY moviePopularity DESC LIMIT 500");
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
    $result = $db->query("SELECT seriesId FROM series WHERE seriesUpdated IS NULL ORDER BY seriesPopularity DESC LIMIT 500");
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
    $result = $db->query("SELECT personId FROM persons WHERE personUpdated IS NULL ORDER BY personPopularity DESC LIMIT 500");
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
      $list[] = $row['personId'];
    return $list;
  }

  // function to retrieve information on a movie
  function getMovie($id) {
    global $api;
    if ($result = getJson("https://api.themoviedb.org/3/movie/".$id."?api_key=".$api."&language=en-US&append_to_response=keywords"))
      return $result;
    else
      return false;
  }

  //////////////////////////////////////////////////////////
  // UPDATE
  // This section provides functions to update information in the database
  //////////////////////////////////////////////////////////

  function updateCollection($collection) {
    global $db;
    global $thisupdate;
    $row = array();
    $row[] = $collection->id;
    if (isset($collection->poster_path) && strlen($collection->poster_path))
      $row[] = "'".$db->escape_string($collection->poster_path)."'";
    else
      $row[] = "null"; 
    $row[] = "'".$thisupdate."'";
    if ($db->query("INSERT INTO collections (collectionId, collectionPoster, collectionUpdated) VALUES (
                      ".implode(', ',$row)."
                    ) ON DUPLICATE KEY UPDATE
                    collectionPoster=VALUES(collectionPoster),
                    collectionUpdated=VALUES(collectionUpdated)"))
      return true;
    else
      return false;
  }

  function updateCompany($company) {
    global $db;
    global $thisupdate;
    return true;
  }

  function updateCountry($country) {
    global $db;
    global $thisupdate;
    return true;
  }

  function updateGenre($genre) {
    global $db;
    global $thisupdate;
    return true;
  }

  function updateLanguage($language) {
    global $db;
    global $thisupdate;
    $row = array();
    $row[] = "'".$language->iso_639_1."'";
    if (isset($language->name) && strlen($language->name))
      $row[] = "'".$db->escape_string($language->name)."'";
    else
      $row[] = "null";
    $row[] = "'".$thisupdate."'";
    if ($db->query("INSERT INTO languages (languageCode, languageName, languageUpdated) VALUES (
                      ".implode(', ',$row)."
                    ) ON DUPLICATE KEY UPDATE
                    languageName=VALUES(languageName),
                    languageUpdated=VALUES(languageUpdated)"))
      return true;
    else
      return false;
  }

  function updateMovie($movie) {
    global $db;
    global $thisupdate;
    $row = array("movieUpdated='".$thisupdate."'");
    if (isset($movie->belongs_to_collection) && is_object($movie->belongs_to_collection) && isset($movie->belongs_to_collection->id) && is_numeric($movie->belongs_to_collection->id)) {
      updateCollection($movie->belongs_to_collection);
      $row[] = "movieCollection=".$movie->belongs_to_collection->id;
    } else
      $row[] = "movieCollection=NULL";
    if (isset($movie->production_companies) && is_array($movie->production_companies) && count($movie->production_companies)) {
      $list = array();
      foreach ($movie->production_companies as $company) {
        updateCompany($company);
        $db->query("INSERT IGNORE INTO moviesCompanies (movieId, companyId) VALUES (".$movie->id.", ".$company->id.")");
        $list[] = $company->id;
      }
      $db->query("DELETE FROM movieCompanies WHERE movieId =".$movie->id." AND (companyId NOT IN (".implode(',',$list)."))");
    } else
      $db->query("DELETE FROM moviesCompanies WHERE movieId=".$movie->id);
    if (isset($movie->production_countries) && is_array($movie->production_countries) && count($movie->production_countries)) {
      $list = array();
      foreach ($movie->production_countries as $country) {
        updateCountry($country);
        $db->query("INSERT IGNORE INTO moviesCountries (movieId, countryCode) VALUES (".$movie->id.", '".$country->iso_3166_1."')");
        $list[] = $country->iso_3166_1;
      }
      $db->query("DELETE FROM movieCountries WHERE movieId =".$movie->id." AND (countryCode NOT IN (".implode(',',$list)."))");
    } else
      $db->query("DELETE FROM moviesCountries WHERE movieId=".$movie->id);
    if (isset($movie->genres) && is_array($movie->genres) && count($movie->genres)) {
      $list = array();
      foreach ($movie->genres as $genre) {
        updateGenre($genre);
        $db->query("INSERT IGNORE INTO moviesGenres (movieId, genreId) VALUES (".$movie->id.", ".$genre->id.")");
        $list[] = $genre->id;
      }
      $db->query("DELETE FROM movieGenres WHERE movieId =".$movie->id." AND (genreId NOT IN (".implode(',',$list)."))");
    } else
      $db->query("DELETE FROM moviesGenres WHERE movieId=".$movie->id);
    if (isset($movie->keywords->keywords) && is_array($movie->keywords->keywords) && count($movie->keywords->keywords)) {
      $list = array();
      foreach ($movie->keywords->keywords as $keyword) {
        $db->query("INSERT IGNORE INTO moviesKeywords (movieId, keywordId) VALUES (".$movie->id.", ".$keyword->id.")");
        $list[] = $keyword->id;
      }
      $db->query("UPDATE keywords SET keywordUpdated='".$thisupdate."' WHERE keywordId IN (".implode(',',$list).")");
      $db->query("DELETE FROM movieKeywords WHERE movieId =".$movie->id." AND (keywodId NOT IN (".implode(',',$list)."))");
    } else
      $db->query("DELETE FROM moviesKeywords WHERE movieId=".$movie->id);
    if (isset($movie->spoken_languages) && is_array($movie->spoken_languages) && count($movie->spoken_languages)) {
      $list = array();
      foreach ($movie->spoken_languages as $language) {
        updateLanguage($language);
        $db->query("INSERT IGNORE INTO moviesLanguages (movieId, languageCode) VALUES (".$movie->id.", '".$language->iso_639_1."')");
        $list[] = $language->iso_639_1;
      }
      $db->query("DELETE FROM movieLanguages WHERE movieId =".$movie->id." AND (languageId NOT IN (".implode(',',$list)."))");
    } else
      $db->query("DELETE FROM moviesLanguages WHERE movieId=".$movie->id);
    if (isset($movie->title) && strlen($movie->title))
      $row[] = "movieTitle='".$db->escape_string($movie->title)."'";
    else
      $row[] = "movieTitle=NULL";
    if (isset($movie->tagline) && strlen($movie->tagline))
      $row[] = "movieTagline='".$db->escape_string($movie->tagline)."'";
    else
      $row[] = "movieTagline=NULL";
    if (isset($movie->original_language) && strlen($movie->original_language)) {
      $row[] = "movieOriginalLanguage='".$db->escape_string($movie->original_language)."'";
      $db->query("INSERT IGNORE INTO languages (languageCode, languageUpdated) VALUES ('".$db->escape_string($movie->original_language)."', '".$thisupdate."')");
    } else
      $row[] = "movieOriginalLanguage=NULL";
    if (isset($movie->overview) && strlen($movie->overview))
      $row[] = "movieOverview='".$db->escape_string($movie->overview)."'";
    else
      $row[] = "movieOverview=NULL";
    if (isset($movie->poster_path) && strlen($movie->poster_path))
      $row[] = "moviePoster='".$db->escape_string($movie->poster_path)."'";
    else
      $row[] = "moviePoster=NULL";
    if (isset($movie->status) && strlen($movie->status))
      $row[] = "movieStatus='".$db->escape_string($movie->status)."'";
    else
      $row[] = "movieStatus=NULL";
    if (isset($movie->release_date) && strlen($movie->release_date))
      $row[] = "movieReleaseDate='".$db->escape_string($movie->release_date)."'";
    else
      $row[] = "movieReleaseDate=NULL";
    if (isset($movie->imdb_id) && strlen($movie->imdb_id))
      $row[] = "movieImdb='".$db->escape_string($movie->imdb_id)."'";
    else
      $row[] = "movieImdb=NULL";
    if (isset($movie->runtime) && is_numeric($movie->runtime))
      $row[] = "movieRuntime=".$db->escape_string($movie->runtime);
    else
      $row[] = "movieRuntime=NULL";
    if (isset($movie->vote_average) && is_numeric($movie->vote_average))
      $row[] = "movieVoteAverage=".$db->escape_string($movie->vote_average);
    else
      $row[] = "movieVoteAverage=NULL";
    if (isset($movie->vote_count) && is_numeric($movie->vote_count))
      $row[] = "movieVoteCount=".$db->escape_string($movie->vote_count);
    else
      $row[] = "movieVoteCount=NULL";
    if (isset($movie->adult) && $movie->adult)
      $row[] = "movieAdult=1";
    else
      $row[] = "movieAdult=0";
    //$db->query("UPDATE movies SET ".implode(', ',$row)." WHERE movieId=".$movie->id);
    echo "UPDATE movies SET ".implode(', ',$row)." WHERE movieId=".$movie->id."\n"; exit;
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
