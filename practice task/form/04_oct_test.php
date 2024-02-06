<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .error{
            color:#FF0000;
        }
    </style>
</head>

<body>

<?php
$nameErr=$addressErr=$noErr=$pwdErr="";
$name=$address=$mobile_no=$pwd="";

if ($_SERVER['REQUEST_METHOD']=='POST'){
    if (empty($_POST['name'])){
        $nameErr = "name required";
    }
    else{
        $name=$_REQUEST['name'];
        if(!preg_match("/^[a-zA-Z-' ]*$/",$name)){
            $nameErr ="Only letters and white space allowed";
         }
    }

    if (empty($_POST['address'])){
        $addressErr = "address required";
    }
    else{
        $address=$_REQUEST['address'];
    }

    if (empty($_POST['mobile_no'])){
        $noErr = "mobile_no required";
    }
    else{
        $mobile_no=$_REQUEST['mobile_no'];
    }

    if (empty($_POST['pwd'])){
        $pwdErr = "password required";
    }
    else{
        $pwd=$_REQUEST['pwd'];
    }
}

    
        ?>
    <h3>form</h3>
    <form action='<?php echo ($_SERVER["PHP_SELF"])?>' method="post">

        <label for="name">enter your name :- </label>
        <input type="text" name="name" id="name">
        <span class="error">*<?php echo $nameErr;?></span><br><br>

        <label for="address">enter your address :- </label>
        <input type="text" name="address" id="address">
        <span class="error">*<?php echo $addressErr;?></span><br><br>

        <label for="mobile_no">enter your mobile_no :- </label>
        <input type="text" name="mobile_no" id="mobile_no">
        <span class="error">*<?php echo $noErr;?></span><br><br>

        <label for="pwd">enter your pwd :- </label>
        <input type="pa" name="pwd" id="pwd">
        <span class="error">*<?php echo $pwdErr;?></span><br><br>

        <input type="submit" value="submit">

        <?php 
        if($nameErr == '' and $addressErr == '' and $noErr == '' and $pwdErr == '' ){
            echo "<br>";
            echo $name;
            echo "<br>";
            echo $address;
            echo "<br>";
            echo $mobile_no;
            echo "<br>";
            echo $pwd;
        }
        else{
           echo "";
        }
   
        ?>
    </form>
</body>

</html>