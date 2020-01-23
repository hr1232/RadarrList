<?php

  header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
  header('Cache-Control: public, no-transform, max-age=3600');
  require(dirname(__FILE__)."/settings.inc.php");
  require(dirname(__FILE__)."/functions.inc.php");

  // default settings
  if (isset($_GET['maxage']) && is_numeric($_GET['maxage']))
    $_GET['minyear'] = date('Y')-$_GET['maxage'];
  else if (!isset($_GET['minyear']))
    $_GET['minyear'] = date('Y')-3;
  if (!isset($_GET['maxyear']))
    $_GET['maxyear'] = date('Y');

  // connect to database
  $db = new mysqli($db['host'],$db['user'],$db['pass'],$db['db'],$db['port'],$db['sock']);

  // build and execute sql query
  $sql = "SELECT seriesTvdb, seriesOriginalTitle, seriesPoster, GROUP_CONCAT(tvgenreId)
          FROM series
          LEFT JOIN seriesGenres ON seriesGenres.seriesId=series.seriesId
          WHERE (seriesOriginalTitle IS NOT NULL) AND (seriesTvdb IS NOT NULL)";
  if (isset($_GET['lang']) && (strlen($_GET['lang']) == 2)) {
    $_GET['lang'] = $db->escape_string($_GET['lang']);
    $sql .= " AND (seriesOriginalLanguage IS NOT NULL) AND (seriesOriginalLanguage='".$_GET['lang']."')";
  }
  if (isset($_GET['minvote']) || isset($_GET['maxvote'])) {
    $sql .= " AND (seriesVoteAverage IS NOT NULL) AND (seriesVoteCount IS NOT NULL) AND (seriesVoteCount>=10)";
    if (isset($_GET['minvote']) && is_numeric($_GET['minvote'])) {
      $_GET['minvote'] = $db->escape_string($_GET['minvote']);
      $sql .= " AND (seriesVoteAverage>=".$_GET['minvote'].")";
    }
    if (isset($_GET['maxvote']) && is_numeric($_GET['maxvote'])) {
      $_GET['maxvote'] = $db->escape_string($_GET['maxvote']);
      $sql .= " AND (seriesVoteAverage<=".$_GET['maxvote'].")";
    }
  }
  if (isset($_GET['minyear']) || isset($_GET['maxyear'])) {
    $sql .= " AND (seriesStartDate IS NOT NULL)";
    if (isset($_GET['minyear']) && is_numeric($_GET['minyear'])) {
      $_GET['minyear'] = $db->escape_string($_GET['minyear']);
      $sql .= " AND (YEAR(seriesStartDate)>=".$_GET['minyear'].")";
    }
    if (isset($_GET['maxyear']) && is_numeric($_GET['maxyear'])) {
      $_GET['maxyear'] = $db->escape_string($_GET['maxyear']);
      $sql .= " AND (YEAR(seriesStartDate)<=".$_GET['maxyear'].")";
    }
  }
  if (isset($_GET['minseasons']) || isset($_GET['maxseasons'])) {
    $sql .= " AND (seriesSeasons IS NOT NULL)";
    if (isset($_GET['minseasons']) && is_numeric($_GET['minseasons'])) {
      $_GET['minseasons'] = $db->escape_string($_GET['minseasons']);
      $sql .= " AND (seriesSeasons>=".$_GET['minseasons'].")";
    }
    if (isset($_GET['maxseasons']) && is_numeric($_GET['maxseasons'])) {
      $_GET['maxseasons'] = $db->escape_string($_GET['maxseasons']);
      $sql .= " AND (seriesSeasons)<=".$_GET['maxseasons'].")";
    }
  }
  if (isset($_GET['minepisodes']) || isset($_GET['maxepisodes'])) {
    $sql .= " AND (seriesEpisodes IS NOT NULL)";
    if (isset($_GET['minepisodes']) && is_numeric($_GET['minepisodes'])) {
      $_GET['minepisodes'] = $db->escape_string($_GET['minepisodes']);
      $sql .= " AND (seriesEpisodes>=".$_GET['minepisodes'].")";
    }
    if (isset($_GET['maxepisodes']) && is_numeric($_GET['maxepisodes'])) {
      $_GET['maxepisodes'] = $db->escape_string($_GET['maxepisodes']);
      $sql .= " AND (seriesEpisodes)<=".$_GET['maxepisodes'].")";
    }
  }
  if (isset($_GET['genres'])) {
    $_GET['genres'] = explode(',',$_GET['genres']);
    $sql .= " AND (";
    $i = 0;
    foreach($_GET['genres'] as $genre)
      if (is_numeric($genre)) {
        if ($i++ > 0)
          $sql .= " OR";
        $sql .= " (FIND_IN_SET($genre, tvgenreId)=1)";
      }
    $sql .= ")";
  }
  if (isset($_GET['nogenres'])) {
    $_GET['nogenres'] = explode(',',$_GET['nogenres']);
    foreach($_GET['nogenres'] as $genre)
      if (is_numeric($genre))
        $sql .= " AND (FIND_IN_SET($genre, tvgenreId)=0)";
  }
  $sql .= " GROUP BY series.seriesId";
  $result = $db->query($sql);

  // make array
  $series = array();
  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    if (isset($row['seriesPoster']) && strlen($row['seriesPoster']))
      $row['seriesPoster'] = 'https://www.heikorichter.name/movieimg/'.$row['seriesPoster'][1].'/'.$row['seriesPoster'][2].$row['seriesPoster'];
    else
      $row['seriesPoster'] = null;
    if (isset($row['seriesGenres']) && strlen($row['seriesGenres'])) {
      $row['seriesGenres'] = explode(',',$row['seriesGenres']);
      for ($i=0;$i<count($row['seriesGenres']);$i++)
        $row['seriesGenres'][$i] = intval($row['seriesGenres'][$i]);
    } else
      $row['seriesGenres'] = null;
    $series[] = array('title' => $row['seriesOriginalTitle'],
                      'tvdb_id' => $row['seriesTvdb'],
                      'poster_url' => $row['seriesPoster']);
  }

  // encode json
  echo json_encode($series,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

  // disconnect from database
  $db->close();

?>
