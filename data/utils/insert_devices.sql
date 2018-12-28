DROP TABLE IF EXISTS mdevices;

DROP TABLE IF EXISTS devices;

CREATE TABLE devices (
device_id INTEGER,  
device_name VARCHAR,
device_description VARCHAR
);
 
INSERT INTO devices (device_id, device_name, device_description) VALUES
(0, 'Kurzweil ME', 'Kurzweil Micro Ensemble module'),
(1, 'Korg NS5R', 'Korg NS5R module'),
(2, 'Korg Krome', 'Korg Krome Synth'),
(3, 'Nord Electro 5D', 'Nord Electro 5D synth'),
(4, 'Yamaha Motif ESR', 'Yamaha Motif ES Rack');


