<?php
include 'fbutils.php';
include 'dbutils.php';

//$curr_userid = $_COOKIE['eazybooze_uid'] != '' ? $_COOKIE['eazybooze_uid'] : NULL;

$fb_params = $_REQUEST['signed_request'];
$fb_tracking_data = parse_signed_request($fb_params, $fb_app_secret);
/*
//echo array_key_exists("app_data", $fb_tracking_data) ? $tracking_data["app_data"] : "app_data not found";
if(array_key_exists("app_data", $fb_tracking_data))
  $unique_userid = insert_event_log($fb_tracking_data, "CLICK", $curr_userid);

if(!curr_userid)
  setcookie('eazybooze_uid', $unique_userid, time() + (86700 * 30));
*/
echo "<html>";
include 'portal.html';
echo "  <script>\n";
echo "    var fbtracker = \"" . $fb_params . "\";\n";
echo "  </script>\n";
echo "</html>";
?>
