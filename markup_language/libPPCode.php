<?php

//Generates html from PPCode.  Returns valid PPCode or kills the script if none could be generated
function parsePPCode($input)
  {
  	
  if($input === null)
    return null;
  	
  if($input === '')
    return '';
    
  $input = '<div>' . $input . '</div>'; // 4.1.4 PPCode revisions require an enclosing block
  
  $ppcode = array('[b]' => '<strong>',
                '[/b]' => '</strong>',
                '[bold]' => '<strong>',
                '[/bold]' => '</strong>',
                '[strong]' => '<strong>',
                '[/strong]' => '</strong>',
                
                '[i]' => '<em>',
                '[/i]' => '</em>',
                '[italic]' => '<em>',
                '[/italic]' => '</em>',
                '[em]' => '<em>',
                '[/em]' => '</em>',
                
                '[u]' => '<u>',
                '[/u]' => '</u>',
                '[ul]' => '<u>',
                '[/ul]' => '</u>',
                '[underline]' => '<u>',
                '[/underline]' => '</u>',
                
                '[br]' => '<br>',
                "\n" => '<br>',
                
                // As of 4.1.4 [p] no is no longer implemented as an actual <p> but rather a simulated para with <div> to allow for [profile]
                '[p]' => '</div><br><div>',
                '[para]' => '</div><br><div>',
                '[paragraph]' => '</div><br><div>',

                '[sp]' => '<span class="spoiler">',
                '[/sp]' => '</span>',
                '[spoiler]' => '<span class="spoiler">',
                '[/spoiler]' => '</span>',
                
                '[/a]' => '</a>',
                '[/url]' => '</a>',
                '[/link]' => '</a>',
                
                '[/s]' => '</span>',
                '[/size]' => '</span>',
                
                '[/c]' => '</span>',
                '[/color]' => '</span>');
                
  foreach($ppcode as $k => $v)
    {
      $input = str_replace($k, $v, $input);
    }                      
                               
  $ppcodeLink = array('[a ', '[url ', '[link ');
  foreach($ppcodeLink as $v)
    {
    $pos = strpos($input, $v);  
    while($pos !== false)
      {
      $input = substr_replace($input, '<a href="', $pos, strlen($v));
      $epos = strpos($input, ']', $pos);
      $input = substr_replace($input, '">', $epos, 1);
      $pos = strpos($input, $v);
      }       
    }
    
  $ppcodeImg = array('[img ', '[image ');
  foreach($ppcodeImg as $v)
    {  
    $pos = strpos($input, $v);  
    while($pos !== false)
      {
      $input = substr_replace($input, '<a href="', $pos, strlen($v));
      $epos = strpos($input, ']', $pos);
      $input = substr_replace($input, '" style="max-width:100%; max-height:100%;">', $epos, 1);
      $pos = strpos($input, $v);
      }
    }
    
  $ppcodeColor = array('[c ', '[color ');
  foreach($ppcodeColor as $v)
    {  
    $pos = strpos($input, $v);  
    while($pos !== false)
      {
      $input = substr_replace($input, '<a href="', $pos, strlen($v));
      $epos = strpos($input, ']', $pos);
      $input = substr_replace($input, ';">', $epos, 1);
      $pos = strpos($input, $v);
      }
    }
    
  $ppcodeSize = array('[s ', '[size ');
  foreach($ppcodeSize as $v)
    {  
    $pos = strpos($input, $v); 
    while($pos !== false)
      {
      $input = substr_replace($input, '<a href="', $pos, strlen($v));
      $epos = strpos($input, ']', $pos);
      $input = substr_replace($input, 'px;">', $epos, 1);
      $pos = strpos($input, $v);
      }
	 }
  
  $ppcodeProfile = array('[profile ');
  foreach($ppcodeProfile as $v)
    {  
    $pos = strpos($input, $v); 
    while($pos !== false)
      {
      $epos = strpos($input, ']', $pos);
      $tgt = substr($input, $pos + strlen($v), $epos - ($pos + strlen($v)));
      $input = substr_replace($input, getUserframe($tgt), $pos, $epos - $pos + 1);
      $pos = strpos($input, $v);
      }
    }
    
    return $input;                       
  }

