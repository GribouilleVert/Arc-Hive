import serial
import json
from xbee import XBee
from pprint import pprint
import sys;

serial_port = serial.Serial(input('Port COM ? '), 9600)
xbee = XBee(serial_port)

def read_until(until):
    out = ''
    while until not in out:
        out += (xbee.wait_read_frame()['rf_data'].decode("utf-8"))
    return out

while True:
    try:

        payload = read_until("\r\n").strip("\r\n")
        data = json.loads(payload)

        #Print Data
        print(json.dumps(data, sort_keys=True, indent=4, separators=(',', ': ')) + "\n")


    except KeyboardInterrupt:
        break

serial_port.close()