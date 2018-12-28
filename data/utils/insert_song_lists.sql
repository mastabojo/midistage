DROP TABLE IF EXISTS song_lists;
CREATE TABLE song_lists (
song_list_id integer PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE, 
song_list_name VARCHAR, 
song_list_description VARCHAR 
);

INSERT INTO song_lists (song_list_name, song_list_description) VALUES
('ABBAMia 2 sets', 'Three hour 2 sets concert'),
('ABBAMia 3 sets', 'Three hour 3 sets concert'),
('ABBAMia 1 hour', 'One hour concerts');

