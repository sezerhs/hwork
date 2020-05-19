import socket
import hashlib
import threading 


host = '127.0.0.1'
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
	s.send(str.encode('start'))
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
		print(res)
		break
	pass


def send_response(s):
	while True:
		data = input()
		if data == '+S':
			s.send(b'\0x00')
		if data == '+Q':
			s.send(b'\0x01')

		s.send(str.encode(data))
	pass

print("+s Starts The Game\n " +
	"exit: terminate the game\n" +
	"+Q: get a new question\n" +
	"+L: get a letter\n" +
	"+t: get a remaining time\n")

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
