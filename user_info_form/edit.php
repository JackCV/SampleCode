<?php

session_start();

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . 'lib/');

include 'libSQL.php';
include 'libNavbar.php';
include 'libMakeList.php';
include 'libPPCode.php';

$upage = mysqli_query($con,"SELECT * FROM Userlist WHERE uname='" . $_POST['utgt'] . "'");
$data = mysqli_fetch_array($upage);

echo '<!DOCTYPE html>
<html>
<head>                                                                                                                                                          
<title>Edit User Page</title>
<link rel="stylesheet" type="text/css" href="/global.css">
<style>
span.fh {
  font-weight:bold;
}
input.fi{
  margin-left:1%;
}
textarea{
  margin-left:1%;
}
</style>
<script src="/js/validatePage.js"></script>
<script>
function populateRelationships()
{
';
echo '  ';
$i = 0;
$friends = mysqli_query($con,"SELECT * FROM Relationships WHERE party_i='" . $_POST['utgt'] . "' AND type='f'");
while($friend = mysqli_fetch_array($friends))
{
  $i++;
  echo 'addfriend();
  document.getElementsByName("friend' . $i . '_name")[0].value = "' . $friend['party_r'] . '";
  ';
  if($friend['statusc'] == 'None') echo 'document.getElementsByName("friend' . $i . '_statusc")[0].selectedIndex = 0;
  ';
  if($friend['statusc'] == 'RP Only') echo 'document.getElementsByName("friend' . $i . '_statusc")[0].selectedIndex = 1;
  ';
  if($friend['statusc'] == 'OOC Online') echo 'document.getElementsByName("friend' . $i . '_statusc")[0].selectedIndex = 2;
  ';
  if($friend['statusc'] == 'IRL') echo 'document.getElementsByName("friend' . $i . '_statusc")[0].selectedIndex = 3;
  ';
  if($friend['statusd'] == 'None') echo 'document.getElementsByName("friend' . $i . '_statusd")[0].selectedIndex = 0;
  ';
  if($friend['statusd'] == 'RP Only') echo 'document.getElementsByName("friend' . $i . '_statusd")[0].selectedIndex = 1;
  ';
  if($friend['statusd'] == 'OOC Online') echo 'document.getElementsByName("friend' . $i . '_statusd")[0].selectedIndex = 2;
  ';
  if($friend['statusd'] == 'IRL') echo 'document.getElementsByName("friend' . $i . '_statusd")[0].selectedIndex = 3;
  ';  
}
$i = 0;
$enemies = mysqli_query($con,"SELECT * FROM Relationships WHERE party_i='" . $_POST['utgt'] . "' AND type='e'");
while($enemy = mysqli_fetch_array($enemies))
{
  $i++;
  echo 'addenemy();
  document.getElementsByName("enemy' . $i . '_name")[0].value = "' . $enemy['party_r'] . '";
  ';
  if($enemy['statusc'] == 'None') echo 'document.getElementsByName("enemy' . $i . '_statusc")[0].selectedIndex = 0;
  ';
  if($enemy['statusc'] == 'RP Only') echo 'document.getElementsByName("enemy' . $i . '_statusc")[0].selectedIndex = 1;
  ';
  if($enemy['statusc'] == 'OOC Online') echo 'document.getElementsByName("enemy' . $i . '_statusc")[0].selectedIndex = 2;
  ';
  if($enemy['statusc'] == 'IRL') echo 'document.getElementsByName("enemy' . $i . '_statusc")[0].selectedIndex = 3;
  ';
  if($enemy['statusd'] == 'None') echo 'document.getElementsByName("enemy' . $i . '_statusd")[0].selectedIndex = 0;
  ';
  if($enemy['statusd'] == 'RP Only') echo 'document.getElementsByName("enemy' . $i . '_statusd")[0].selectedIndex = 1;
  ';
  if($enemy['statusd'] == 'OOC Online') echo 'document.getElementsByName("enemy' . $i . '_statusd")[0].selectedIndex = 2;
  ';
  if($enemy['statusd'] == 'IRL') echo 'document.getElementsByName("enemy' . $i . '_statusd")[0].selectedIndex = 3;
  ';  
}
$i = 0;
$scs = mysqli_query($con,"SELECT * FROM Relationships WHERE party_i='" . $_POST['utgt'] . "' AND type='sc'");
while($sc = mysqli_fetch_array($scs))
{
  $i++;
  echo 'addsc();
  document.getElementsByName("sc' . $i . '_name")[0].value = "' . $sc['party_r'] . '";
  ';  
}

echo '}
</script>
</head>

<body onload="populateRelationships()">
<div class="tctrpage">
<div class="tctrtop">'; writeTopbar(); echo '</div>
<div class="tctrnav">'; writeNavbar(); echo '</div>
<div class="tctrcontent">
<p>
<span class="subhead">Edit User Page - ' . $_POST['utgt'] . '</span>
</p>

<hr>

<form name="page" onsubmit="return checkForm();" action="doEdit.php" method="post" enctype="multipart/form-data">

<input type="hidden" name="utgt" value="' . $_POST['utgt'] . '">

<p>
Enter the username you use most frequently on IRC.  This will be used in the link to your profile page.
</p>

<p>
<span id="tuname" class="fh">Primary Username:</span><br>
<input type="text" name="uname" class="fi" value="' . $data['uname'] . '">
</p>

<hr>

<p>
Enter any old or alternate usernames you use.  List each name on a new line.
</p>

<p>
<span id="taltnames" class="fh">Alternate Usernames:</span><br>
<textarea cols="40" rows="5" name="altnames" wrap="virtual">' . $data['altnames'] . '</textarea>
</p>

<hr>

<p>All information is optional.  Blank fields will not be displayed on your user page.</p>

<p>
<span id="timglink" class="fh">Profile Image Link:</span><br>
<input type="text" name="imglink" class="fi" value="' . $data['imglink'] . '">
</p>

<p>
<span id="trace" class="fh">Race:</span><br>
<input type="text" name="race" class="fi" value="' . $data['race'] . '">
</p>

<p>
<span id="tgender" class="fh">Gender:</span><br>
<input type="text" name="gender" class="fi" value="' . $data['gender'] . '">
</p>

<p>
<span id="tclass" class="fh">Class:</span><br>
<input type="text" name="class" class="fi" value="' . $data['gender'] . '">
</p>

<p>
<span id="terp" class="fh">Platform of Choice:</span><br>
  <select name="platform" style="margin-left:1%">';
    createSelectedList('<option value="Forum">Forum</option>
    <option value="IRC">IRC</option>', $data, 'platform');
  echo '</select>
</p>

<hr>

<p>
<span id="tlooking" class="fh">Are you looking for a campaign to join?</span><br>
  <select name="looking" style="margin-left:1%">';
    createSelectedList('<option value="No">No</option>
    <option value="Maybe">Maybe</option>
    <option value="Yes">Yes</option>', $data, 'looking');
  echo '</select>
</p>

<hr style="border: 1px dashed black">

<div id="efriend"></div>
<a class="jslink" style="margin-left:1%;" onclick="addfriend()">Add Friend</a>

<hr style="border: 1px dashed black">

<div id="eenemy"></div>
<a class="jslink" style="margin-left:1%;" onclick="addenemy()">Add Enemy</a>

<hr style="border: 1px dashed black">

<div id="esc"></div>
<a class="jslink" style="margin-left:1%;" onclick="addsc()">Add Secondary Character</a>

<hr>

<div>The following three entries will accept up to 1,000 characters.  You can use ';

writePPCodeInfo();

echo ' to format them.</div>

<p>
<span id="tbio" class="fh">User Bio:</span><br>
<textarea cols="40" rows="5" name="bio" wrap="virtual">' . $data['bio'] . '</textarea>
</p>

<p>
<span id="tlookingbio" class="fh">What type(s) of campaign(s) are you interested in?</span><br>
<textarea cols="40" rows="5" name="lookingbio" wrap="virtual">' . $data['lookingbio'] . '</textarea>
</p>

<p>
<span id="tcontactinfo" class="fh">List any additional contact info you want to provide:</span><br>
<textarea cols="40" rows="5" name="contactinfo" wrap="virtual">' . $data['contactinfo'] . '</textarea>
</p>

<input type="submit" value="Submit" class="fi">

</form>
</div></div>
</body>
</html>';

?>