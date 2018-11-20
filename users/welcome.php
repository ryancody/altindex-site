<!DOCTYPE html>

<?php
    session_start();
    include('session.php');

    if(isset($_SESSION['login_user'])){

        $username = ucfirst($_SESSION['login_user']);

        //if page was loaded via the 'submit' button ...
        if($_POST['submit']){

            $title = $_POST['title'];
            $subtitle = $_POST['subtitle'];

            $content = $_POST['content'];
            $content = base64_encode($content);

            include_once("config.php");
            $sql = "INSERT INTO articles1(author, title, subtitle, content, published, showLogo) VALUE ('$username', '$title', '$subtitle', '$content', 0, 1)";
            $success = mysqli_query($db,$sql);
        }else{
            //else.... do nothing at this point.
            //header('Location: login.php');
            //die();
        }
    }
?>

<html>

<head>
    <title>Welcome</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container-fluid">
	<div class="row">
		<div class="col-sm-4">
            <div class="container-fluid">
                <div class="navbar-header pull-left">
                <a class="navbar-brand pull-left" href="#">AltIndex Users</a>
                </div>
                <ul class="nav navbar-nav pull left">
                <li class="active"><a href="http://altindex.io">Home</a></li>
                <li><a href="index.php">Articles</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Sign Out</a></li>
                <li><h3>user: <?php echo $_SESSION['login_user']; ?></h3></li>
                <p></p>
                </ul>

                <div class="navbar-header pull-left">
                <ul>
                <li>&lt;img&gt;imagename.jpg&lt;/img&gt;</li>
                <li>&lt;b&gt;<b>bold text</b>&lt;/b&gt;</li>
                <li>&lt;p&gt;<p>paragraph</b>&lt;/p&gt;</li>
                <li>&lt;center&gt;<center>centered text</center>&lt;/center&gt;</li>
                <li>&amp;quot; quote &quot;</li>
                <li>&amp;apos; apostrophe &apos;</li>
                </ul>
                </div>

                <div class="navbar-header pull-left">
                    <?php include('changelog.php'); ?>
                </div>
            </div>
		</div>

		<div class="col-sm-8">
            <form method="post" action="welcome.php" role="form" class='form'>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input class="form-control" id="title" name='title'>
                </div>
                <div class="form-group">
                    <label for="subtitle">Subtitle:</label>
                    <input class="form-control" id="subtitle" name='subtitle'>
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea class="form-control" rows="20" id="content" name='content'></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name='submit' id='submit' value='Submit'>
                </div>
            </form>
            <?php
                if($_POST['submit']){
                    if($success){
                    echo '<div class="alert alert-success">
                        <strong>Success!</strong> Article posted.
                    </div>';
                    }else{
                    echo '<div class="alert alert-danger">
                        <strong>Oops!</strong> Something went wrong while posting your article.
                    </div>';
                    }
                }
            ?>

            <form action="welcome.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submitpic">
            </form>

            <?php
                if($invalidPicType){
                    echo '<div class="alert alert-warning">
                        <strong>Sorry!</strong> This file type is not allowed.
                    </div>';
                }
            ?>
		</div>
	</div>
	</div>

	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/scripts.js"></script>
</body>

<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL & ~E_NOTICE);
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submitpic"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $invalidPicType = true;
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

</html>