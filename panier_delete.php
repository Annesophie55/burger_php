<?php
session_start();

 require "pdo.php";
 $produitId=$_GET['idItem'];
 $sql = 'DELETE FROM panier WHERE id_item=:id_produit';
 $sqlStatement= $db->prepare($sql);
 $sqlStatement->bindValue(':id_produit',$_GET['idItem'],PDO::PARAM_INT);
 $sqlStatement->execute(array(':id_produit' => $produitId));


 header('Location:http://localhost/burger/panier.php');


?>