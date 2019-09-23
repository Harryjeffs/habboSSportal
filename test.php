<?php
include 'models/config.php';

$rand_pass = getUniqueCode(15); //Get unique code
var_dump($rand_pass);
$secure_pass = generateHash(5); //Generate random hash
var_dump($secure_pass);