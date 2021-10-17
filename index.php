<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud','root','');
// it is use to check if we are now connected in the mysql if not it appear error
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$search = $_GET["search"] ?? "";
if($search){ 
   //this will run if you want to search something
   $statement = $pdo->prepare(
   'SELECT * FROM product WHERE title LIKE :title
   ORDER BY create_date DESC;
   ');
   $statement->bindValue(":title","%$search%");
}
else{ // if you are not searching anything the default is selecting all the items
   $statement = $pdo->prepare(
   'SELECT * FROM product
   ORDER BY create_date DESC;
   ');
}
$statement->execute(); // execute() = execute a prepared statements
$product = $statement->fetchAll(PDO::FETCH_ASSOC); // returns an array containing all of the result set rows
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
    <h1>Product CRUD</h1>

   <a href="create.php" class="btn btn-success">Create Product</a>
   <br><br>
   <form action="">
      <div class="input-group mb-3">
         <input type="text" class="form-control" placeholder="Search for products" name="search">
         <button class="btn btn-outline-secondary" type="submit">Search</button>
      </div>
   </form>

    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Image</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Price</th>
      <th scope="col">Create Date</th>
      <th scope="col">Action</th>

   </tr>
  </thead>
  <tbody>
     <?php  foreach ($product as $key => $prod) : ?>  <!--  you can use foreach { table }  same lang sila ni foreach(): endforeach;-->
            <tr>
            <th scope="row">
               <?php echo $key+1   ?>
            </th>
            <td>
               <?php if($prod['image']!== "") { ?>
                   <img src="<?php echo $prod['image']?>" class="size">
               <?php }?>
            </td>
            <td>
               <?php echo $prod['title']?>
            </td>
            <td>
               <?php echo $prod['description']?>
            </td>
            <td>
               <?php echo $prod['price']?>
            </td>
            <td>
               <?php echo $prod['create_date']?>
            </td>
            <td>
               
                  
                  <a href="update.php?id=<?php echo $prod['id']?>" class='btn btn-outline-primary btn-sm'>Edit</a>
              
               <!-- <a href="delete.php?id (php syntax) echo $prod['id'] (php syntax)
                     - meaning neto pupunta kay delete.php na may dalang id value" -->
                     <form action="delete.php" style="display: inline-block;" method="POST">
                        <input type="hidden" name="id" value="<?php echo $prod['id'];?>">
                        <button type="submit"class="btn btn-outline-danger btn-sm">Delete</button>
                     </form>
              
            </td>
          </tr>

      <?php endforeach; ?>

  </tbody>
</table>

  </body>
</html>