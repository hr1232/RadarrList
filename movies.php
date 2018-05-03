<?php

  header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
  header('Cache-Control: public, no-transform, max-age=3600');
  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // default settings
  if (!isset($_GET['adult']))
    $_GET['adult'] = 0;
  if (!isset($_GET['collections']))
    $GET['collections'] = 1;
  if (isset($_GET['maxage']) && is_numeric($_GET['maxage']))
    $_GET['minyear'] = date('Y')-$_GET['maxage'];
  else if (!isset($_GET['minyear']))
    $_GET['minyear'] = date('Y')-3;
  if (!isset($_GET['maxyear']))
    $_GET['maxyear'] = date('Y');

  // connect to database
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);

  // build and execute sql query
  $sql = "SELECT movieImdb, movieOriginalTitle, movieCollection, moviePoster, GROUP_CONCAT(moviesGenres.genreId) AS movieGenres
          FROM movies
          LEFT JOIN moviesGenres ON movies.movieId=moviesGenres.movieId
          WHERE (movieUpdated IS NOT NULL) AND (movieImdb IS NOT NULL) AND (movieImdb <> '') AND (movieVideo<>1)";
  if (isset($_GET['lang']) && (strlen($_GET['lang']) == 2)) {
    $_GET['lang'] = $db->escape_string($_GET['lang']);
    $sql .= " AND (movieOriginalLanguage IS NOT NULL) AND (movieOriginalLanguage='".$_GET['lang']."')";
  }
  if (isset($_GET['adult']) && is_bool($_GET['adult'])) {
      $sql .= " AND (movieAdult IS NOT NULL)";
    if ($_GET['adult'])
      $sql .= " AND (movieAdult=1)";
    else
      $sql .= " AND (movieAdult=0)";
  }
  if (isset($_GET['minvote']) || isset($_GET['maxvote'])) {
    $sql .= " AND (movieVoteAverage IS NOT NULL) AND (movieVoteCount IS NOT NULL) AND movieVoteCount>=25";
    if (isset($_GET['minvote']) && is_numeric($_GET['minvote'])) {
      $_GET['minvote'] = $db->escape_string($_GET['minvote']);
      $sql .= " AND (movieVoteAverage>=".$_GET['minvote'].")";
    }
    if (isset($_GET['maxvote']) && is_numeric($_GET['maxvote'])) {
      $_GET['maxvote'] = $db->escape_string($_GET['maxvote']);
      $sql .= " AND (movieVoteAverage<=".$_GET['maxvote'].")";
    }
  }
  if (isset($_GET['minyear']) || isset($_GET['maxyear'])) {
    $sql .= " AND (movieReleaseDate IS NOT NULL)";
    if (isset($_GET['minyear']) && is_numeric($_GET['minyear'])) {
      $_GET['minyear'] = $db->escape_string($_GET['minyear']);
      $sql .= " AND (YEAR(movieReleaseDate)>=".$_GET['minyear'].")";
    }
    if (isset($_GET['maxyear']) && is_numeric($_GET['maxyear'])) {
      $_GET['maxyear'] = $db->escape_string($_GET['maxyear']);
      $sql .= " AND (YEAR(movieReleaseDate)<=".$_GET['maxyear'].")";
    }
  }
  $_GET['nogenres'] = '1,2,3,4,5,6,7,8,9,10';
  if (isset($_GET['nogenres'])) {
    $_GET['nogenres'] = $db->escape_string($_GET['nogenres']);
    $sql = "SELECT * FROM (".$sql." GROUP BY movies.movieId) as temp WHERE (movieGenres NOT IN (".$_GET['nogenres']."))";
  } else
    $sql .= " GROUP BY movies.movieId";
  $result = $db->query($sql);

  // make array
  $movies = array();
  $collections = array();
  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    if (isset($row['moviePoster']) && strlen($row['moviePoster']))
      $row['moviePoster'] = 'https://www.heikorichter.name/movieimg/'.$row['moviePoster'][1].'/'.$row['moviePoster'][2].$row['moviePoster'];
    else
      $row['moviePoster'] = null;
    $movies[] = array('title' => $row['movieOriginalTitle'],
                      'imdb_id' => $row['movieImdb'],
                      'poster_url' => $row['moviePoster']);
    if (isset($GET['collections']) && ($GET['collections'] == 1) && is_numeric($row['movieCollection']) && (!in_array($row['movieCollection'],$collections)))
      $collections[] = $row['movieCollection'];
  }

  // get rest of collections from database
  if (count($collections)) {
    $result = $db->query("SELECT movieImdb, movieOriginalTitle, moviePoster
                          FROM movies
                          WHERE (movieUpdated IS NOT NULL) AND (movieImdb IS NOT NULL) AND (movieImdb <> '') AND (movieCollection IN (".implode(',',$collections)."))");
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
      $exist = false;
      for ($i=0;$i<count($movies);$i++) {
        if ($movies[$i]['imdb_id'] == $row['movieImdb']) {
          $exist = true;
          break;
        }
      }
      if (!$exist) {
        if (isset($row['moviePoster']) && strlen($row['moviePoster']))
          $row['moviePoster'] = 'https://www.heikorichter.name/movieimg/'.$row['moviePoster'][1].'/'.$row['moviePoster'][2].$row['moviePoster'];
        else
          $row['moviePoster'] = null;
        $movies[] = array('title' => $row['movieOriginalTitle'],
                          'imdb_id' => $row['movieImdb'],
                          'poster_url' => $row['moviePoster']);
      }
    }
  }

  // encode json
  echo json_encode($movies,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

  // disconnect from database
  $db->close();

?>
