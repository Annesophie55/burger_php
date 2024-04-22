<?php
session_start();
require "pdo.php";


$userTemp = 1;
$_SESSION['userTemp']++;

$qte = 1;

// récupération du produit
$sqlPaniers = 'SELECT * FROM items
WHERE items.id = :id';
$paniersStatement = $db->prepare($sqlPaniers);
$paniersStatement->bindValue(':id',$_GET['idItem'],PDO::PARAM_INT);
$paniersStatement->execute();
$produitPanier = $paniersStatement->fetch();



 $sqlGroupItem = 'SELECT * FROM panier WHERE id_item = :id';
 $groupItemStatement = $db->prepare($sqlGroupItem);
 $groupItemStatement->bindValue(':id',$_GET['idItem'],PDO::PARAM_INT);
 $groupItemStatement->execute();
 $groupItems = $groupItemStatement->fetchAll();



if(empty($groupItems)){
 $sql = $db-> prepare("INSERT INTO panier (id_item, userTemp, qte, prix) VALUE (?, ?, ?, ?)");
$sql->execute(array($_GET['idItem'],$_SESSION['userTemp'],$qte,$produitPanier['price']));

}
else{
 $sql = $db->prepare("UPDATE panier SET qte = qte + 1 WHERE id_item = :id");
 $sql->bindValue(':id',$_GET['idItem'],PDO::PARAM_INT);
 $sql->execute();
}




header('Location:http://localhost/burger/panier.php');


?>