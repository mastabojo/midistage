#!/usr/bin/python

import pygame.midi, json

pygame.midi.init()
c = pygame.midi.get_count()
devices = "%s midi devices found" % c + "\n"
for i in range(c):
    devices += "%s: " % i
    devices += "%s name: %s input: %s output: %s opened: %s" % (pygame.midi.get_device_info(i))+ "\n"

devices += "Default device: " + str(pygame.midi.get_default_output_id())
print(devices)
