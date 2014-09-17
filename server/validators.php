<?php
function validate_email($email) {
  $isValid = true;
  $atIndex = strrpos($email, "@");

  if (is_bool($atIndex) && !$atIndex)
    $isValid = false;
  else {
    $domain = substr($email, $atIndex+1);
    $local = substr($email, 0, $atIndex);
    $localLen = strlen($local);
    $domainLen = strlen($domain);

    if($localLen < 1 || $localLen > 64)
      $isValid = false;
    else if($domainLen < 1 || $domainLen > 255)
      $isValid = false;
    else if($local[0] == '.' || $local[$localLen-1] == '.')
      $isValid = false;
    else if(preg_match('/\\.\\./', $local))
      $isValid = false;
    else if(!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      $isValid = false;
    else if(preg_match('/\\.\\./', $domain))
      $isValid = false;
    else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local)))
      if(!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local)))
        $isValid = false;

    if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
       $isValid = false;
   }

   return $isValid;
}

function validate_zipcode($zipcode) {
  $isValid = true;

  if(!preg_match("/^[0-9]{5}$/", $zipcode))
    $isValid = false;

  return $isValid;
}

function validate_age($age) {
  $isValid = true;

  if(!is_numeric($age) || intval($age) < 21 || intval($age) > 100)
    $isValid = false;

  return $isValid;
}
?>
