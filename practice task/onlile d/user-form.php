<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<!--====form section start====-->
<div class="user-detail">
    <h2>Insert User Data</h2>
    <p id="msg"></p>
    <form id="userForm" method="POST">
          <label>Full Name</label>
          <input type="text" placeholder="Enter Full Name" name="fullName" >
          <label>Email Address</label>
          <input type="email" placeholder="Enter Email Address" name="emailAddress" >
          <label>City</label>
          <input type="city" placeholder="Enter Full City" name="city" >
          <label>Country</label>
          <input type="text" placeholder="Enter Full Country" name="country" >
          <button type="submit">Submit</button>
    </form>
        </div>
</div>
<!--====form section start====-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="ajax-script.js"></script>
</body>
</html>