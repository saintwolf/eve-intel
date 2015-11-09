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
import codecs
import configobj
import glob
import os
import re
import requests
import threading
import time
import wx

class IntelReporter(object):
	def __init__(self):
		self.filter  = None
		self.logdir  = None
		self.token   = None
		self.url     = None
		self.files   = {}

		self.lock    = threading.Lock()
		self.thread  = None
		self.running = False

	def OpenLogFiles(self):
		files = sorted(glob.glob(os.path.join(self.logdir, '*')), reverse = True)
		regex = re.compile(self.filter)

		for f in files:
			match = regex.search(f)
			if match == None: continue
			match = match.groups(0)[0]

			if match not in self.files:
				self.Print('Watching file: %s' % f)
				self.files[match] = codecs.open(f, 'r', encoding = 'utf-16')
				self.files[match].seek(0, 2)

	def CloseLogFiles(self):
		for key, value in self.files.iteritems():
			self.Print('Closing file: %s' % value.name)
			value.close()
		self.files = {}

	def ReadLogs(self):
		while self.running == True:
			for key, value in self.files.iteritems():
				for line in value:
					try:
						if ord(line[1]) == 91: line = line[1:]
						line = line.rstrip()

						if re.match('(\[ \d{4}\.\d{2}\.\d{2} \d{2}:\d{2}:\d{2} ] .* > .*)', line):
							self.Print('%s' % line)
							response = requests.post(self.url, data = {'uploader_token': self.token, 'text': line}, verify = False)
							if response.status_code == 401: raise ValueError('Your uploader token is invalid.')

					except Exception, e:
						self.Print('Error: %s' % e)
						continue

			time.sleep(0.1)

	def Print(self, message):
		print '%s' % message

	def Start(self, config):
		self.filter  = config['filter']
		self.logdir  = config['logdir']
		self.token   = config['token' ]
		self.url     = config['url'   ]

		self.OpenLogFiles()

		self.running = True
		self.thread  = threading.Thread(target = self.ReadLogs)
		self.thread.start()

	def Stop(self):
		if self.running == True:
			self.running = False
			self.thread.join()
			self.CloseLogFiles()

class IntelReporterCLI(IntelReporter):
	def __init__(self, config):
		super(IntelReporterCLI, self).__init__()

		config.Validate()
		self.Start(config.GetValues())

class IntelReporterWindowed(IntelReporter, wx.Frame):
	def __init__(self, config):
		super(IntelReporterWindowed, self).__init__()
		wx.Frame.__init__(self, parent = None)
		self.config = config

		self.InitUI()

	def InitUI(self):
		# MenuBar
		menubar = wx.MenuBar()
		self.SetMenuBar(menubar)

		fileMenu = wx.Menu()
		menubar.Append(fileMenu, '&File')

		fitem = fileMenu.Append(wx.ID_EXIT, 'Quit', 'Quit application')
		self.Bind(wx.EVT_MENU, self.OnQuit, fitem)

		# Sizers
		windowSizer = wx.BoxSizer(wx.VERTICAL)
		self.SetSizer(windowSizer)

		configSizer = wx.GridBagSizer(0, 0)
		windowSizer.Add(configSizer, 0, wx.EXPAND)

		# Log Configuration
		self.filterLabel = wx.StaticText(self, label = 'Filter: ')
		self.filterEntry = wx.TextCtrl(self)
		self.filterEntry.SetValue(self.config['filter'])

		self.logdirLabel = wx.StaticText(self, label = 'Directory: ')
		self.logdirEntry = wx.TextCtrl(self, style = wx.TE_READONLY)
		self.logdirEntry.SetValue(self.config['logdir'])

		self.logdirBrowseButton = wx.Button(self, label = 'Browse')
		self.Bind(wx.EVT_BUTTON, self.OnBrowseLogDir, self.logdirBrowseButton)

		configSizer.Add(self.filterLabel, wx.GBPosition(0, 0), wx.GBSpan(1, 1), wx.ALIGN_CENTER_VERTICAL)
		configSizer.AddGrowableCol(1)
		configSizer.Add(self.filterEntry, wx.GBPosition(0, 1), wx.GBSpan(1, 1), wx.EXPAND)
		configSizer.Add(self.logdirLabel, wx.GBPosition(0, 2), wx.GBSpan(1, 1), wx.ALIGN_CENTER_VERTICAL)
		configSizer.AddGrowableCol(3)
		configSizer.Add(self.logdirEntry, wx.GBPosition(0, 3), wx.GBSpan(1, 1), wx.EXPAND)
		configSizer.Add(self.logdirBrowseButton, wx.GBPosition(0, 4), wx.GBSpan(1, 1))

		# Server Configuration
		self.urlLabel   = wx.StaticText(self, label = 'URL: ')
		self.urlEntry   = wx.TextCtrl(self)
		self.urlEntry.SetValue(self.config['url'])

		self.tokenLabel = wx.StaticText(self, label = 'Token: ')
		self.tokenEntry = wx.TextCtrl(self)
		self.tokenEntry.SetValue(self.config['token'])

		configSizer.Add(self.urlLabel, wx.GBPosition(1, 0), wx.GBSpan(1, 1), wx.ALIGN_CENTER_VERTICAL)
		configSizer.AddGrowableCol(1)
		configSizer.Add(self.urlEntry, wx.GBPosition(1, 1), wx.GBSpan(1, 1), wx.EXPAND)
		configSizer.Add(self.tokenLabel, wx.GBPosition(1, 2), wx.GBSpan(1, 1), wx.ALIGN_CENTER_VERTICAL)
		configSizer.AddGrowableCol(3)
		configSizer.Add(self.tokenEntry, wx.GBPosition(1, 3), wx.GBSpan(1, 1), wx.EXPAND)

		# Save
		self.saveButton = wx.Button(self, label = 'Save')
		self.Bind(wx.EVT_BUTTON, self.OnSaveConfig, self.saveButton)
		configSizer.Add(self.saveButton, wx.GBPosition(1, 4), wx.GBSpan(1, 1))

		# Start
		self.startButton = wx.ToggleButton(self, label = 'Start')
		self.Bind(wx.EVT_TOGGLEBUTTON, self.OnToggleThread, self.startButton)
		configSizer.Add(self.startButton, wx.GBPosition(0, 5), wx.GBSpan(2, 1), wx.EXPAND)

		# Output
		self.outputEntry = wx.TextCtrl(self, style = wx.TE_MULTILINE|wx.TE_READONLY|wx.TE_WORDWRAP)
		windowSizer.Add(self.outputEntry, 1, wx.EXPAND)

		# Window
		self.Bind(wx.EVT_CLOSE, self.OnClose)
		self.SetSize((800, 600))
		self.SetTitle('Intel Reporter')
		self.Centre()
		self.Show(True)

	def OnBrowseLogDir(self, event):
		dialog = wx.DirDialog(self, "Choose a directory:")
		if dialog.ShowModal() == wx.ID_OK: self.logdirEntry.SetValue(dialog.GetPath())
		dialog.Destroy()

	def OnSaveConfig(self, event):
		self.config['cli'   ] = False
		self.config['filter'] = self.filterEntry.GetValue()
		self.config['logdir'] = self.logdirEntry.GetValue()
		self.config['token' ] = self.tokenEntry .GetValue()
		self.config['url'   ] = self.urlEntry   .GetValue()
		self.config.Validate()

		self.Print('Saving configuration to: %s' % self.config.filename)
		self.config.write()

	def OnToggleThread(self, event):
		if self.running == True:
			self.Stop()
			self.startButton.SetValue(False)
			self.startButton.SetLabel('Start')
			return

		self.config['filter'] = self.filterEntry.GetValue()
		self.config['logdir'] = self.logdirEntry.GetValue()
		self.config['token' ] = self.tokenEntry .GetValue()
		self.config['url'   ] = self.urlEntry   .GetValue()
		self.config.Validate()

		self.startButton.SetValue(True)
		self.startButton.SetLabel('Stop')
		self.Start(self.config.GetValues())

	def OnQuit(self, event):
		self.Close()

	def OnClose(self, event):
		self.Stop()
		self.Destroy()

	def Print(self, message):
		self.outputEntry.SetInsertionPointEnd()
		self.outputEntry.WriteText('%s\n' % message)

