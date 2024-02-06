<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test</title>
</head>
<body>
    <?php 
    $n1="";
    $e1="";
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        if (empty($_POST['n1'])){
            $e1="required";
        }
        else{
            $n1=$_REQUEST['n1'];
        }
    }

    ?>
    <form action="<?php echo ($_SERVER['PHP_SELF']);?>" method="post">
    <input type="text" name="n1">
    <span>*<?php echo $e1;?></span>
    <input type="submit" value="submit" name="s1">

    <?php 
    echo $n1;
    ?>
</form>
</body>
</html>