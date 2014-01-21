<?php

session_start();

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . 'lib/');

include 'libSQL.php';
require 'libCheckSession.php';

foreach ($_POST as $k=>$v) 
  {
  $_POST[$k] = htmlentities($v, ENT_QUOTES);
  }  
  
if($_POST['utgt'] !== '' &&  $_POST['utgt'] !== null)
  {
  mysqli_query($con, "DELETE FROM Relationships WHERE party_i='" . $_POST['utgt'] . "'");
  $sqld="DELETE FROM Userlist WHERE uname='" . $_POST['utgt'] . "'";
  if (!mysqli_query($con,$sqld))
    {
    die('Error: ' . mysqli_error($con));
    }
  }
  
$pup = mysqli_query($con,"SELECT * FROM Userlist WHERE owner='" . $_SESSION['account'] . "' AND primary_page='y'");
if(mysqli_num_rows($pup) < 1)
  $p = 'y';
else
  $p = 'n';

$sql="INSERT INTO Userlist (uname, altnames, class, race, gender, looking, platform, bio, lookingbio, contactinfo, imglink, owner, primary_page)
VALUES
('" . $_POST['uname'] . 
"', '" .  $_POST['altnames'] . 
"', '" . $_POST['class'] . 
"', '" . $_POST['race'] . 
"', '" . $_POST['gender'] . 
"', '" . $_POST['looking'] . 
"', '" . $_POST['platform'] .  
"', '" . $_POST['bio'] . 
"', '" . $_POST['lookingbio'] . 
"', '" . $_POST['contactinfo'] . 
"', '" . $_POST['imglink'] . 
"', '" . $_SESSION['account'] . 
"', '" . $p . "')";

if (!mysqli_query($con,$sql))
  {    
  die('Error: ' . mysqli_error($con));
  }

echo "Updated User Page... \n";

foreach($_POST as $k=>$v)
{
  if (strpos($k, 'friend') !== false && strpos($k, 'name') !== false)
  {
    $set = substr($k, 0, strpos($k, '_'));
    $arr = array('party_i' => $_POST['uname'], 'party_r' => $_POST[$set . '_name']);
    $sqlr="INSERT INTO Relationships (party_i, party_r, type)
    VALUES
    ('" . $arr['party_i'] . "', '" . $arr['party_r'] . "', 'f')";
    if (!mysqli_query($con,$sqlr))
      {
      die('Error: ' . mysqli_error($con));
      }
  }  if (strpos($k, 'enemy') !== false && strpos($k, 'name') !== false)
  {
    $set = substr($k, 0, strpos($k, '_'));
    $arr = array('party_i' => $_POST['uname'], 'party_r' => $_POST[$set . '_name']);
    $sqlr="INSERT INTO Relationships (party_i, party_r, type)
    VALUES
    ('" . $arr['party_i'] . "', '" . $arr['party_r'] . "', 'e')";
    if (!mysqli_query($con,$sqlr))
      {
      die('Error: ' . mysqli_error($con));
      }
  }
  if (strpos($k, 'sc') !== false && strpos($k, 'name') !== false)
  {
    $set = substr($k, 0, strpos($k, '_'));
    $arr = array('party_i' => $_POST['uname'], 'party_r' => $_POST[$set . '_name']);
    $sqlr="INSERT INTO Relationships (party_i, party_r, type)
    VALUES
    ('" . $arr['party_i'] . "', '" . $arr['party_r'] . "', 'sc')";
    if (!mysqli_query($con,$sqlr))
      {
      die('Error: ' . mysqli_error($con));
      }
  }
} 
    
echo "Updated Relationships...  \n Update successful!  Redirecting...";
echo '<meta http-equiv="refresh" content="0; url=/userlist/user.php?uname=' . $_POST['uname'] . '" />';

mysqli_close($con);

?>                                                                                             