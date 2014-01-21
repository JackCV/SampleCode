<script>

var activeSort = "sname";
var ascSort = true;
var srchOpen = false;
var sortQuery = "";
var searchQuery = "";

function openSearch()
{
srchOpen = !srchOpen;
var ajax = new XMLHttpRequest();
ajax.onreadystatechange=function()
  {
  if (ajax.readyState === 4 && ajax.status === 200)
    {
    document.getElementById("search").innerHTML=ajax.responseText;
    }
  }
ajax.open("GET","search.php?open="+srchOpen,true);
ajax.send(); 
}

function manageSort(str, ref)
{

if (ref.id === activeSort)
  {
  ascSort = !ascSort;
  }
else
  {
  ascSort = true;                                                   
  activeSort = ref.id;
  }

if (ascSort)
  {  
  sortQuery="sort=" + str + "&order=asc";
  }
else
  {
  sortQuery="sort=" + str + "&order=desc";
  }
  
  updateTable();
}

function manageSearch()
{
  searchQuery = "";
  if(document.getElementById("srchname").value !== "")
    {
    searchQuery += "&srchname=" + document.getElementById("srchname").value;
    }
  updateTable();
}

function generateTable()
{                                                                
var ajax = new XMLHttpRequest();
ajax.onreadystatechange=function()
  {
  if (ajax.readyState === 4 && ajax.status === 200)
    {
    document.getElementById("userlist").innerHTML=ajax.responseText;
    }
  }
ajax.open("GET","list.php",true);
ajax.send();
}

function generateLegacyTable()
{                                                                
var ajax = new XMLHttpRequest();
ajax.onreadystatechange=function()
  {
  if (ajax.readyState === 4 && ajax.status === 200)
    {
    document.getElementById("legacyuserlist").innerHTML=ajax.responseText;
    }
  }
ajax.open("GET","legacy/list.php",true);
ajax.send();
}

function updateTable()
{

var str = sortQuery + searchQuery;

if (str.length === 0)
  {
  generateTable();
  return;
  }
var ajax = new XMLHttpRequest();
ajax.onreadystatechange=function()
  {
  if (ajax.readyState === 4 && ajax.status === 200)
    {
    document.getElementById("userlist").innerHTML=ajax.responseText;
    }
  }                 
  ajax.open("GET","list.php?"+str,true);
  ajax.send();
}
</script>