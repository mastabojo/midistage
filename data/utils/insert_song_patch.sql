DROP TABLE IF EXISTS song_patch;
CREATE TABLE song_patch (
song_patch_list INTEGER,
song_patch_song INTEGER,
song_patch_device INTEGER,
song_patch_channel INTEGER,
song_patch_patch_id INTEGER
);

INSERT INTO song_patch (song_patch_list, song_patch_song, song_patch_device, song_patch_channel, song_patch_patch_id) VALUES
(1, 1, 1, 1, 7),
(1, 1, 2, 1, 35);

