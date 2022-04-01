#!/usr/bin/python
#-*- coding: utf-8 -*-

import sqlite3, sys
import MySQLdb

db = MySQLdb.connect("localhost", "starmonger", "og2te8Ohwu0woh5ahdei", "starmonger")
db_cursor = db.cursor()

con = sqlite3.connect('./starmonger.db')
cursor = con.cursor()
cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
cursor.execute("SELECT * FROM twitter_favorite")
rows = cursor.fetchall()

for line in rows:
    print(line)
    result = db_cursor.execute("""
        INSERT INTO twitter_favorite (`id`, `href`, `user`, `content`, `json`,
            `protected`, `created_at`, `saved_at`)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)""", line)

db.commit()
