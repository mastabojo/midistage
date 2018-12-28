import pygame.midi, json

pygame.midi.init()

c = pygame.midi.get_count()
devices = "%s midi devices found" % c + "\n"
for i in range(c):
    devices += "%s: " % (i)
    devices += "%s name: %s input: %s output: %s opened: %s" % (pygame.midi.get_device_info(i))+ "\n"
devices += "Default device: " + str(pygame.midi.get_default_output_id())
print(devices)


port = 4
channel = 0
bank0 = 0
bank32 = 0
program = 16
print "PORT: %d CH: %d BANK0: %d BANK32: %d PG: %d" % (port, channel, bank0, bank32, program)
out = pygame.midi.Output(port)
out.write_short(0xb0, 0, bank0)
out.write_short(0xb0, 32, bank32)
out.write_short(0xc0, program)
del out
pygame.midi.quit()
