<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>

<!DOCTYPE html>
<html>
<head>
    <title>articles</title>


	<script src="/js/jquery-3.1.1.min.js"></script>

	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/scripts.js"></script>

	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/style.css" rel="stylesheet">
</head>
<body>
    <h1 style='text-align: center;'>articles</h1>
    <h3><a href='welcome.php'>Back</a></h3>
    <div class="container-fluid">
    <div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
    <?php

        include_once('config.php');
        $sql = "SELECT * FROM articles1 ORDER BY ID DESC";
        $result = mysqli_query($db, $sql);

        while($row = mysqli_fetch_array($result)){
            $title = $row['title'];
            $author = $row['author'];
            $content = base64_decode($row['content']);
            $subtitle = $row['subtitle'];
            $published = $row['published'];
            $showLogo = $row['showLogo'];
            $rowid = $row['id'];
        ?>
        <article>
            <div class="well">
                <div class="row"><center>article - <?php echo $rowid; ?></center></div>
                <div class="row">
                    <?php if($showLogo){ ?>
                        <div class="col-xs-2"><img src="/images/logo/transparenttclogo.png"></div>
                        <?php } ?>
                        <?php if ($showLogo){ ?>
                    <div class="col-xs-10">
                        <?php }else{ ?>
                    <div class="col-xs-12">
                    <?php } ?>

                        <?php if($row['published'] == 1){?>
                            <form method="POST">
                                <button type="submit" class="btn btn-success pull-right" name="publish" value="0">Published</button>
                                <?php echo "<input type='hidden' name='rowid' value='$rowid' />"; ?>
                            </form>
                        <?php }else{ ?>
                            <form method="POST">
                                <button type="submit" class="btn btn-danger pull-right" name="publish" value="1">Unpublished</button>
                                <?php echo "<input type='hidden' name='rowid' value='$rowid' />"; ?>
                            </form>
                        <?php } ?>

                        <h3><?php echo $title ?></h3><h4><?php echo $subtitle ." - " . $author ?></h4>
                            
                    </div>

                    <div class="article-body">
                        <div class="read">
                            <?php
                            $content = str_replace('<img>','<img src="/images/', $content);
                            $content = str_replace('</img>','">', $content);
                            echo $content;
                            ?>
                        </div>
                    </div>
                    <br>
                    <?php if($row['published'] == 0){?>
                        <form method="POST">
                            <?php echo "<button type='submit' class='btn btn-danger pull-left' name='delete' value='$rowid'>Delete $rowid</button>"; ?>
                        </form>
                    <?php }else{ ?>
                        <form method="POST">
                            <?php 
                                if($showLogo == 0){
                                    echo "<button type='submit' class='btn btn-default pull-left' name='toggleLogo' value='1'>Logo Off</button>";
                                    echo "<input type='hidden' name='rowid' value='$rowid' />";
                                }else{
                                    echo "<button type='submit' class='btn btn-primary pull-left' name='toggleLogo' value='0'>Logo On</button>";
                                    echo "<input type='hidden' name='rowid' value='$rowid' />";
                                }
                            
                            ?>
                        </form>
                    <?php } ?>

                </div>
            </div>
        </article>
    <?php
        }
    ?>
    </div>
    </div>
    </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST["delete"])) {
        $row = $_POST['delete'];
        $sql = "DELETE FROM articles1 WHERE id=$row";
        mysqli_query($db, $sql);
        $_POST = array();
        echo "<meta http-equiv='refresh' content='0'>";
    }
    if(isset($_POST["publish"])) {
        $pub = $_POST['publish'];
        $row = $_POST['rowid'];
        $sql = "UPDATE articles1 SET published=$pub WHERE id=$row";
        mysqli_query($db, $sql);
        $_POST = array();
        echo "<meta http-equiv='refresh' content='0'>";
    }
    if(isset($_POST["toggleLogo"])) {
        $row = $_POST['rowid'];
        $logo = $_POST['toggleLogo'];

        $sql = "UPDATE articles1 SET showLogo=$logo WHERE id=$row";
        //echo "<script type='text/javascript'>alert('$sql');</script>";
        mysqli_query($db, $sql);
        $_POST = array();
        echo "<meta http-equiv='refresh' content='0'>";
    }
}
?>