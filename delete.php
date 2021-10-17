<?php
//  connect mysql
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud','root','');
// it is use to check if we are now connected in the mysql if not it appear error
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

 $id = $_POST['id'] ?? null;

 if(!$id){
   header("Location:index.php");
   exit;
 }


$statement = $pdo->prepare('DELETE FROM product WHERE id = :id');
$statement->bindValue(":id",$id);
$statement->execute();
header("Location:index.php");

?>