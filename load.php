<?php
require_once("database.php");

function loadData($feedid) {
  global $db;
  $options  = array('http'  => array('user_agent' => 'Deltafeed (Bot; +https://github.com/keine-ahnung/deltafeed)'));
  $context  = stream_context_create($options);
  $data = file_get_contents ($db->getFeedURLByID($feedid), false, $context);
  $blobid = $db->addRequestToDatabase($feedid, $data);
}