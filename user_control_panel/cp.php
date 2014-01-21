<?php

session_start();

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . 'lib/');

include 'libSQL.php';
include 'libNavbar.php';
include 'libUserframe.php';
include 'libMakeList.php';

echo '<!DOCTYPE html>
<html>
<head>
<title>Account Control Panel</title>
<link rel="stylesheet" type="text/css" href="/global.css">
<style>
td {
  vertical-align:middle;
}
td.ltd {
  text-align:center;
}
span.fh {
  font-weight:bold;
}
input.fi{
  margin-left:1%;
}
</style>
<script src="/js/validateAccount.js">
</script>
</head>
<body>
<div class="tctrpage">
<div class="tctrtop">'; writeTopbar(); echo '</div>
<div class="tctrnav">'; writeNavbar(); echo '</div>
<div class="tctrcontent">';

$acc = mysqli_query($con,"SELECT * FROM Accounts WHERE email='" . $_SESSION['account'] . "'");
$data = mysqli_fetch_array($acc);

if($_SESSION['loginType'] == 'activate')
  {
  echo '<span class="subhead">Activate Account</span>
  <p>Check your email for your registration code and enter it here.</p>
  <form action="doActivate.php" method="post">
  Activation Code: <input type="text" name="actcode">
  <br>
  <input type="submit" value="Activate">
  </form>
  <span style="font-size:0.75em;"><a href="resend.php">Resend activation email.</a></span>';
  }
else if($_SESSION['loginType'] == 'recovery')
  {
  echo '<span class="subhead">Recover Account</span>
  <p>Your account has been locked.<br>
  Check your email for your recovery code and enter it here to reactivate your account.<br>
  Do not navigate away from this page.  Doing so will require you to restart the recovery process.</p>
  <form name="recover" onsubmit="return checkRecovery();" action="doRecoverAccount.php" method="post">
  Recovery Code: <input type="text" name="reccode">
  <br>
  New Password: <input type="password" name="pass">
  <br>
  Confirm New Password: <input type="password" name="cpass">
  <br>
  <input type="submit" value="Reactivate">
  </form>';
  }
else if($_SESSION['loginType'] == 'locked')
  {
  echo '<span class="subhead">Account Locked</span>
  <p>Your account has been locked by an administrator.<br>
  You should have received an email indicating the reason for this action.</p>
  <p>Contact Perun or Starlight on IRC for details.';
  }
