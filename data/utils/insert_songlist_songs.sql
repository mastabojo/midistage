DROP TABLE songlist_songs IF EXISTS;

CREATE TABLE songlist_songs (
songlist_songlist_id INTEGER, 
songlist_song_id INTEGER);

INSERT INTO songlist_songs (songlist_songlist_id, songlist_song_id) VALUES 
(1, 3),
(1, 4),
(1, 1),
(1, 6),
(1, 5),
(1, 2),
(2, 6),
(2, 3),
(2, 1),
(2, 4),
(2, 2),
(2, 5),
(2, 6),
(2, 3),
(3, 5),
(3, 6),
(3, 4),
(3, 1),
(3, 3),
(3, 2),
(3, 3),
(3, 5);