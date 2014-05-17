<?php
require_once("database.php");

function loadData($feedid) {
  global $db;
  $data = file_get_contents ($db->getFeedURLByID($feedid));
  $blobid = $db->addRequestToDatabase($feedid, $data);
}

loadData(1);