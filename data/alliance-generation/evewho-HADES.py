#!/usr/bin/python

import json,urllib2,time

allianceId='99005224'
page=0
ids=[]

while True:
	url = "http://evewho.com/api.php?type=allilist&id=%s&page=%d" % (allianceId, page)
	#print "URL %s" % (url)
	headers = { 'User-Agent' : 'Mozilla/5.0' }
	req = urllib2.Request(url, None, headers)
	data = urllib2.urlopen( req ).read()
	#print data
	output = json.loads(data)
	if not output["characters"]:
		break;
	for c in output["characters"]:
		ids.append(c["character_id"])
	time.sleep(1)
	page += 1

print "Read %d pages, Found %d characters" % (page, len(ids))

file = open("alliance_characters.txt", "w")
for i in ids:
	file.write( "%s\n" % (i) )
file.close
