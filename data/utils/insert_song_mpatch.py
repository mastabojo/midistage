sql1 = 'DROP TABLE IF EXISTS song_patch;'
sql2 = '''CREATE TABLE "song_mpatch" (
song_patch_song INTEGER,
song_patch_device INTEGER,
song_patch_channel INTEGER,
song_patch_patch INTEGER
);
'''
sql3 = """
INSERT INTO song_patch (song_patch_song, song_patch_device, song_patch_channel, song_patch_patch) VALUES
(1, 1, 1, 7),
(1, 2, 1, 35)
"""
