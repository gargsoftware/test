<!DOCTYPE html>
<html>
<body>
<!-- 
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  Name: <input type="text" name="fname">
  <input type="submit">
</form>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_REQUEST['fname']);
        if (empty($name)) {echo "Name is empty";}
        else {echo $name;}
    }
else{
    $name = htmlspecialchars($_REQUEST['fname']);
        if (empty($name)) {echo "Name is get";}
        else {echo $name;}
    }
?>
<form action="manish.php" method="post">
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
<input type="submit">
</form> -->



Welcome <?php echo $_POST["name"]; ?><br>
Your email address is: <?php echo $_POST["email"]; ?>


</body>
</html>



