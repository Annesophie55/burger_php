<?php

try{
 $db = new PDO('mysql:host=localhost;dbname=burger;charset=utf8',
 'root', '');
 }
 catch (PDOException $e){
 die('Erreur : ' . $e->getMessage());
 }
 ?>