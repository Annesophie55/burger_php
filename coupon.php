<?php
session_start();
require "pdo.php";

$dateJour = date('Y-m-d H:i:s');


if(!empty($_POST['code'])){
    $code = strip_tags($_POST['code']);

    $sqlCodeValide = 'SELECT * FROM coupons WHERE code = :code AND debut <= :date AND fin >= :date';
    $codeValideStatement = $db->prepare($sqlCodeValide);
    $codeValideStatement->bindValue(':code', $code, PDO::PARAM_STR);
    $codeValideStatement->bindValue(':date', $dateJour, PDO::PARAM_STR);
    $codeValideStatement->execute();
    $codeValide = $codeValideStatement->fetch();

    if($codeValide){
        $reduc = $codeValide['remise'];
        // Mettez à jour le panier avec la remise appliquée si nécessaire
        // ...
        $_SESSION['coupon'] = $codeValide;
        $_SESSION['message'] = "Vous avez ajouté un code de réduction !";
    } else {
        $_SESSION['coupon'] = null;
        $_SESSION['message'] = "Le code de remise saisi est incorrect !";
    }
}


header('Location: panier.php');
?>
