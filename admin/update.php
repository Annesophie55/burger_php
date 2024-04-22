
<?php
session_start();
require "pdo.php";
var_dump($_GET['idItem']);



if (isset($_GET['idItem'])) {
    $idItem = $_GET['idItem'];

    $id_cat = 'SELECT items.*, categories.name AS category_name FROM items INNER JOIN categories ON items.category = categories.id WHERE items.id = :id';
    $item_sql = $db->prepare($id_cat);
    $item_sql->bindValue(':id', $idItem, PDO::PARAM_INT);
    $item_sql->execute();
    $itemss = $item_sql->fetch();

}


$sql = "SELECT * FROM categories";
$sqlStatement = $db->prepare($sql);
$sqlStatement->execute();
$categories = $sqlStatement->fetchAll();

if($_POST){
if (!empty($_POST['name']) || !empty($_POST['description']) || !empty($_POST['price']) || !empty($_POST['category'] || !empty($_FILES['image'])))  {

    $nom =  strip_tags($_POST['name']);
    $description =  strip_tags($_POST['description']);
    $prix =  strip_tags($_POST['price']);
    $categorie =  strip_tags($_POST['category']);
    
   
    $sql = 'UPDATE items SET name = :name, description = :description, price = :price, category = :category WHERE id = :id';
    $sqlstatement = $db->prepare($sql);
    $sqlstatement->bindValue(':name', $nom, PDO::PARAM_STR);
    $sqlstatement->bindValue(':description', $description, PDO::PARAM_STR);
    $sqlstatement->bindValue(':price', $prix, PDO::PARAM_STR);
    $sqlstatement->bindValue(':category', $categorie, PDO::PARAM_STR);
    $sqlstatement->bindValue(':id', $idItem, PDO::PARAM_INT); 
    $sqlstatement->execute();
    
}


// téléchargement de la nouvelle image
if(isset($_FILES['image']) && $_FILES['image']['error']==0) {

    if($_FILES['image']['size']<=1000000){
    $fileInfo = pathinfo($_FILES['image']['name']);
    $extension = $fileInfo['extension'];
    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];


    if (in_array($extension, $allowedExtensions)) {
     
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . basename($_FILES['image']['name']));
        $image = 'images/'.basename($_FILES['image']['name']);
        echo "L'envoi a bien été effectué !";
        }else{
            echo "le format de l'image est incorrect";
            header("location:index.php");
            exit;
           }
           }else{
            echo "le fichier est trop volumineux";
            exit;
           }
           }
           else {
             echo 'Le fichier n\'a pas été envoyé correctement';
             exit;
           }
}




?>
<!DOCTYPE html>
<html>
    <head>
    <title>Burger Code</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../styles.css">
    </head>
    
    <body>
        <h1 class="text-logo"> Burger Code </h1>
        <div class="container admin">
       
            <div class="row">
                <div class="col-md-6">
                    <h1><strong>Modifier un item</strong></h1>
                    <br>
                    <form class="form" action="index.php" role="form" method="POST" enctype="multipart/form-data">
                        <br>
                        <div>
                            <label class="form-label" for="name">Nom:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="">
                            <span class="help-inline"></span>
                        </div>
                        <br>
                        <div>
                            <label class="form-label" for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="">
                            <span class="help-inline"></span>
                        </div>
                        <br>
                        <div>
                        <label class="form-label" for="price">Prix: (en €)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Prix" value="">
                            <span class="help-inline"></span>
                        </div>
                        <br>
                        <div>
                            <label class="form-label" for="category">Catégorie:</label>
                            <select class="form-control" id="category" name="category">
                            <?php foreach ($categories as $categorie) { ?>
                            <option value="<?= $categorie['id'] ?>"><?= $categorie['name'] ?></option>
                            <?php } ?>
                            </select>
                            <span class="help-inline"></span>
</div>

                        <br>
                        <div>
                            <label class="form-label" for="image">Image:</label>
                            <!-- <p><img src="images/" alt="">
</p> -->
                            <label for="image">Sélectionner une nouvelle image:</label>
                            <input type="file" id="image" name="image"> 
                            <span class="help-inline"></span>
                        </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><span class="bi-pencil"></span> Modifier</button>
                            <a class="btn btn-primary" href="index.php"><span class="bi-arrow-left"></span> Retour</a>
                       </div>
                    </form>
                </div>
                
                <div class="col-md-6 site">
                
                    <div class="img-thumbnail">
                        <img src="images/<?= $itemss['image']?>" alt="">
                        <div class="price"><?= $itemss['price'] ?>€</div>
                          <div class="caption">
                            <h4><?= $itemss['name'] ?></h4>
                            <p><?= $itemss['description'] ?></p>
                            <a href="#" class="btn btn-order" role="button"><span class="bi-cart-fill"></span> Commander</a>
                          </div>
                    </div>
                    
                </div>
                
            </div>
           
        </div>   
    </body>
</html>
