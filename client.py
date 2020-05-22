import socket
import hashlib
import threading 
from struct import *
import sys

'''
160.75.154.73
1773
'''

host = '160.75.154.73'
#host = '127.0.0.1'
port = 1773


shex = '56617FAC7294D0CBF74951BF7B8F078F'
def mergesha(hexcode):
	hexcode  = bytes(hexcode,'utf8')
	sid = '150160551'
	h = hashlib.sha1()
	h.update(hexcode)
	results = h.hexdigest()
	return results + '#' + sid


def authenticate(s):
	s.send(str.encode('Start_Connection'))
	while True:
		res = s.recv(1024).decode('utf-8')
		if(len(res) == 32):
			clientHex = shex
			merge = res + clientHex
			sha = mergesha(merge)
			s.send(str.encode(sha))
		break


def parse_packet(res):
	while True:
		packetsize = sys.getsizeof(res)
		if(packetsize == 39):
			##remaing time
			MessageHeader  = res[0:4]
			Message  = res[4:6]
			time = unpack('h',Message)[0]
			## maybe after check this line
			print(('recevied time ==========> ' + str(time)))
		elif res[0:1] == b'\x01':
			print(str.encode('================HEADER x01 ====================='))
			try:
				print(res.decode('utf-8'))
			except:
				print(res.decode('utf-16'))
		elif res[0:1] == b'\x00':
			print(str.encode('================HEADER x00 ====================='))
			try:
				print(res.decode('utf-8'))
			except:
				print(res.decode('utf-16'))
		else:
			print(res.decode('utf-8'))
			pass
		break
	pass

'''
var Request = {
    START_GAME: 0x00,
    TERMINATE_GAME: 0x01,
    ASK_QUESTION: 0x02,
    GET_LETTER: 0x03,
    GUESS: 0x04,
    GET_REMAIN_TIME: 0x05
}
'''

def send_response(s):
	while True:
		data = input()
		if data == '+S':
			s.send(b'\x00')
		elif data == '+Q':
			s.send(b'\x02')
		elif data == '+L':
			s.send(b'\x03')
		elif data == 'exit':
			s.send(b'\x01')
		elif data == '+T':
			s.send(b'\x05')
		else:
			s.send(b'\x04' + data.encode())

print(
	"=============================\n" +
	"+S Starts The Game\n " +
	"exit: terminate the game\n" +
	"+Q: get a new question\n" +
	"+L: get a letter\n" +
	"+T: get a remaining time\n" +
	"===============================\n")

with socket.socket(socket.AF_INET,socket.SOCK_STREAM) as s:
	s.connect((host, port))
	authenticate(s)

	autgoing = threading.Thread(target=send_response,args=(s,))
	print("Main: Before running")
	autgoing.start()

	while True:
		# ~ res = s.recv(1024)
		res = s.recv(1024)
		parse_packet(res)
