<?php
$user = "portal";
$password = "portal123";
$database = "user_events";
$db_host = "localhost";

function insert_event_log($fb_tracking_data, $event_type, $userid=NULL) {
  global $user, $password, $database, $db_host;

  mysql_connect($db_host, $user, $password);
  @mysql_select_db($database) or die("Unable to select database");

  if(!$userid) {
    mysql_query("INSERT INTO users_info (email, zipcode, age) VALUES(NULL, NULL, NULL)");
    $userid = mysql_insert_id();
  }

  $sql_query = sprintf("INSERT INTO events_log (event_timestamp,
                                                visitor_liked,
                                                visitor_country,
                                                visitor_locale,
                                                visitor_age,
                                                event_type,
                                                user_id,
                                                ad_id,
                                                client_id)
                        VALUES(%s, %b, '%s', '%s', %d, '%s', %d, %d, '%s')",
                       $fb_tracking_data["issued_at"],
                       $fb_tracking_data["page"]["liked"],
                       $fb_tracking_data["user"]["locale"],
                       $fb_tracking_data["user"]["country"],
                       $fb_tracking_data["user"]["age"]["min"],
                       $event_type,
                       $userid,
                       $fb_tracking_data["app_data"],
                       $fb_tracking_data["page"]["id"]);

  mysql_query($sql_query);
  echo die($sql_query);

  $sql_err = mysql_error();
  if($sql_err)
    die($sql_err);

  return $userid;
  //return mysql_insert_id();
}

function insert_user_info($user_info, $userid=NULL) {
  global $user, $password, $database, $db_host;

  mysql_connect($db_host, $user, $password);
  @mysql_select_db($database) or die( "Unable to select database");

  if($userid)
    $sql_query = sprintf("UPDATE users_info
                          SET email = '%s', zipcode = '%s', age = %d
                          WHERE user_id = %d",
                         $user_info["email"],
                         $user_info["zipcode"],
                         $user_info["age"],
                         $userid);
  else
    $sql_query = sprintf("INSERT INTO users_info (email, zipcode, age)
                          VALUES('%s', '%s', %d)",
                       $user_info["email"],
                       $user_info["zipcode"],
                       $user_info["age"]);

  mysql_query($sql_query);
  //echo $sql_query;

  $sql_err = mysql_error();
  if($sql_err)
    die($sql_err);

  if(!$userid)
    $userid = mysql_insert_id();

  return $userid;
  //return mysql_insert_id();
}
?>
