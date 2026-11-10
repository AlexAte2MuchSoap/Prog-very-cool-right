<?php  /* db-tilkobling */
/*
/*  Programmet foretar tilkobling til database-server og valg av database
*/
$host = getenv('b-studentsql-1.usn.no');
$username = getenv('alamu3205');
$password = getenv('alamu3205');
$database = getenv('alamu3205');
$port = getenv('3306');

 $db=mysqli_connect($host,$username,$password,$database) or die ("ikke kontakt med database-server");
    /* tilkobling til database-serveren utført */
 ?>