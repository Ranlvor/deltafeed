#!/bin/sh
cp db.sqlite /tmp/db-tmp-copy-for-dump.sqlite
sqlite3 /tmp/db-tmp-copy-for-dump.sqlite "BEGIN; DELETE FROM data; DELETE FROM feed; DELETE FROM request; DELETE FROM result; DELETE FROM sqlite_sequence; COMMIT;"
sqlite3 /tmp/db-tmp-copy-for-dump.sqlite .dump > db.sql
rm /tmp/db-tmp-copy-for-dump.sqlite

