<?php
require_once("database.php");
require_once("load.php");
require_once("diff.php");
require_once("feed.php");
global $db;
if(isset($_GET['id'])) {

  $feedid = $_GET['id'];
  if($db->feedExists($feedid)) {
    loadData($feedid);
    generateEntryForFeed($feedid);
    generateFeed($feedid);
  } else {
    echo "Invlid feedid";
  }

}