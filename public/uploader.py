#!/usr/bin/env python
# 
# The MIT License (MIT)
# 
# Copyright (c) 2015 msims04
# 
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
# 
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
# 
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

import argparse
import atexit
import codecs
import daemon
import glob
import re
import requests
import threading
import time

from os import listdir, sep
from os.path import expanduser, isfile, join

def run(options):
	class LogParser():
		def __init__(self, logdir, token, url):
			self.logdir   = logdir
			self.logfiles = self.getLatestLogFiles()
			self.token    = token
			self.url      = url
			self.timer    = None

			atexit.register(self.cleanup)

		def getLatestLogFiles(self):
			files  = sorted(glob.glob(join(self.logdir, '*.imperium*')), reverse = False)
			regex  = re.compile('.*\\'+sep+'(\w+)\.imperium.*')
			result = {}

			for f in files:
				match = regex.match(f).groups(0)[0]
				if not match in files and isfile(f):
					result[match] = codecs.open(f, 'r', encoding = 'utf-16')
					result[match].seek(0, 2)

			return result

		def callback(self):
			regex = re.compile('\[ (\d{4}\.\d{2}\.\d{2} \d{2}:\d{2}:\d{2}) ] (.*) > (.*)')
			for key, value in self.logfiles.iteritems():
				for line in value:
					try:
						if regex.match(line[1:]):
							print line[1:]
							response = requests.post(self.url, data = {'uploader_token': self.token, 'text': line[1:]}, verify = False)

					except Exception as e:
						print e
						continue

			self.timer = threading.Timer(1.0, self.callback)
			self.timer.start()

		def run(self):
			self.timer = threading.Timer(1.0, self.callback)
			self.timer.start()

		def cleanup(self):
			return

	parser = LogParser(options.logdir, options.token, options.url)
	parser.run()

if __name__ == '__main__':
	# Parse the command line arguments.
	parser = argparse.ArgumentParser(description = 'EVE Online Intel Reporter')
	parser.add_argument(      '--logdir' , type = str , nargs = '?', dest = 'logdir' , required = False, default = expanduser('~')+'/Documents/EVE/logs/Chatlogs', help = 'the log directory to read from')
	parser.add_argument(      '--token'  , type = str , nargs = '?', dest = 'token'  , required = True, help = 'the uploader token to authenticate with')
	parser.add_argument(      '--url'    , type = str , nargs = '?', dest = 'url'    , required = True, help = 'the url to upload reports to')
	parser.add_argument('-q', '--quiet'  , type = bool, nargs = '?', dest = 'quiet'  , default = False, const = True, help = 'suppress trivial messages')
	parser.add_argument('-v', '--verbose', type = bool, nargs = '?', dest = 'verbose', default = False, const = True, help = 'output additional messages')
	parser.add_argument('-d', '--daemon' , type = bool, nargs = '?', dest = 'daemon' , default = False, const = True, help = 'run as a daemon')
	options = parser.parse_args()

	# Run
	if not options.daemon:
		run(options)

	else:
		try:
			os.environ['SUPERVISOR_ENABLED']
			run(options)

		except:
			context = daemon.DaemonContext(working_directory = sys.path[0], stderr = log_file)
			with context:
				run(options)
