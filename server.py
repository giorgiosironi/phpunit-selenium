#!/usr/bin/python

import BaseHTTPServer, SimpleHTTPServer
import ssl
import sys

httpd = BaseHTTPServer.HTTPServer(('localhost', int(sys.argv[1])), SimpleHTTPServer.SimpleHTTPRequestHandler)
httpd.socket = ssl.wrap_socket (httpd.socket, certfile='../ssl/server.pem', server_side=True)
httpd.serve_forever()
