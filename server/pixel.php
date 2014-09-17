<?php
include 'fbutils.php';
include 'dbutils.php';

$curr_userid = isset($_COOKIE['easybooze_uid']) ? $_COOKIE['easybooze_uid'] : NULL;

$fb_params = $_GET['adtracker'];
$track_event = $_GET['event'];
$fb_tracking_data = parse_signed_request($fb_params, $fb_app_secret);

if(!array_key_exists("app_data", $fb_tracking_data))
  $fb_tracking_data["app_data"] = NULL;

//echo array_key_exists("app_data", $fb_tracking_data) ? $tracking_data["app_data"] : "app_data not found";
if(array_key_exists("app_data", $fb_tracking_data)) {
  $unique_userid = insert_event_log($fb_tracking_data, $track_event, $curr_userid);

  if(!$curr_userid)
    setcookie('easybooze_uid', $unique_userid, time() + (86700 * 30));
}

header("content-type: image/gif");
echo base64_decode("R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");
?>
