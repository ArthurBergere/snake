
<?php
function OpenCon()
 {
 $dbhost = "localhost:3306";
 $dbuser = "";
 $dbpass = "";
 $db = "arthurbe_snake";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
 return $conn;
 }
 
function CloseCon($conn)
 {
 $conn -> close();
 }
   
?>