<?php
$feedid = 1;
require_once("database.php");
$feedDetails = $db->getFeedDetails($feedid);
$feedData = $db->getFeedData($feedid);
header("Content-Type: application/atom+xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<feed xmlns="http://www.w3.org/2005/Atom">
  <author>
    <name>none</name>
  </author>
  <atom:generator>DeltaFeed</atom:generator>
  <title><?=$feedDetails['title']?></title>
  <id>deltafeed:feedid<?=$feedDetails['id']?></id>
  <updated><?=date(DateTime::ATOM, $feedData[0]['time'])?></updated>
 
<?php
foreach($feedData as $data) {
?>
  <entry>
    <title>Detected Change for feed <?=$feedDetails['title']?></title>
    <link href="<?=$feedDetails['url']?>"/>
    <id>deltafeed:feedid<?=$feedDetails['id']?>:resultid:<?=$data['id']?></id>
    <updated><?=date(DateTime::ATOM, $data['time'])?></updated>
    <content type="html"><![CDATA[<link rel="stylesheet" href="styles.css" type="text/css" charset="utf-8"/><?=$data['delta']?>]]></content>
  </entry>
<?php
}
?>
</feed>