#!/bin/sh
cat db.sql | sqlite3 db.sqlite
chmod og-r db.sqlite