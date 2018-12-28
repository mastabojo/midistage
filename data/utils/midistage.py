import sqlite3
# import mido

con = sqlite3.connect('data/mpdata.sqlite')

with con:
    cur = con.cursor()
    cur.execute('SELECT * FROM mdevices')
    rows = cur.fetchall()

    for row in rows:
        print row

