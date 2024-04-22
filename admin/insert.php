<?php 
require '../pdo.php';



function checkInput($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data =stripslashes($data);
    return $data;

}

$sql = 'SELECT * FROM categories';
$stmt = $db->prepare($sql);
$stmt->execute();
$categs = $stmt->fetchAll();

if(!empty($_POST)){
    $name = checkInput($_POST['name']);
    $description = checkInput($_POST['description']);
    $price = checkInput($_POST['price']);
    $category = checkInput($_POST['category']);
    $image = checkInput($_POST['image']);
    $file;
    $succes = true;
    $uploaded = false;


if(empty($name)){
    $nameError= 'Veuillez mettre le nom du produit';
    $succes = false;
}
if(empty($description)){
    $descriptionError= 'Veuillez mettre la description du produit';
    $succes = false;
}
if(empty($price)){
    $priceError= 'Veuillez mettre le prix du produit';
    $succes = false;
}
if(empty($category)){
    $categoryError= 'Veuillez mettre la catégorie du produit';
    $succes = false;
}
if(empty($image)){
    $imageError= 'Veuillez mettre une image du produit';
    $uploaded = true;
}

if(isset($_FILES['image'])&& $_FILES['image']['error']==0){
    if($_FILES['image']['size']<=10000000){
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $extAutorise = ['jpg','jpeg','png'];

        if(in_array($extension, $extAutorise)){

            $newFileName = pathinfo($_FILES['image']['name'],PATHINFO_FILENAME).'_'.microtime().''.pathinfo($_FILES['image']['name'],PATHINFO_FILENAME);

            move_uploaded_file($_FILES['image']['tmp_name'], '.../images/'.$newFileName);

        }
        else{
            echo "le format de l'image est incorrect";
            $uploaded = false;
            exit;
        }
    }else{
        echo "le fichier est trop volumineux";
        $uploaded = false;
        exit;
    }
    }else{
        echo "le fichier n'a pas été téléchargé";
        $uploaded = false;
            exit;
    }

    if($uploaded && $succes){
        $sql='INSERT INTO items(name, description, price, category, image) VALUES(?,?,?,?,?)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($name, $description, $price, $category, $newFileName));

        header("location:index.php");
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
        <h1 class="text-logo">Burger Code</h1>
        <div class="container admin">
            <div class="row">
                <h1><strong>Ajouter un item</strong></h1>
                <br>
                <form class="form" action="insert.php" method="POST" enctype="multipart/form-data">
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
                            <?php foreach($categs as $res) { ?>
                              <div><?=$res['category']?></div>  
                                <?php } ?> 
                        </select>
                        
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="image">Sélectionner une image:</label>
                        <input type="file" id="image" name="image"> 
                        <span class="help-inline"></span>
                    </div>
                    <br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><span class="bi-pencil"></span> Ajouter</button>
                        <a class="btn btn-primary" href="index.html"><span class="bi-arrow-left"></span> Retour</a>
                   </div>
                </form>
            </div>
        </div>   
    </body>
</html>