<?php
// Database configuration

$hostname="localhost";
$user_name = 'root';  
$user_password = '';  
$db_name= 'staff_db';
// Create database connection  
$conn =new mysqli($hostname,$user_name,$user_password,$db_name);
 
// Check connection  
if (!$conn) {  
    echo"not connected"; 
}