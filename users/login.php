<?php
   include("config.php");
   session_start();

   if($_SERVER["REQUEST_METHOD"] == "POST") {

      // username and password sent from form below.
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']);

      $myusername = strtolower($myusername);

      //pass sql line
      $sql = "SELECT userID FROM users WHERE username = '$myusername' and password = '$mypassword'";

      //retreive the matching username and password result
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];

      $count = mysqli_num_rows($result);

      // If result matched $myusername and $mypassword, table row must be 1 row
      if($count == 1) {
         //session_register("myusername");  // depricated??
         $_SESSION['login_user'] = $myusername;

         header("Location: welcome.php");
      }else {
         $error = "Your username or password is invalid.";
      }
   }
?>
<html>

<?php session_start(); ?>
<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>

<!DOCTYPE html>
<html>
<?php

?>
<head>

    <title>Login</title>

	<script src="/js/jquery-3.1.1.min.js"></script>

	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/scripts.js"></script>

	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/style.css" rel="stylesheet">
</head>

<body>
<div class="container-fluid">
<div class="row">
<br /><br />
<div class="col-xs-4"></div>
<div class="col-xs-8">
    <div class="col-xs-2">
        <form method="post" action="login.php" role="form" class='form'>
        <div class="form-group">
            <label for="username">username:</label>
            <input class="form-control" id="username" name='username' type='text'>
        </div>
        <div class="form-group">
            <label for="subtitle">password:</label>
            <input class="form-control" id="password" name='password' type='password'>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name='submit' id='submit' value='Submit'>
        </div>
        </form>
    </div>
</div>
</body>
</html>