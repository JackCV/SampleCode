<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . 'lib/');

include 'libSQL.php';
include 'libInput.php';
include 'libUserframe.php';
include 'libPPCode.php';
include_once 'libGlobal.php';

$sort=mysqli_real_escape_string($con, $_GET['sort']);
if ($sort == '' || $sort == null) $sort = 'uname';
$order=mysqli_real_escape_string($con, $_GET['order']);
if ($order !== 'asc' && $order !== 'desc') $order = 'asc';
$bkgdimg = $s_TableHeaderAsc;
if ($order == 'desc') $bkgdimg = $s_TableHeaderDesc;

$srchname = mysqli_real_escape_string($con, $_GET['srchname']);

echo '<table class="ul"><tr>
<th class="ho">&nbsp</th>
<th class="ho" id="sname" onclick="manageSort(\'uname\', this)"';
if($sort == 'uname')
    echo ' style="background-image:url(\'' . $bkgdimg . '\');"'; 
echo '><a class="jslink" style="color:black;">Name</a></th>
<th class="he" id="sgender" onclick="manageSort(\'gender\', this)"';
if($sort == 'gender')
    echo ' style="background-image:url(\'' . $bkgdimg . '\');"';
echo '><a class="jslink" style="color:black;">Gender</a></th>
<th class="ho" id="srace" onclick="manageSort(\'race\', this)"';
if($sort == 'race')
    echo ' style="background-image:url(\'' . $bkgdimg . '\');"';
echo '><a class="jslink" style="color:black;">Race</a></th>
<th class="he" id="srole" onclick="manageSort(\'class\', this)"';
if($sort == 'role')
    echo ' style="background-image:url(\'' . $bkgdimg . '\');"';
echo '><a class="jslink" style="color:black;">Class</a></th>
<th class="ho" id="slooking" onclick="manageSort(\'looking\', this)"';
if($sort == 'looking') 
    echo ' style="background-image:url(\'' . $bkgdimg . '\');"';
echo '><a class="jslink" style="color:black;">Looking?</a></th>
<th class="he" id="stastuso" onclick="manageSort(\'platform\', this)"';
if($sort == 'statuso') 
    echo ' style="background-image:url(\'' . $bkgdimg . '\');"';
echo '><a class="jslink" style="color:black;">Platform</a></th>
<th class="ho" id="serp" onclick="manageSort(\'forum\', this)"';
if($sort == 'erp') 
    echo ' style="background-image:url(\'' . $bkgdimg . '\');"';
echo '><a class="jslink" style="color:black;">Forum User?</a></th>
<th class="he">Friends/Enemies</th>
<th class="ho">Secondary Characters</th>
<th class="he">Old/Alternate Names</th></tr>';

$query = "SELECT * FROM Userlist WHERE NOT uname=''";
if ($srchname !== '' && $srchname !== null)
  $query .= " AND (uname LIKE '%" . $srchname . "%' OR altnames LIKE '%" . $srchname . "%')";
$query .=  " ORDER BY " . $sort . " " . $order . ", uname asc";

$data = mysqli_query($con, $query);

$rowtrack = true;
$ucount = 0;

while($usr = mysqli_fetch_array($data))
  {
  $ucount++;
  $friends = array();
  $friendq = mysqli_query($con, "SELECT * FROM Relationships WHERE party_i='" . $usr['uname'] . "' AND type='f'");
  $i = 0;
  while($afriend = mysqli_fetch_array($friendq))
    {
    $friends[$i] = $afriend['id'];
    $i++;
    }
  $enemies = array();
  $enemyq = mysqli_query($con, "SELECT * FROM Relationships WHERE party_i='" . $usr['uname'] . "' AND type='e'");
  $i = 0;
  while($aenemy = mysqli_fetch_array($enemyq))
    {
    $enemies[$i] = $aenemy['id'];
    $i++;
    };
  $scs = array();
  $scq = mysqli_query($con, "SELECT * FROM Relationships WHERE party_i='" . $usr['uname'] . "' AND type='sc'");
  $i = 0;
  while($asc = mysqli_fetch_array($scq))
    {
    $scs[$i] = $asc['id'];
    $i++;
    }
  $altnames = explode("\n", $usr['altnames']);
  foreach($altnames as $k => $v) 
    {
      if(strlen($v) > 15)
        $altnames[$k] = substr($v, 0, 15) . '...';
    }
  $pimg = $usr['imglink'];
  if ($pimg == '') $pimg = $s_DefaultPic;
  $i = 0;
  $styleCellOdd;
  $styleCellEven;
  if ($rowtrack)
    {
    $styleCellOdd = 'reco';
    $styleCellEven = 'rece';
    }
  else
    {
    $styleCellOdd = 'roco';
    $styleCellEven = 'roce';
    }
    echo '<tr>
    <td style="width:5%;" class="ho"><div style="width:100%; height:32px;"><img src="' . 
    $pimg . 
    '" alt="profile" style="max-height:100%; max-width:100%;"></div>
    <td class="' . $styleCellOdd . '">';
    writeUserframe($usr['uname']);
    echo '</td>
    <td class="' . $styleCellEven . '">' . $usr['gender'] . '</td>
    <td class="' . $styleCellOdd . '">' . $usr['race'] . '</td>
    <td class="' . $styleCellEven . '">' . $usr['class'] . '</td>
    <td class="' . $styleCellOdd . '">' . $usr['looking'] . '</td>
    <td class="' . $styleCellEven . '">' . $usr['platform'] . '</td>
    <td class="' . $styleCellOdd . '">' . $usr['forum'] . '</td>';
    echo '<td class="' . $styleCellEven . '">';
    if (count($friends) > 0)
      {
      echo 'Friends: ';
      foreach($friends[0] !== '')
        {
        writeRelframe($x);
        }
      if (count($enemies) > 0)
        {
        echo '<br>';
        }
      }
    if (enemies[0] !== '')
      {
      echo 'Enemies: ';
      foreach($enemies as $x)
        {
        writeRelframe($x);
        }
      }
    if($friends[0] == '' && $enemies[0] == '')
      {
      echo '&nbsp';
      }
    echo '</td><td class="' . $styleCellOdd . '">';
    if ($scs[0] !== '')
      {
      foreach($tulpas as $x)
        {
        writeRelframe($x);
        }
      }
    else
      {
      echo '&nbsp';
      }
    echo '</td><td class="' . $styleCellEven . '">';
    if (count($altnames) > 0 && $altnames[0] !== '')
      {
       $i = 0;
       foreach($altnames as $x)
         {
         $i++;
         echo trim($x);
         if($i !== count($altnames)) echo ', ';
         }
      }
      else
      {
      echo '&nbsp';
      }
    $rowtrack = !$rowtrack;
    }
echo '</table>';
echo '<p style="font-size:8px;font-family:verdana, sans-serif; text-align:left; margin-left:5%;">Returned ' . $ucount . ' users.';

?>