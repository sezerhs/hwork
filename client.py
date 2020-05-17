#!/usr/bin/env python3

import socket
import secrets
import hashlib

from pprint import pprint

host = '127.0.0.1'  # The server's hostname or IP address
port = 1775        # The port used by the server

s = socket.socket()
s.connect((host,port))
shex = '56617FAC7294D0CBF74951BF7B8F078F'

def mergesha(hexcode):
	hexcode  = bytes(hexcode,'utf8')
	sid = '150160551'
	h = hashlib.sha1()
	h.update(hexcode)
	results = h.hexdigest()
	return results + '#' + sid

while True:
	commandSend = input('Command :')
	s.send(str.encode(commandSend));
	data = s.recv(1024).decode('utf-8')
	print('-> server said',data)
	if(len(data) == 32):
		# ~ clientHex = secrets.token_hex(16)
		clientHex = shex
		merge = data + clientHex
		sha = mergesha(merge)
		s.send(str.encode(sha))
