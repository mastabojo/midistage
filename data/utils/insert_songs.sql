DROP TABLE IF EXISTS songs;
CREATE TABLE songs (
song_id integer PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE, 
song_title VARCHAR, 
song_description VARCHAR, 
song_genre INTEGER,
song_key VARCHAR,
song_tempo INTEGER,
song_notes VARCHAR
);

INSERT INTO songs (song_title, song_description, song_genre, song_key, song_tempo, song_notes) VALUES
('Black Magic Woman', 'Something about a black magic woman', 0, 'Dm', 118, 'Notes'),
('Samba Pa Ti', 'Best instrumental', 0, 'G', 84, 'Change of rhytm in the middle'),
('Oye Como Va', 'Greg Rollie singing', 0, 'Am', 122, 'Am7 - D9'),
('Europa', 'Second best instrumental', 0, 'Hm', 102, 'EU is falling apart'),
('Waterloo', 'Eurovision song contest 1974', 0, 'D', 130, 'Ending - 6 times'),
('I do I do Ido', 'Cheezy', 0, 'C', 164, 'Modulation to D#');

