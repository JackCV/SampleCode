<?php

session_start();

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . 'lib/');
                                                   
include 'libNavbar.php';

echo '<!DOCTYPE html>
<html>
<head>
<title>Userlist</title>
<link rel="stylesheet" type="text/css" href="/global.css">
<link rel="stylesheet" type="text/css" href="userlist.css">
<script src="/js/liveSearch.js">
</head>

<body onload="generateTable(); generateLegacyTable();">
<div class="tctrpage">
<div class="tctrtop">'; writeTopbar(); echo '</div>
<div class="tctrnav">'; writeNavbar(); echo '</div>
<div class="tctrcontent">
<div class="ctrsearch" id="search"><div class="sctrlink"><a class="jslink" onclick="openSearch()">Search...</a></div></div><br>
<div class="ctrtable" id="userlist">Loading user database...</div>
<div class="ctrtable" id="legacyuserlist">Loading legacy profiles...</div>
</div>
</body>
</html>';

?>