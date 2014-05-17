<?php
class DeltafeedDatabase {
  private $sql;

  function DeltafeedDatabase() {
    $this->sql = new SQLite3('db.sqlite');
    $this->sql->busyTimeout(60000);
  }


  function getFeedURLByID($feedid) {
    $query = $this->sql->prepare('SELECT url FROM feed WHERE id = :id');
    $query->bindParam(':id', $feedid, SQLITE3_INTEGER);

    $result = $query->execute();
    $resultArray = $result->fetchArray(SQLITE3_ASSOC);
    $result->finalize();
    $query->close();

    //print_r($resultArray);
    return $resultArray['url'];
  }

  function addRequestToDatabase($feedid, $data) {
    $query = $this->sql->prepare('INSERT OR IGNORE INTO data(\'data\') VALUES (:data)');
    $query->bindParam(':data', $data, SQLITE3_BLOB);

    $result = $query->execute();
    $result->finalize();
    $query->close();

    $query = $this->sql->prepare('SELECT id FROM data WHERE data = :data');
    $query->bindParam(':data', $data, SQLITE3_BLOB);

    $result = $query->execute();
    $resultArray = $result->fetchArray(SQLITE3_ASSOC);
    $result->finalize();
    $query->close();

    $blobid = $resultArray['id'];

    $query = $this->sql->prepare("INSERT INTO request(feedid, time, dataid) VALUES (:feedid, strftime('%s', 'now'), :dataid)");
    $query->bindParam(':feedid', $feedid, SQLITE3_INTEGER);
    $query->bindParam(':dataid', $blobid, SQLITE3_INTEGER);

    $result = $query->execute();
    $result->finalize();
    $query->close();
  }
}

global $db;
$db = new DeltafeedDatabase();