else
  {
  echo '<p><span class="subhead">User Pages</span></p>
  <hr>
  <p>';
  
  $upages = mysqli_query($con,"SELECT * FROM Userlist WHERE owner='" . $_SESSION['account'] . "'");
  if(mysqli_num_rows($upages) == 0)
    {
    echo 'You have no active user pages.
    </p>';
    }
  else
    {
    $pupq = mysqli_query($con,"SELECT * FROM Userlist WHERE owner='" . $_SESSION['account'] . "' AND primary_page='y'");
    $pupa = mysqli_fetch_array($pupq);
    if($pupa['imglink'] !== '')
      $img = $pupa['imglink'];
    else
      $img = $s_DefaultPic;
    echo '<p>Primary User Page - You will use this display name and avatar on the site.
    </p>
    <table style="border:1px solid black; width:60%"><tr>
    <td style="width:5%;"><div style="width:100%; height:32px;"><img src="' . 
    $img . 
    '" alt="profile" style="max-height:100%; max-width:100%;"></div></td>
    <td style="width:35%;">';
    writeUserframe($pupa['uname']);
    echo '</td><td style="width:5%;" class="ltd"><form action="edit.php" method="post"><input type="hidden" name="utgt" value="' . $pupa['uname'] . '"/><input type="submit" name="submit" value="Edit" class="phplink"/></form></td>
    <td style="width:5%;" class="ltd"><form action="delete.php" method="post"><input type="hidden" name="utgt" value="' . $pupa['uname'] . '"/><input type="submit" name="submit" value="Delete" class="phplink"/></form></td>
    <td style="width:10%;" class="ltd"><span style="color:#777777; text-decoration:underline; font-size:0.8em">Mark as Primary</span></td></tr></table>';
    
    $upq = mysqli_query($con,"SELECT * FROM Userlist WHERE owner='" . $_SESSION['account'] . "' AND primary_page='n'");
    if(mysqli_num_rows($upq) > 0)
      {
      echo '<p>Secondary User Pages</p>
      <table style="border:1px solid black; width:60%">';
      while($upa = mysqli_fetch_array($upq))
        {
        if($upa['imglink'] !== '')
        $img = $upa['imglink'];
        else
          $img = $s_DefaultPic;
        echo '<tr>
        <td style="width:5%;"><div style="width:100%; height:32px;"><img src="' . 
        $img . 
        '" alt="profile" style="max-height:100%; max-width:100%;"></div></td>
        <td style="width:35%;">';
        writeUserframe($upa['uname']);
        echo '</td><td style="width:5%;" class="ltd"><form action="edit.php" method="post"><input type="hidden" name="utgt" value="' . $upa['uname'] . '"/><input type="submit" name="submit" value="Edit" class="phplink"/></form></td>
        <td style="width:5%;" class="ltd"><form action="delete.php" method="post"><input type="hidden" name="utgt" value="' . $upa['uname'] . '"/><input type="submit" name="submit" value="Delete" class="phplink"/></form></td>
        <td style="width:10%;" class="ltd"><form action="doPrimary.php" method="post"><input type="hidden" name="utgt" value="' . $upa['uname'] . '"/><input type="submit" name="submit" value="Mark as Primary" class="phplink"/></form></td></tr>';
        }
      echo '</table>';
      }
    }
  echo '<p>
  <a href="add.php">Add a new User Page</a>
  </p>
  <p>
  <span class="subhead">Update Account Information</span>
  </p>

  <hr>

  <form name="account" onsubmit="return checkForm();" action="doEditAccount.php" method="post" enctype="multipart/form-data">

  <p>
  The following information is required.  Your password will not be changed unless you enter a new one.
  </p>

  <p>
  <span id="temail" class="fh">E-mail:</span><br>
  <input type="email" name="email" class="fi"  value="' . $data['email'] . '">
  </p>

  <p>
  <span id="tpass" class="fh">Password:</span><br>
  <input type="password" name="pass" class="fi">
  </p>

  <p>
  <span id="tcpass" class="fh">Confirm Password:</span><br>
  <input type="password" name="cpass" class="fi">
  </p>

  <hr>

  <p>
  The following information is optional.  Anything you enter will be displayed publicly on any profile pages you create.
  </p>

  <p>
  <span id="tloc" class="fh">Location:</span><br>
  <input type="text" name="loc" class="fi" value="' . $data['loc'] . '">
  </p>

  <p>
  <span id="tbirthday" class="fh">Birthday:</span><br>
    <select name="bdayMonth" style="margin-left:1%">';
        createSelectedList('<option value="N"> - Month - </option>
        <option value="1">January</option>
        <option value="2">February</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>', $data, 'bdayMonth');
    echo '</select>
    <select name="bdayDay">';
        createSelectedList('<option value="N"> - Day - </option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>', $data, 'bdayDay');
    echo '</select>
  <select name="bdayYear">';
    createSelectedList('<option value="N"> - Year - </option>
    <option value="2013">2013</option>
    <option value="2012">2012</option>
    <option value="2011">2011</option>
    <option value="2010">2010</option>
    <option value="2009">2009</option>
    <option value="2008">2008</option>
    <option value="2007">2007</option>
    <option value="2006">2006</option>
    <option value="2005">2005</option>
    <option value="2004">2004</option>
    <option value="2003">2003</option>
    <option value="2002">2002</option>
    <option value="2001">2001</option>
    <option value="2000">2000</option>
    <option value="1999">1999</option>
    <option value="1998">1998</option>
    <option value="1997">1997</option>
    <option value="1996">1996</option>
    <option value="1995">1995</option>
    <option value="1994">1994</option>
    <option value="1993">1993</option>
    <option value="1992">1992</option>
    <option value="1991">1991</option>
    <option value="1990">1990</option>
    <option value="1989">1989</option>
    <option value="1988">1988</option>
    <option value="1987">1987</option>
    <option value="1986">1986</option>
    <option value="1985">1985</option>
    <option value="1984">1984</option>
    <option value="1983">1983</option>
    <option value="1982">1982</option>
    <option value="1981">1981</option>
    <option value="1980">1980</option>
    <option value="1979">1979</option>
    <option value="1978">1978</option>
    <option value="1977">1977</option>
    <option value="1976">1976</option>
    <option value="1975">1975</option>
    <option value="1974">1974</option>
    <option value="1973">1973</option>
    <option value="1972">1972</option>
    <option value="1971">1971</option>
    <option value="1970">1970</option>
    <option value="1969">1969</option>
    <option value="1968">1968</option>
    <option value="1967">1967</option>
    <option value="1966">1966</option>
    <option value="1965">1965</option>
    <option value="1964">1964</option>
    <option value="1963">1963</option>
    <option value="1962">1962</option>
    <option value="1961">1961</option>
    <option value="1960">1960</option>
    <option value="1959">1959</option>
    <option value="1958">1958</option>
    <option value="1957">1957</option>
    <option value="1956">1956</option>
    <option value="1955">1955</option>
    <option value="1954">1954</option>
    <option value="1953">1953</option>
    <option value="1952">1952</option>
    <option value="1951">1951</option>
    <option value="1950">1950</option>', $data, 'bdayYear');
  echo '</select> 
  </p>

  <p>
  <span id="ttimezone" class="fh">Timezone:</span><br>
  <select name="timezone" style="margin-left:1%;">';
      createSelectedList('<option selected value="N"> - Timezone - </option>
      <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
      <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
      <option value="-10.0">(GMT -10:00) Hawaii</option>
      <option value="-9.0">(GMT -9:00) Alaska</option>
      <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
      <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
      <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
      <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
      <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
      <option value="-3.5">(GMT -3:30) Newfoundland</option>
      <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
      <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
      <option value="-1.0">(GMT -1:00) Azores, Cape Verde Islands</option>
      <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
      <option value="1.0">(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
      <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
      <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
      <option value="3.5">(GMT +3:30) Tehran</option>
      <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
      <option value="4.5">(GMT +4:30) Kabul</option>
      <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
      <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
      <option value="5.75">(GMT +5:45) Kathmandu</option>
      <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
      <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
      <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
      <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
      <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
      <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
      <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
      <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>', $data, 'timezone');
  echo '</select>
  </p>

  <hr>

  <p>
  <span id="trpic" class="fh">Real Life Photo Link:</span><br>
  <input type="text" name="rpic" class="fi"  value="' . $data['rpic'] . '">
  </p>

  <input type="submit" value="Submit" class="fi">

  </form>
  
  <p>
  <a href="deleteAccount.php">Delete Account</a>
  </p>';
  }

echo '</div></div>
</body>
</html>';
?>