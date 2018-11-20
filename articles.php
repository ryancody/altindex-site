
<?php

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'publicreader');
    define('DB_PASSWORD', 'password');
    define('DB_DATABASE', 'altindex');
    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    if($a == null){
        $sql = "SELECT * FROM articles1 ORDER BY id DESC";
    }else{
        $sql = "SELECT * FROM articles1 WHERE id = $a";
    }
    $result = mysqli_query($db, $sql);


    while($row = mysqli_fetch_array($result)){
        $title = $row['title'];
        $author = $row['author'];
        $content = base64_decode($row['content']);
        $subtitle = $row['subtitle'];
        $published = $row['published'];
        $showLogo = $row['showLogo'];
        $articleID = $row['id'];
        if($published){
?>

    <article>
        <div class="well">
            <div class="row">
                <?php if($showLogo){ ?>
                    <div class="col-xs-2" style="padding: 0 0 0 0;margin: 20px 0 0 0;"><a href='/'><img src="/images/logo/newlogo.png" class="article-logo"></a></div>
                    <?php } ?>
                    <?php if ($showLogo){ ?>
                        <div class="col-xs-10" style="padding: 0 0 0 0;">
                    <?php }else{ ?>
                        <div class="col-xs-12">
                <?php }

                if($showLogo){
                    echo "<h3><a href='/?a=" . $articleID . "' class='internal'>" . $title . "</a></h3>";
                }else{
                    echo "<h3>" . $title . "<script>$(this).parent().attr('target','_blank');</script></h3>";
                } ?>
                <h4><?php echo $subtitle ." - " . $author ?></h4>

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

            </div>
        </div>
    </article>
<?php
        }
    }
?>

<?php if($a == 0){ ?>
<script>
//Initialize Variables
CharCount =  450;
articles = new Array();
$( document ).ready(function()
{
    $( "article" ).each(function( index )
    {
    //console.log( index + ": " + $( this ).find( ".read" ).text() );

    $(this).siblings( "center").remove();
    //console.log(index);
    articles[index] = $( this ).find( ".read" ).html();
    if($( this ).find( ".read" ).text().length > CharCount)
    {
        //console.log( "-- Shorten");
        var fullstring = $( this ).find( ".read" ).text();
        var eos = $( this ).find( ".read" ).text().length;
        var maxchars = fullstring.substring(0, CharCount);
        var breakpoint = maxchars.lastIndexOf(" ");
        var msg = maxchars.substring(0,breakpoint) + " ...";
        msg = "<p>" + msg + "</p>";
        msg = msg + "<div class='col-sm-12 text-center'>";
        msg = msg +     "<a class='action' style='cursor:pointer' onclick='toggleMoreLess(this," + index + ")'> more </a>";
        msg = msg + "</div>";

        $( this ).find( ".read" ).html(msg);
    }
    else
    {
        //console.log("--okay")
    }
    });
});

function toggleMoreLess(ev, id)
{
    if($(ev).closest("a").text().search("more") > -1)
    {
        //console.log($(ev).closest("a").html("Read Less"));
        $(ev).closest("article").css('height', 'auto');
        var msg = "";
        msg = msg + "<div class='col-sm-12 text-center'>";
        msg = msg +     "<a class='action' style='cursor:pointer' onclick='toggleMoreLess(this," + id + ")'> less </a>";
        msg = msg + "</div>";
        $( ev ).closest( ".read" ).html(articles[id] + msg );
        //console.log($( ev ).closest( ".read" ).html());
        //console.log(articles[id]);
    }
    else
    {
        $(ev).closest("article").css('height', 'vh');
        console.log($(ev).closest("a").html("more"));

        var fullstring = $( ev ).closest( ".read" ).text();
        var eos = $( ev ).closest( ".read" ).text().length;
        var maxchars = fullstring.substring(0, CharCount);
        var breakpoint = maxchars.lastIndexOf(" ");
        var msg = maxchars.substring(0,breakpoint) + " ...";
        msg = "<p>" + msg + "</p>";
        msg = msg + "<div class='col-sm-12 text-center'>";
        msg = msg +     "<a class='action' style='cursor:pointer' onclick='toggleMoreLess(this," + id + ")'> more </a>";
        msg = msg + "</div>";

        $( ev ).closest( ".read" ).html(msg);
    }
}

//Loop through all  anchor tags
$( "a" ).each(function( index ) {
  //Collect where the link is to
  var link = $( this ).attr("href");

  //Pattern to identify any external link (http, maybe s for secure then :// that is not alt index)
  var pattern = /https?:\/\//gmi;
  //Fill array with match or null if not external
  var myArray = pattern.exec(link);

  //Check array content
  if(myArray != null)
  {
        //Add Link in new tab if external
      $(this).attr('target', '_blank');
  }
});
</script>
<?php } ?>