class Config(configobj.ConfigObj):
	def __init__(self, options):
		if isinstance(options.config, str) == False or os.path.isfile(options.config) == False:
			cwd = os.path.dirname(os.path.realpath(__file__))
			config = '%s/uploader.ini' % cwd
		configobj.ConfigObj.__init__(self, config)

		self.MergeOptions(options)
		self.SetDefaults()

	def MergeOptions(self, options):
		if options.cli != None:
			self['cli'] = bool(options.cli)

		if options.filter != None:
			self['filter'] = str(options.filter)

		if options.logdir != None:
			self['logdir'] = str(options.logdir)

		if options.token != None:
			self['token'] = str(options.token)

		if options.url != None:
			self['url'] = str(options.url)

	def SetDefaults(self):
		# cli
		try:
			self['cli']
			if isinstance(self['cli'], bool) == False: raise
		except: self['cli'] = False

		# filter
		try:
			self['filter']
			if isinstance(self['filter'], str) == False or len(self['filter']) == 0: raise
		except: self['filter'] = '(.*)_\d+_\d+'

		# logdir
		try:
			self['logdir']
			if isinstance(self['logdir'], str) == False or len(self['logdir']) == 0: raise
		except: self['logdir'] = '%s/Documents/EVE/logs/Chatlogs' % os.path.expanduser('~')

		# token
		try:
			self['token']
			if isinstance(self['token'], str) == False: raise
		except: self['token'] = ''

		# url
		try:
			self['url']
			if isinstance(self['url'], str) == False or len(self['url']) == 0: raise
		except: self['url'] = 'http://intel.example.com/report'

	def Validate(self):
		return

	def GetValues(self):
		return {
			'filter': self['filter'],
			'logdir': self['logdir'],
			'token' : self['token' ],
			'url'   : self['url'   ],
		}

if __name__ == '__main__':
	parser = argparse.ArgumentParser(description = 'Intel Reporter')
	parser.add_argument('--cli'   , type = str , nargs = '?', dest = 'cli'   , required = False, help = 'run using the command line interface'   , default = False, const = True)
	parser.add_argument('--config', type = str , nargs = '?', dest = 'config', required = False, help = 'the path to a configuration file to use' )
	parser.add_argument('--filter', type = str , nargs = '?', dest = 'filter', required = False, help = 'the regex filter to pick files to watch' )
	parser.add_argument('--logdir', type = str , nargs = '?', dest = 'logdir', required = False, help = 'the directory to read log files from'    )
	parser.add_argument('--token' , type = str , nargs = '?', dest = 'token' , required = False, help = 'the uploader token to send reports using')
	parser.add_argument('--url'   , type = str , nargs = '?', dest = 'url'   , required = False, help = 'the url to upload reports to'            )
	config = Config(options = parser.parse_args())

	# CLI
	if config['cli'] == True:
		IntelReporterCLI(config)

	# Windowed
	else:
		app = wx.App(redirect = True)
		win = IntelReporterWindowed(config)
		app.MainLoop()
