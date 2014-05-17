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

  function getTwoNewestEntrys($feedid) {
    $query = $this->sql->prepare('SELECT data.data AS data

                                  FROM          request
                                      LEFT JOIN data ON request.dataid == data.id

                                  WHERE feedid = 1

                                  ORDER BY request.time DESC
                                  LIMIT 2');

    $query->bindParam(':feedid', $feedid, SQLITE3_INTEGER);

    $resultArray = array();
    $result = $query->execute();
    $resultArray[] = $result->fetchArray(SQLITE3_ASSOC);
    $resultArray[] = $result->fetchArray(SQLITE3_ASSOC);
    $result->finalize();
    $query->close();

    $returnArray = array();

    if(isset($resultArray[0]['data']))
      $returnArray[] = $resultArray[0]['data'];
    else
      $returnArray[] = '';

    if(isset($resultArray[1]['data']))
      $returnArray[] = $resultArray[1]['data'];
    else
      $returnArray[] = '';

    return $returnArray;
  }

  function saveDiff($feedid, $data) {
    $query = $this->sql->prepare("INSERT INTO result(feedid, time, delta) VALUES (:feedid, strftime('%s', 'now'), :data)");
    $query->bindParam(':feedid', $feedid, SQLITE3_INTEGER);
    $query->bindParam(':data', $data, SQLITE3_BLOB);

    $result = $query->execute();
    $result->finalize();
    $query->close();
  }

  function getFeedDetails($feedid) {
    $query = $this->sql->prepare('SELECT * FROM feed WHERE id = :id');
    $query->bindParam(':id', $feedid, SQLITE3_INTEGER);

    $result = $query->execute();
    $resultArray = $result->fetchArray(SQLITE3_ASSOC);
    $result->finalize();
    $query->close();

    return $resultArray;
  }

  function getFeedData($feedid) {
    $query = $this->sql->prepare('SELECT * FROM result
                                  WHERE feedid = :id
                                  ORDER BY time DESC
                                  LIMIT 20');
    $query->bindParam(':id', $feedid, SQLITE3_INTEGER);

    $result = $query->execute();
    $returnArray = array();
    while($resultArray = $result->fetchArray(SQLITE3_ASSOC))
       $returnArray[] = $resultArray;
    $result->finalize();
    $query->close();

    return $returnArray;
  }

  function feedExists($feedid) {
    $query = $this->sql->prepare('SELECT COUNT(*) AS count FROM feed WHERE id = :id');
    $query->bindParam(':id', $feedid, SQLITE3_INTEGER);

    $result = $query->execute();
    $resultArray = $result->fetchArray(SQLITE3_ASSOC);
    $result->finalize();
    $query->close();

    return $resultArray['count'] > 0;
  }
}

global $db;
$db = new DeltafeedDatabase();