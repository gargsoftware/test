<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HELLO WORLD</title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
<input type="submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    print_r($_POST);die;
    $name = $_REQUEST['name'];
    $email =$_REQUEST['email'];

    if (empty($name)) {
        echo "Name is empty";
    }
    elseif(empty($email)) {
        echo "email is empty";
    }
    else {
        echo "name ----> $name<br>";
        echo "email ---->$email";
    }}?>

</body>
</html>

