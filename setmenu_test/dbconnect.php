<?
  //$db_Server = "db1409.perfora.net";
  //$username = "dbo235976078";
  //$password = "9aGPYuGw";
  //$db_Name = "db235976078";
    $db_Server = "db475997706.db.1and1.com";
  $db_Name = "db475997706";  
  $username = "dbo475997706";  
  $password = "9aGPYuGw";
  $link = mysql_connect($db_Server,$username,$password);
  mysql_query("SET NAMES UTF8");
  if (!$link)
  {
    die('Could not connect to Database: ' . mysql_error());
  }
  $db_selected = mysql_select_db($db_Name, $link);
  if (!$db_selected)
  {
    die ('Database problem : ' . mysql_error());
  }
?>