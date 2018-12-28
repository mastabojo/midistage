sql1 = 'DROP TABLE IF EXISTS songs;'
sql2 = '''CREATE TABLE songs (
song_id integer PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE, 
song_title VARCHAR, 
song_description VARCHAR, 
song_genre INTEGER
);
'''
sql3 = """
INSERT INTO songs (song_title, song_description, song_genre) VALUES
('Black Magic Woman', 'Something about a black magic woman', 0),
('Samba Pa Ti', 'Best instrumental', 0),
('Oye Como Va', 'Greg Rollie singing', 0),
('Europa', 'Second best instrumental', 0),
('Waterloo', 'Eurovision song contest 1974', 0),
('I do I do Ido', 'Cheezy', 0)
"""
