<?php
session_start();

require "pdo.php";

$sqlProduits = 'SELECT * FROM panier INNER JOIN items ON id_item=items.id';
$produitsStatement = $db->prepare($sqlProduits);
$produitsStatement->execute();
$produits = $produitsStatement->fetchAll();

$totalPanier = 0; 

foreach ($produits as $produit) {
    $totalPanier += $produit['price'] * $produit['qte'];
}



$tva = (($totalPanier * 20) / 100);
$reduc = isset($_SESSION['coupon']) ? $_SESSION['coupon']['remise'] : 0; // 

$totalTTC = $totalPanier + $tva - (($totalPanier * $reduc) / 100);
$totalTTC = round($totalTTC, 2);


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="styles.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    </head>

    <body>
        <div class="cart ">
            <div class="alert alert-danger <?php if($produits){echo'd-none';} ?>" role="alert" style="text-align:center;">
                Votre panier est vide !
            </div>
        
            <div class="cart-container">

                <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="">
                            
                            <div class="">
                            

                                
                                <table class="table table-bordered mb-30">
                                
                                    <thead>
                                                                   
                                                              <tr>                                        <th scope="col"></th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Prix</th>
                                            <th scope="col">Quantité</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                  </thead>
                                    
                                    <tbody>
                                   

                                       
                                    <?php foreach ($produits as $produit) { ?>                                       <tr>
 
                                                              
                                            <th scope="row">
                                                                                    
                                                <a href="panier_delete.php?idItem=<?=$produit['id']?>"
                                                    onclick="return confirm('Etes-vous sûr de vouloir supprimer ce produit de votre panier ?')">
                                                    <i class="bi bi-archive"></i>
                                                </a>
                                            </th>
                                            <td>
                                                <img src="images/<?=$produit['image']?>" alt="Product" style="width:100px">
                                            </td>
                                            <td>
                                                <a href=""><?= $produit['name'] ?></a><br>
                                                <span ><small></small></span>
                                            </td>
                                            <td> <?= $produit['price'] ?>€</td>
                                            <td>
                                                <div class="quantity"
                                                    style="display:flex; justify-content:center; align-items:center">

                                                    <a href="ajoutqte.php?idItem=<?=$produit['id_item']?>&qte=<?=$produit['qte']-1?>" name="supprimer"
                                                        style="border:none; background-color:white; text-decoration:none; color:black">
                                                        <span
                                                            style="font-size:40px; margin-right:10px; margin-left:10px">-</span>
                                                    </a>
                                                    <span id="qtpanier"><?=$produit['qte']?></span>
                                                    <a href="ajoutqte.php?idItem=<?=$produit['id_item']?>&qte=<?=$produit['qte']+1?>" name="ajouter"
                                                        style="border:none; background-color:white; text-decoration:none;  color:black">
                                                        <span
                                                            style="font-size:40px; margin-left:10px; margin-right:10px">+</span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class=""><?=$produit['price'] * $produit['qte']?></td>
                                            <?php } ?>            
        </tr>
                                           
                                    </tbody>
                                     
                                        
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Coupon -->
                    <div class="col-12 col-lg-6">
                        <div class=" mb-30" <?php if(!isset($_POST['code'])){echo'd-none';} ?>>
                            <h6>Avez vous un coupon?</h6>
                            <p>Entrer le code de la remise</p>

                            <div class="alert alert-danger" role="alert" >
                                Attention : le code remise saisi est incorrect !
                            </div>

                            <div class="alert alert-primary" role="alert">
                                Vous avez ajouté un code de réduction !
                            </div>
                        <!-- Coupon -->
                            <div class="coupon-form">
                                <form action="coupon.php" method="POST">
                                    <input type="text" class="form-control" name="code" placeholder="Entrer le code">
                                    <button type="submit" class="btn btn-primary"
                                        style="margin-top:20px">Valider</button>
                                </form>
                            </div>
                            <br>

                            <!-- Coupon -->


                        </div>
                    </div>



                    <div class="col-12 col-lg-5">
        <div class=" mb-30">
            <h5 class="mb-3">Total panier</h5>
            <div class="">
                <table class="table mb-3">
                    <tbody>
                    <tr>
                        <td>Total produit HT</td>
                        <td id="HT"><?= $totalPanier ?> €</td>
                    </tr>
                    <tr>
                        <td>TVA</td>
                        <td id="TVA"><?= $tva ?> €</td>
                    </tr>
                    <tr>
                        <td>Remise</td>
                        <td><?= ($totalPanier * $reduc) / 100 ?> €</td>
                    </tr>
                    <tr>
                        <td>Total TTC</td>
                        <td id="TTC"><?= $totalTTC ?> €</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

                </div>
                <a class="btn btn-primary" href="index.php"><span class="bi-arrow-left"></span> Retour</a>
            </div>
        </div>
    </body>

</html>