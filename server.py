#!/usr/bin/env python3

from threading import Thread
import socket
import secrets
import json
import hashlib

HOST = '127.0.0.1'
PORT = 1775


def returnStudentWithId(studentId):
	with open('students.json', 'r') as f:
		studentsList = json.load(f)

	for students in studentsList:
		if(studentId == students['id']):
			return students['hex']



with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
	s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
	s.bind((HOST, PORT))
	s.listen(5)
	conn, addr = s.accept()
	with conn:
		print('Connected by', addr)
		while True:
			conn.setblocking(True)
			data = conn.recv(1024).decode('utf-8')
			print('-> client said',data)
			if data == 'start':
				status = 'auth'
				nonceHex = secrets.token_hex(16);
				conn.send(str.encode(nonceHex))
			elif status == 'auth':
				if(len(data) == 50):
					params = (data.split('#'))
					if(len(params) ==2):
						studentHex = returnStudentWithId(params[1])
						shaSum = hashlib.sha1()
						merge = bytes(nonceHex+studentHex,'utf8')
						shaSum.update(merge)
						results = shaSum.hexdigest()
						if results == params[0]:
							print('yes')
						else:
							print('auth fail')
							break
			else:
				conn.send(str.encode("ERR: Wrong input to start communication\n"))
				break
