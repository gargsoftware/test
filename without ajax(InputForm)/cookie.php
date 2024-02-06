<!-- document.addEventListener("DOMContentLoaded", () => {
  const currentUrl = window.location.href;
  const navbarLinks = document.querySelectorAll('#navbar .navbar_lst li a');

  navbarLinks.forEach(link => {
    if (link.href === currentUrl) {
      link.classList.add('selected');
    }
  });
}); -->
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user credentials (for example, from a database)
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the credentials (you should perform proper validation and authentication here)

    // Check if the "Remember Me" checkbox is checked
    $rememberMe = isset($_POST["remember_me"]) && $_POST["remember_me"] == 1;

    // If the credentials ar
        if ($rememberMe) {
            setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days
        }

        // Redirect to the authenticated page
        exit();
  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <?php
    // Display error message if any
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <br>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <br>

        <label for="remember_me">Remember Me</label>
        <input type="checkbox" name="remember_me" value="1">

        <br>

        <input type="submit" value="Login">
    </form>

</body>
</html>
