#!/usr/bin/python

'''
# structure of midiData (converted from JSON argument)
midiData = [port0, port1...]
'''
import sys, pygame, pygame.midi, json

# set up pygame
pygame.midi.init()

print 'ARGUMENTS:', str(sys.argv[1])

# read argument from JSON
ports = json.loads(sys.argv[1])
for port in ports:
    out = pygame.midi.Output(port)
    for ch in range(0, 16):
        print "PORT: %d CH: %d" % (port, ch)
        # out.write_short(0x7b, ch)
        out.write_short(0x3F3, 0x7b, ch)
    del out
pygame.midi.quit()
