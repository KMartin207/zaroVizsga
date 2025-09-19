<?php

    $adb = mysqli_connect("localhost", "root", "", "parkolorendszer");
    if(!$adb) {
        die("Kapcsolódási hiba: " . mysqli_connect_error());
    }

    mysqli_query($adb, "INSERT INTO kartya VALUES (105241625, False)");

?>
