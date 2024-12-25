<?php

$keyId = 'rzp_test_CZnxmrbV9AAJAA';
$keySecret = '0is9hn7Xljr2lCYp7LwrN7Zk';
$displayCurrency = 'INR';

//These should be commented out in production
// This is for error reporting
// Add it to config.php to report any errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = 'localhost';
$username = 'saran';
$password = 'fubu';
$dbname = 'stripe';

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}