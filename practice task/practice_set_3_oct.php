<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
  </nav>
<br><br>

<div class="container">
    
<form action="<?php echo ($_SERVER['PHP_SELF'])?>" method="POST">

  <div class="mb-3">
    <label for="exampleInputName" class="form-label">FIRST NAME</label>
    <input type="text" class="form-control" name="fname" id="exampleInputName">
  </div>

  <div class="mb-3">
    <label for="exampleInputLastName" class="form-label">Last NAME</label>
    <input type="text" class="form-control" name="lname" id="exampleInputLastName">
  </div>

  <div class="mb-3">
    <label for="exampleInputEmail" class="form-label">Email</label>
    <input type="text" class="form-control" name="email" id="exampleInputEmail">
  </div>  
  
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
  </div>

  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
  <button type="button" class="btn btn-info "><a href="http://localhost:8080/new_php/practice_set_3_oct.php">reload</a></button><br>
  <?php
if (isset($_POST["submit"]) && ($_SERVER["REQUEST_METHOD"] == "POST")) {
    // collect value of input field
    global $name;
    global $lname;
    global $email;
    global $pwd;
    
    $name = test_input($_REQUEST['fname']);
    $lname = test_input($_REQUEST['lname']);
    $email = test_input($_REQUEST['email']);
    $pwd = test_input($_REQUEST['password']);

    if($name == '' ){
      echo "name required";
    }
    else{echo $name."<br>";}


    if($lname == '' ){
      echo "lname required";
    }
    else{echo $lname."<br>";}
  
    echo $email."<br>";
    echo $pwd."<br>";}


  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
 
?>

</form>

</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>

