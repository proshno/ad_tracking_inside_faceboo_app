<?php
include 'fbutils.php';
include 'dbutils.php';
include 'validators.php';

$client_api_url = "http://192.168.0.1/api/submit/"; //user your own api endpoint

$curr_userid = isset($_COOKIE['easybooze_uid']) ? $_COOKIE['easybooze_uid'] : NULL;

//echo $curr_userid;

$fb_tracking_data = parse_signed_request($_POST["adtracker"], $fb_app_secret);
if(!array_key_exists("app_data", $fb_tracking_data))
  $fb_tracking_data["app_data"] = NULL;

//echo var_dump($fb_tracking_data);

//$email = "";
//$email = "abc";
//$email = "abc@xyz";

$email = $_POST['email'];

//$zipcode = "";
//$zipcode = "0";
//$zipcode = "100000";

$zipcode = $_POST['zipcode'];

//$age = -1;
//$age = 0;
//$age = 20;
//$age = 101;

$age = $_POST['age'];

function rollback($err_msg) {
  echo "<html>\n";
  include 'portal.html';
  echo "  <script>\n";
  echo "    var fbtracker = \"" . $_POST["adtracker"] . "\";\n";
  echo "    alert(\"" . $err_msg . "\");\n";
  echo "  </script>\n";
  echo "</html>";
}

$errs = array();
if(!validate_email($email))
  array_push($errs, "email");
if(!validate_zipcode($zipcode))
  array_push($errs, "zipcode");
if(!validate_age($age))
  array_push($errs, "age");

if($errs) {
  $err_msg = "Invalid " . implode(", ", $errs) . "!";
  rollback($err_msg);
}
else if(array_key_exists("app_data", $fb_tracking_data)) {
  //$client_api_req = $client_api_url;
  //$client_api_req = $client_api_url . "?email=abc@xyz.com";
  //$client_api_req = $client_api_url . "?zipcodel=1";
  //$client_api_req = $client_api_url . "?age=15";

  $client_api_req = $client_api_url. "?email=" . $email . "&zipcode=" . $zipcode . "&age=" . $age;
  $client_api_res = json_decode(utf8_encode(file_get_contents($client_api_req)));

  //echo var_dump($client_api_res);

  if($client_api_res->error)
    rollback($client_api_res->error);
  else {
    $event_id = insert_event_log($fb_tracking_data, "CONVERSION", $curr_userid);
    insert_user_info($_POST, $curr_userid);
    header("Location: " . $client_api_res->redirect);
  }
}
?>
