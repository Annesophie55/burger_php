<?php
session_start();
require "pdo.php";

$newQte = intval($_GET['qte']);
$newId = $_GET['idItem'];

// Mettez à jour la quantité dans la table "panier"
$sql = 'UPDATE panier SET qte = qte + 1 WHERE id_item = :id_produit';
$qteStatement = $db->prepare($sql);
$qteStatement->bindValue(':id_produit', $newId, PDO::PARAM_INT);
$qteStatement->execute();

// Récupérer les informations du produit depuis la base de données
$produitQuery = $db->prepare('SELECT * FROM panier WHERE id_item = :id');
$produitQuery->bindValue(':id', $newId, PDO::PARAM_INT);
$produitQuery->execute();
$produit = $produitQuery->fetch();

if ($produit) {
    $newPrix = $produit['prix'] * $newQte;

    // Mettez à jour la quantité et le prix dans la table "panier" pour l'élément spécifié
    $updateProduit = $db->prepare('UPDATE panier SET qte = :qte, prix = :prix WHERE id_item = :id');
    $updateProduit->execute(array(':qte' => $newQte, ':prix' => $newPrix, ':id' => $newId));
}

// Redirection vers la page panier.php ou une autre action appropriée
header('Location: http://localhost/burger/panier.php');
?>
