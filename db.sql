PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "feed" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "url" TEXT
);
CREATE TABLE "request" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "feedid" INTEGER,
    "time" INTEGER,
    "dataid" INTEGER
);
CREATE TABLE "data" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "data" BLOB
);
CREATE TABLE "result" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "feedid" INTEGER,
    "time" INTEGER,
    "delta" TEXT
);
CREATE INDEX "resultFeedTime" on result (feedid ASC, time ASC);
CREATE INDEX "requestFeedTime" on request (feedid ASC, time ASC);
CREATE UNIQUE INDEX "dataunique" on data (data ASC);
CREATE VIEW requestHR
AS
SELECT    request.id                              AS request,
             feed.url                             AS url,
 datetime(request.time, 'unixepoch', 'localtime') AS time,
             data.data                            AS data

FROM         request
      LEFT JOIN feed ON request.feedid == feed.id
      LEFT JOIN data ON request.dataid == data.id;
COMMIT;
