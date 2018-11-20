<?php 
// sweet reference
// https://www.labnol.org/internet/direct-links-for-google-drive/28356/

include_once('announcements_key.php')
$announcementURL = "https://docs.google.com/document/d/" . $announcementKEY . "/export?format=txt";

$announcements = file_get_contents($announcementURL);

echo $announcements;
?>
