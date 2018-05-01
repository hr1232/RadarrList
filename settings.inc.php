<?php

  // Please do not change anything here; these values will be overwritten
  // in the next pull.
  // You should copy this file to /etc/radarrlist/settings.inc.php and
  // do your individual settings there.

  // database connection settings
  $db['host'] = "localhost";
  $db['user'] = "database";
  $db['pass'] = "password";
  $db['db']   = "radarrlist";
  $db['port'] = null;
  $db['sock'] = "/var/run/mysqld/mysqld.sock";

  // tmdb api to use
  $api = 'api-key';

  // basepath for images
  $imgbase = "/var/lib/radarrlist";
  
  // get settings from file
  if(is_file('/etc/radarrlist/settings.inc.php'))
    include('/etc/radarrlist/settings.inc.php');

?>