//Modified userframe generator for use with [profile]
function getUserframe($tgtname)
{
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . 'lib/');
include 'libSQL.php';

$data = mysqli_query($con,"SELECT * FROM Userlist WHERE uname='" . mysqli_real_escape_string($con, $tgtname) . "'");
$olddata = mysqli_query($con,"SELECT * FROM Legacy_Userlist WHERE uname='" . mysqli_real_escape_string($con, $tgtname) . "'");

$frame = '';

if(mysqli_num_rows($data) > 0)
    {
    $usr = mysqli_fetch_array($data);
    $frame .= '<a class="tooltip" href="/userlist/user.php?uname=' . $tgtname . '"><div class="';
    }
else if(mysqli_num_rows($olddata) > 0)
    {
    $usr = mysqli_fetch_array($olddata);
    $frame .= '<a class="tooltip" href="/userlist/legacy/user.php?uname=' . $tgtname . '"><div class="';
    }



if($usr['uname'] !== null && $usr['uname'] !== '')
  {

$uname=$usr['uname'];
$bio=stripPPCode($usr['bio']);
$imglink=$usr['imglink'];

if($bio !== '')
  $frame .= 'uframe';
else
  $frame .= 'uframesmall';

$frame .= '">
<div class="fctrname">' . $uname . '</div>
<div class="';

if($bio !== '')
    $frame .= 'fctrimg';
else 
    $frame .='fctrimgsmall';

$frame .= '"><img src="' . $imglink . '" class="fppic" alt="profile image"></div>';
if ($bio !== '')
  {
	$frame .= '<div class="fctrbio">' . $bio . '</div>';
  }
  $frame .= '</div>' . $tgtname . '</a> ';
  return $frame;
}
else
  {
  return '<span class="brokenuserlink">' . $tgtname . '</span>';
  }
}

function writePPCodeInfo()
  {
  
  set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . 'lib/');	
  include 'libUserframe.php';
  	
  echo '<a class="tooltip" target="_blank" href="/frames/ppcode.php"><div class="ppcodeframe">
  <span class="subhead">PPCode</span>
  <p style="font-size:0.5em;">PPCode is a method of formatting your profile and posts.  See below for usage.</p>
  <table class="ppcodetable"><tr><th>PPCode</th><th>Result</th></tr>
  <tr>
  <td>[b]text[/b]<br>[bold]text[/bold]<br>[strong]text[/strong]</td>
  <td><strong>text</strong></td>
  </tr>                                                                                                                                                                               
  <tr>
  <td>[i]text[/i]<br>[italic]text[/italic]<br>[em]text[/em]</td>
  <td><em>text</em></td>
  </tr>
  <tr>
  <td>[u]text[/u]<br>[ul]text[/ul]<br>[underline]text[/underline]</td>
  <td><u>text</u></td>
  </tr>
  <tr>
  <td>[s 15]text[/s]<br>[size 15]text[/size]</td>
  <td><span style="font-size:15px;">text</span></td>
  </tr>
  <tr>
  <td>[c red]text[/c]<br>[color red]text[/color]</td>
  <td><span style="color:red;">text</span></td>
  </tr>
  <tr>
  <td>[sp]text[/sp]<br>[spoiler]text[/spoiler]</td>
  <td><span class="spoiler">text</span></td>
  </tr>
  <tr>
  <td>line 1[br]line 2</td>
  <td>line 1<br>line 2</td>
  </tr>
  <tr>
  <td>line 1[p]line 2<br>line 1[para]line 2<br>line 1[paragraph]line 2</td>
  <td><p>line 1</p><p>line 2</p></td>
  </tr>
  <tr>
  <td>[profile Perun]</td>
  <td><u>Perun</u></td>
  </tr>
  <tr>
  <td>[a http://www.example.com]Example[/a]<br>[url http://www.example.com]Example[/url]<br>[link http://www.example.com]Example[/link]</td>
  <td><a href="http://www.example.com">Example</a></td>
  </tr>
  <tr>
  <td>[img http://upload.wikimedia.org/wikipedia/commons/a/a9/Example.jpg]</td>
  <td><img src="http://upload.wikimedia.org/wikipedia/commons/a/a9/Example.jpg"></td>
  </tr></table>
  </div>PPCode</a>';                                                      
  }
?>