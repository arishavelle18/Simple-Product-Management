<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud','root','');
// it is use to check if we are now connected in the mysql if not it appear error
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

 $id = $_GET['id'] ?? null;

 if(!$id){
   header("Location:index.php");
   exit;
 }
$statement = $pdo->prepare("SELECT * FROM product WHERE id=:id");
$statement->bindValue(":id",$id);
$statement->execute();
$prod = $statement->fetchAll(PDO::FETCH_ASSOC);
$product = $prod[0];

$title = $product['title'];
$description= $product['description'];
$price = $product['price'];

$error =[];
   if($_SERVER["REQUEST_METHOD"]==="POST"){
     

      // $_FILES["image"] get the image name,type,tmp_name,size,error and etc.
        
      $title = $_POST['title'];
      $description = $_POST['description'];
      $price = $_POST['price'];
   
      // Validation
      if(!$title){
         $error[] =  "Product title is required!"; // append to $error[]
      }
      if(!$price){
         $error[] = "Product price is required!"; // append to $error[]
      }

      if(!is_dir('images')){
         mkdir('images');
      }
      //  explain
     if(empty($error)){ // if walang error
      $imagePath=$product['image'];// this will unset the value
      $image = $_FILES["image"] ?? null;
      
      

      if($image && $image["tmp_name"]){
         
         if($product['image']){
            unlink($product['image']);
         }
            // move_uploaded_file i momove yung file sa specific location na gusto mo
            $imagePath ='images/'. generate(8).'/'.$image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'],$imagePath);
      }

      $statement = $pdo->prepare("UPDATE product SET title=:title,description=:description,image=:image,price=:price WHERE id = :id");
      $statement->bindValue(":title",$title);
      $statement->bindValue(":description",$description);
      $statement->bindValue(":image",$imagePath);
      $statement->bindValue(":price",$price);
      $statement->bindValue(":id",$id);
      $statement->execute();
      header('Location:index.php');
      unset($title);
      unset($description);
      unset($price);
     }
      
   }
   function generate($n){
      $alpanum = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $str = "";
      for($i = 0;$i<$n;$i++){
         $index=rand(0,strlen($alpanum)-1);
         $str.=$alpanum[$index];
      }
      return $str;
   }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>Product CRUD</title>
  </head>
  <body>
     <p>
        <a href="index.php" class="btn btn-secondary">Go Back to Products</a>
     </p>
    <h1>Update Product <?php echo $product["title"]?> </h1>
      <!-- print the error -->
      <!-- check also if it has no error -->

      <?php if(!empty($error)): ?>
         <div class="alert alert-danger">
            <?php foreach ($error as $key => $value) :?>

            <div> <?php echo $value . "<br>"; ?> </div>
               
            <?php endforeach;?>

         </div>
      <?php endif;?>
      <?php if($product["image"]):?>
      <img src="<?php echo $product["image"]?>" class="update-size">
      <?php endif;?>

      <form action="" method="post" enctype="multipart/form-data">

            <div class="form-group">
               <label>Product Image</label>
               <br>
               <input type="file" name="image" >
            </div>
            <div class="form-group">
               <label>Product Title</label>
               <!-- Solution 1(for retaining the data) : you can solve this using echo (isset($title))?$title:""; -->

               <input type="text" class="form-control" placeholder="Enter the product title" name="title" value="<?php  echo (isset($title))?$title:""; ?>" >
            </div>
            <div class="form-group">
               <label>Product Description</label>
               <textarea class="form-control" cols="30" rows="10" name="description" ><?php  echo (isset($description))?$description:""; ?></textarea>
            </div>
            <div class="form-group">
               <!-- step is for the decimal value -->
               <label>Price</label>
               <input type="number" class="form-control" step="0.01" placeholder="Enter the product price" name="price" value="<?php  echo (isset($price))?$price:"";; ?>"> 
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      <script src="app.js"></script>
  </body>
</html>
