<?php

if ($_GET['open'] == 'false') 
  {
  echo '<div class="sctrlink"><a class="jslink" onclick="openSearch()">Search...</a></div>';
  }
else
  {
  echo' <form onsubmit="updateTable(); return false;"><div class="strcssec">
  Name: <input id="srchname" type="text" size="30" onkeyup="manageSearch()">
  <br>
  <div class="sctrlink"><a class="jslink" onclick="openSearch()">Close Search</a></div>';
  }

?>