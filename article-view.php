<?php

    /**

    * @param a {Integer} article id to get
    */

    // get the parameters

    $a = @$_GET['a'];
    if ($a && !preg_match('/^[0-9]+$/', $a)) {
        die("Invalid article parameter: $a");
    }

?>