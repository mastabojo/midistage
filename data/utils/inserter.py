import sqlite3
import insert_songs
import insert_song_mpatch
import insert_devices
import insert_kurzweilme1

con = sqlite3.connect('data/mpdata.sqlite')
with con:
    cur = con.cursor()
    
    # songs
    for s in insert_devices.sqlList:
        cur.execute(s)
    cur.execute(insert_devices.sqlInsert)


    # song - patch mappings
    #cur.execute(insert_song_mpatch.sql1)
    #cur.execute(insert_song_mpatch.sql2)
    #cur.execute(insert_song_mpatch.sql3)	
    # devices
    #cur.execute(insert_devices.sql1)
    #cur.execute(insert_devices.sql2)
    #cur.execute(insert_devices.sql3)	
    # Kurzweil ME1
    #cur.execute(insert_kurzweilme1.sql1)
    #cur.execute(insert_kurzweilme1.sql2)
    #cur.execute(insert_kurzweilme1.sql3)
