<?php session_start(); ?>
<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>

<!DOCTYPE html>
<html>
<?php

?>
<head>

    <title>T&amp;C User Settings</title>

	<script src="/js/jquery-3.1.1.min.js"></script>

	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/scripts.js"></script>

	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/style.css" rel="stylesheet">
</head>
<body>
    <h1 style='text-align: center;'>Settings</h1>
    <h3><a href='welcome.php'>Back</a></h3>
    <div class="container-fluid">
    <div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <div class="col-xs-2">
            <form method="post" action="settings.php" role="form" class='form'>
            <div class="form-group">
                <label for="title">current password:</label>
                <input class="form-control" id="curpass" name='curpass' type='password'>
            </div>
            <div class="form-group">
                <label for="subtitle">new password:</label>
                <input class="form-control" id="newpass" name='newpass' type='password'>
            </div>
            <div class="form-group">
                <label for="subtitle">confirm new password:</label>
                <input class="form-control" id="conpass" name='conpass' type='password'>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name='submit' id='submit' value='Submit'>
            </div>
        </form>
        </div>
        <div class="col-xs-2">
        <?php
            //if page was loaded via the 'submit' button ...
            if($_POST['submit']){
                
                $user = $_SESSION['login_user'];
                $curpass = $_POST['curpass'];
                $newpass = $_POST['newpass'];
                $conpass = $_POST['conpass'];

                //echo $user . " " . $curpass . " " . $newpass . " " . $conpass;

                include_once("config.php");
                if($newpass == $conpass){
                    $sql = "UPDATE users SET password='$newpass' WHERE username='$user'";
                    $success = mysqli_query($db,$sql);
                    //echo "success is " . $success;
                    if($success){
                        echo '<div class="alert alert-success">
                            <strong>Success!</strong> Password updated.
                        </div>';
                        }else{
                        echo '<div class="alert alert-danger">
                            <strong>Oops!</strong> Current password incorrect.
                        </div>';
                    }
                }else{
                    echo "<div class='alert alert-danger'>
                        <strong>Oops!</strong> New passwords don't match.
                    </div>";
                }
            }
        ?>
        </div>
    </div>

    <div class="col-xs-2"></div>
    
</body>