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
  define("IMAGE_BASE",       "http://image.tmdb.org/t/p/w500");

  //////////////////////////////////////////////////////////
  // FUNCTIONS
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
    if ($file = @file_get_contents($url))
      if ($file = @json_decode($file))
        return $file;
      else
        return false;
    else
      return false;
  }

  // function to retrieve all movies updated since the last run and up to 500 movies that have never been updated
  function getMovieUpdates($limit) {
    global $api;
    global $db;
    $result = $db->query("SELECT IF(MAX(lastUpdate) IS NULL,'".gmdate('Y-m-d H:i:s')."',DATE_FORMAT(MAX(lastUpdate),'%Y-%m-%d %H:%i:%s')) AS lastUpdate FROM (
                            SELECT MAX(movieUpdated) AS lastUpdate FROM movies
                            UNION
                            SELECT MAX(personUpdated) AS lastUpdate FROM persons
                            UNION
                            SELECT MAX(seriesUpdated) AS lastUpdate FROM series
                          ) AS temp");
    if ($result && $result->num_rows) {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $startTime = $row['lastUpdate'];
    } else
      $startTime = gmdate('Y-m-d 00:00:00');
    $list = array();
    $page = 1;
    do {
      if ($result = getJson("https://api.themoviedb.org/3/movie/changes?api_key=".$api."&start_date=".urlencode($startTime)."&page=".$page)) {
        foreach($result->results as $value)
          $list[] = $value->id;
      }
    } while ($result->total_pages > $page++);
    $result = $db->query("SELECT movieId FROM movies WHERE movieUpdated IS NULL ORDER BY RAND() DESC LIMIT ".$limit);
    while (($row = $result->fetch_array(MYSQLI_ASSOC)) && (count($list)<$limit))
      $list[] = $row['movieId'];
    $result = $db->query("SELECT movieId FROM movies WHERE (movieUpdated IS NOT NULL) AND (DATE_SUB(`movieUpdated`,INTERVAL 3 MONTH)>=NOW()) ORDER BY RAND() DESC LIMIT ".$limit);
    while (($row = $result->fetch_array(MYSQLI_ASSOC)) && (count($list)<$limit))
      $list[] = $row['movieId'];
    return $list;
  }

  // function to retrieve all tv series updated since the last run and up to 500 series that have never been updated
  function getTvUpdates($startTime) {
    global $api;
    global $db;
    $result = $db->query("SELECT DATE_FORMAT(IF(MAX(seriesUpdated) IS NULL,DATE_SUB(NOW(),INTERVAL 3 HOUR),MAX(seriesUpdated)),'%Y-%m-%d %H:%i:%s') AS lastUpdate FROM series");
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $startTime = $row['lastUpdate'];
    $list = array();
    $page = 1;
    do {
      if ($result = getJson("https://api.themoviedb.org/3/tv/changes?api_key=".$api."&start_date=".urlencode($startTime)."&page=".$page)) {
        foreach($result->results as $value)
          $list[] = $value->id;
      }
    } while ($result->total_pages > $page++);
    $result = $db->query("SELECT seriesId FROM series WHERE seriesUpdated IS NULL ORDER BY RAND() DESC LIMIT ".$limit);
    while (($row = $result->fetch_array(MYSQLI_ASSOC)) && (count($list)<$limit))
      $list[] = $row['seriesId'];
    $result = $db->query("SELECT seriesId FROM series WHERE (seriesUpdated IS NOT NULL) AND (DATE_SUB(`seriesUpdated`,INTERVAL 3 MONTH)>=NOW()) ORDER BY RAND() DESC LIMIT ".$limit);
    while (($row = $result->fetch_array(MYSQLI_ASSOC)) && (count($list)<$limit))
      $list[] = $row['seriesId'];
    return $list;
  }

  // function to update the temporary movies table
  function updateTempMovies() {
    global $db;
    $db->query("INSERT INTO tempMovies
                SELECT movies.*,
                  collections.collectionTitle AS movieCollectionTitle,
                  collections.collectionPoster AS movieCollectionPoster,
                  GROUP_CONCAT(DISTINCT moviesCompanies.companyId) AS movieCompanies,
                  GROUP_CONCAT(DISTINCT moviesCountries.countryCode) AS movieCountries,
                  GROUP_CONCAT(DISTINCT moviesGenres.genreId) AS movieGenres,
                  GROUP_CONCAT(DISTINCT moviesKeywords.keywordId) AS movieKeywords,
                  GROUP_CONCAT(DISTINCT moviesLanguages.languageCode) AS movieLanguages,
                  GROUP_CONCAT(DISTINCT moviesPersons.personId) AS moviePersons
                FROM movies
                LEFT JOIN collections ON movies.movieCollection=collections.collectionId
                LEFT JOIN moviesCompanies ON movies.movieId=moviesCompanies.movieId
                LEFT JOIN moviesCountries ON movies.movieId=moviesCountries.movieId
                LEFT JOIN moviesGenres ON movies.movieId=moviesGenres.movieId
                LEFT JOIN moviesKeywords ON movies.movieId=moviesKeywords.movieId
                LEFT JOIN moviesLanguages ON movies.movieId=moviesLanguages.movieId
                LEFT JOIN moviesPersons ON movies.movieId=moviesPersons.movieId
                WHERE
                  (movieUpdated IS NOT NULL) AND
                  (movieAdult IS NOT NULL) AND
                  (movieVideo IS NOT NULL) AND
                  (movieVideo <> 1) AND
                  (movieImdb IS NOT NULL) AND
                  (movieImdb <> '')
                GROUP BY movies.movieId
                ON DUPLICATE KEY UPDATE
                  movieTitle=VALUES(movieTitle),
                  movieTagline=VALUES(movieTagline),
                  movieOriginalTitle=VALUES(movieOriginalTitle),
                  movieOriginalLanguage=VALUES(movieOriginalLanguage),
                  movieStatus=VALUES(movieStatus),
                  movieOverview=VALUES(movieOverview),
                  movieReleaseDate=VALUES(movieReleaseDate),
                  movieImdb=VALUES(movieImdb),
                  movieCollection=VALUES(movieCollection),
                  moviePoster=VALUES(moviePoster),
                  movieRuntime=VALUES(movieRuntime),
                  moviePopularity=VALUES(moviePopularity),
                  movieAdult=VALUES(movieAdult),
                  movieVideo=VALUES(movieVideo),
                  movieVoteAverage=VALUES(movieVoteAverage),
                  movieVoteCount=VALUES(movieVoteCount),
                  movieUpdated=VALUES(movieUpdated),
                  movieCollectionTitle=VALUES(movieCollectionTitle),
                  movieCollectionPoster=VALUES(movieCollectionPoster),
                  movieCompanies=VALUES(movieCompanies),
                  movieCountries=VALUES(movieCountries),
                  movieGenres=VALUES(movieGenres),
                  movieKeywords=VALUES(movieKeywords),
                  movieLanguages=VALUES(movieLanguages),
                  moviePersons=VALUES(moviePersons)");
    $db->query("DELETE FROM tempMovies WHERE (movieUpdated IS NULL) OR (movieAdult IS NULL) OR (movieVideo IS NULL) OR (movieVideo=1) OR (movieImdb IS NULL) OR (movieImdb='')");
    return true;
  };

  // function to retrieve information on a movie
  function getMovie($id) {
    global $api;
    if ($result = getJson("https://api.themoviedb.org/3/movie/".$id."?api_key=".$api."&language=en-US&append_to_response=keywords,credits,changes"))
      return $result;
    else
      return false;
  }

  // function to retrieve information on a collection
  function getCollection($id) {
    global $api;
    if ($result = getJson("https://api.themoviedb.org/3/collection/".$id."?api_key=".$api."&language=en-US"))
      return $result;
    else
      return false;
  }

  // download a picture
  function getImage($image) {
    global $imgbase;
    if (!is_dir($imgbase."/".$image[1]."/".$image[2]))
      @mkdir($imgbase."/".$image[1]."/".$image[2],0777,true);
    if (!is_file($imgbase."/".$image[1]."/".$image[2].$image))
      if ($file = @file_get_contents(IMAGE_BASE.$image))
        if (@file_put_contents($imgbase."/".$image[1]."/".$image[2].$image,$file))
          return true;
        else
          return false;
      else
        return false;
    else
      return true;
  }

  // delete a picture
  function delImage($image) {
    global $imgbase;
    if (is_file($imgbase."/".$image[1]."/".$image[2].$image))
      unlink($imgbase."/".$image[1]."/".$image[2].$image);
    return true;
  }

  // update a collection in the database
  function updateCollection($collection) {
    global $db;
    global $thisupdate;
    $row = array();
    $row[] = $collection->id;
    if (isset($collection->poster_path) && strlen($collection->poster_path)) {
      getImage($collection->poster_path);
      $row[] = "'".$db->escape_string($collection->poster_path)."'";
    } else
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

  // update a company in the database
  function updateCompany($company) {
    global $db;
    global $thisupdate;
    $row = array();
    $row[] = $company->id;
    if (isset($company->logo_path) && strlen($company->logo_path)) {
      getImage($company->logo_path);
      $row[] = "'".$db->escape_string($company->logo_path)."'";
    } else
      $row[] = "null"; 
    if (isset($company->origin_country) && strlen($company->origin_country))
      $row[] = "'".$db->escape_string($company->origin_country)."'";
    else
      $row[] = "null"; 
    $row[] = "'".$thisupdate."'";
    if ($db->query("INSERT IGNORE INTO countries (countryCode) VALUES ('".$db->escape_string($company->origin_country)."')"))
      if ($db->query("INSERT INTO companies (companyId, companyLogo, companyCountry, companyUpdated) VALUES (
                        ".implode(', ',$row)."
                      ) ON DUPLICATE KEY UPDATE
                      companyLogo=VALUES(companyLogo),
                      companyCountry=VALUES(companyCountry),
                      companyUpdated=VALUES(companyUpdated)"))
        return true;
      else
        return false;
  }

  // update a country in the database
  function updateCountry($country) {
    global $db;
    global $thisupdate;
    $row = array();
    $row[] = "'".$country->iso_3166_1."'";
    if (isset($country->name) && strlen($country->name))
      $row[] = "'".$db->escape_string($country->name)."'";
    else
      $row[] = "null"; 
    $row[] = "'".$thisupdate."'";
    if ($db->query("INSERT INTO countries (countryCode, countryName, countryUpdated) VALUES (
                      ".implode(', ',$row)."
                    ) ON DUPLICATE KEY UPDATE
                    countryName=VALUES(countryName),
                    countryUpdated=VALUES(countryUpdated)"))
      return true;
    else
      return false;
  }

  // update a genre in the database
  function updateGenre($genre) {
    global $db;
    global $thisupdate;
    $row = array();
    $row[] = "'".$genre->id."'";
    if (isset($genre->name) && strlen($genre->name))
      $row[] = "'".$db->escape_string($genre->name)."'";
    else
      $row[] = "null"; 
    $row[] = "'".$thisupdate."'";
    if ($db->query("INSERT INTO genres (genreId, genreName, genreUpdated) VALUES (
                      ".implode(', ',$row)."
                    ) ON DUPLICATE KEY UPDATE
                    genreName=VALUES(genreName),
                    genreUpdated=VALUES(genreUpdated)"))
      return true;
    else
      return false;
  }

 // update a language in the database
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

  // update a person in the database
  function updatePerson($person) {
    global $db;
    global $thisupdate;
    $row = array();
    $row[] = "'".$person->id."'";
    if (isset($person->gender) && ($person->gender == 1))
      $row[] = "'f'";
    else if (isset($person->gender) && ($person->gender == 2))
      $row[] = "'m'";
    else
      $row[] = "null";
    if (isset($person->profile_path) && strlen($person->profile_path)) {
      getImage($person->profile_path);
      $row[] = "'".$db->escape_string($person->profile_path)."'";
    } else
      $row[] = "null";
    $row[] = "'".$thisupdate."'";
    if ($db->query("INSERT INTO persons (personId, personGender, personPicture, personUpdated) VALUES (
                      ".implode(', ',$row)."
                    ) ON DUPLICATE KEY UPDATE
                    personGender=VALUES(personGender),
                    personPicture=VALUES(personPicture),
                    personUpdated=VALUES(personUpdated)"))
      return true;
    else
      return false;
  }

  // update a movie in the database (including collections, companies, countries, genres, keywords, languages and persons)
  $updated = array();
  $updated['collection'] = array();
  $updated['company'] = array();
  $updated['country'] = array();
  $updated['genre'] = array();
  $updated['language'] = array();
  $updated['person'] = array();
  function updateMovie($movie) {
    global $db;
    global $thisupdate;
    global $updated;
    $db->query("INSERT IGNORE INTO movies (movieId) VALUES (".$movie->id.")");
    $row = array("movieUpdated='".$thisupdate."'");
    if (isset($movie->belongs_to_collection) && is_object($movie->belongs_to_collection) && isset($movie->belongs_to_collection->id) && is_numeric($movie->belongs_to_collection->id)) {
      if (!in_array($movie->belongs_to_collection->id,$updated['collection'])) {
        updateCollection($movie->belongs_to_collection);
        $updated['collection'][] = $movie->belongs_to_collection->id;
      }
      $row[] = "movieCollection=".$movie->belongs_to_collection->id;
    } else
      $row[] = "movieCollection=NULL";
    if (isset($movie->production_countries) && is_array($movie->production_countries) && count($movie->production_countries)) {
      $list = array();
      foreach ($movie->production_countries as $country) {
        if (!in_array($country->iso_3166_1,$updated['country'])) {
          updateCountry($country);
          $updated['country'][] = $country->iso_3166_1;
        }
        $db->query("INSERT IGNORE INTO moviesCountries (movieId, countryCode) VALUES (".$movie->id.", '".$country->iso_3166_1."')");
        $list[] = $country->iso_3166_1;
      }
      $db->query("DELETE FROM movieCountries WHERE movieId =".$movie->id." AND (countryCode NOT IN (".implode(',',$list)."))");
    } else
      $db->query("DELETE FROM moviesCountries WHERE movieId=".$movie->id);
    if (isset($movie->production_companies) && is_array($movie->production_companies) && count($movie->production_companies)) {
      $list = array();
      foreach ($movie->production_companies as $company) {
        if (!in_array($company->id,$updated['company'])) {
          updateCompany($company);
          $updated['company'][] = $company->id;
        }
        $db->query("INSERT IGNORE INTO moviesCompanies (movieId, companyId) VALUES (".$movie->id.", ".$company->id.")");
        $list[] = $company->id;
      }
      $db->query("DELETE FROM movieCompanies WHERE movieId =".$movie->id." AND (companyId NOT IN (".implode(',',$list)."))");
    } else
      $db->query("DELETE FROM moviesCompanies WHERE movieId=".$movie->id);
    if (isset($movie->credits)) {
      $list = array();
      if (isset($movie->credits->cast) && is_array($movie->credits->cast) && count($movie->credits->cast)) {
        foreach ($movie->credits->cast as $person) {
          if (!in_array($person->id,$updated['person'])) {
            updatePerson($person);
            $updated['person'][] = $person->id;
          }
          $row2 = array();
          $row2[] = $movie->id;
          $row2[] = $person->id;
          $row2[] = "'cast'";
          if (isset($person->character) && strlen($person->character))
            $row2[] = "'".$db->escape_string($person->character)."'";
          else
            $row2[] = 'NULL';
          if (isset($person->order) && is_numeric($person->order) && (strlen($person->order) > 0))
            $row2[] = $person->order;
          else
            $row2[] = 'NULL';
          $db->query("INSERT IGNORE INTO moviesPersons (movieId, personId, personType, personCharacter, personOrder)
                      VALUES (".implode(',',$row2).")
                      ON DUPLICATE KEY UPDATE
                      personCharacter=VALUES(personCharacter),
                      personOrder=VALUES(personOrder)");
          $list[] = $person->id;
        }
      }
      if (isset($movie->credits->crew) && is_array($movie->credits->crew) && count($movie->credits->crew)) {
        foreach ($movie->credits->crew as $person) {
          if (!in_array($person->id,$updated['person'])) {
            updatePerson($person);
            $updated['person'][] = $person->id;
          }
          $row2 = array();
          $row2[] = $movie->id;
          $row2[] = $person->id;
          $row2[] = "'crew'";
          if (isset($person->department) && strlen($person->department))
            $row2[] = "'".$db->escape_string($person->department)."'";
          else
            $row2[] = 'NULL';
          if (isset($person->job) && strlen($person->job))
            $row2[] = "'".$db->escape_string($person->job)."'";
          else
            $row2[] = 'NULL';
          $db->query("INSERT IGNORE INTO moviesPersons (movieId, personId, personType, personDepartment, personJob)
                      VALUES (".implode(',',$row2).")
                      ON DUPLICATE KEY UPDATE
                      personCharacter=VALUES(personCharacter),
                      personOrder=VALUES(personOrder)");
          $list[] = $person->id;
        }
      }
      $db->query("DELETE FROM moviePersons WHERE movieId =".$movie->id." AND (personId NOT IN (".implode(',',$list)."))");
      if (!count($list))
        $db->query("DELETE FROM moviesPersons WHERE movieId=".$movie->id);
    } else
      $db->query("DELETE FROM moviesPersons WHERE movieId=".$movie->id);
    if (isset($movie->genres) && is_array($movie->genres) && count($movie->genres)) {
      $list = array();
      foreach ($movie->genres as $genre) {
        if (!in_array($genre->id,$updated['genre'])) {
          updateGenre($genre);
          $updated['genre'][] = $genre->id;
        }
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
        if (!in_array($language->iso_639_1,$updated['language'])) {
          updateLanguage($language);
          $updated['language'][] = $language->iso_639_1;
        }
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
    if (isset($movie->poster_path) && strlen($movie->poster_path)) {
      getImage($movie->poster_path);
      $row[] = "moviePoster='".$db->escape_string($movie->poster_path)."'";
    } else
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
    if (isset($movie->video) && $movie->video)
      $row[] = "movieVideo=1";
    else
      $row[] = "movieVideo=0";
    $db->query("UPDATE movies SET ".implode(', ',$row)." WHERE movieId=".$movie->id);
    return true;
  }

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

?>
