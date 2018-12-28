#!/usr/bin/python

''''

# structure of midiData (converted from JSON argument)
midiData = [
    {'port': 2, 'channel': 0, 'bank0': 0, 'bank32': 6, 'program': 22},
    {'port': 2, 'channel': 1, 'bank0': 0, 'bank32': 0, 'program': 5}
    ]
    
# example of command with JSON argument
python set_devices.py '[
{"port": 2, "channel": 0, "bank0": 0, "bank32": 6, "program": 22}, 
{"port": 2, "channel": 1, "bank32": 0, "bank0": 0, "program": 5}
]'
'''

import sys, pygame, pygame.midi, json
import logging
## logging.basicConfig(filename='/var/www/html/log/midistage.log',level=logging.DEBUG)
## logging.debug(str(sys.argv[1]))

# set up pygame
pygame.midi.init()

# read argument from JSON
midiData = json.loads(sys.argv[1])
for data in midiData:
    ### print "PORT: %d CH: %d BANK0: %d BANK32: %d PG: %d" % (data['port'], data['channel'], data['bank0'], data['bank32'], data['program'])

    # logging.debug("PORT: %d" % (data['port']))
    # logging.debug("BANK0: %d" % (data['bank0']))
    # logging.debug("BANK32: %d" % (data['bank32']))
    # logging.debug("PATCH: %d" % (data['program']))

    out = pygame.midi.Output(data['port'])
    out.write_short(0xb0, 0, data['bank0'])
    out.write_short(0xb0, 32, data['bank32'])
    out.write_short(0xc0, data['program'])
    del out
pygame.midi.quit()
