#!/usr/bin/python

import json,urllib,time

allianceId='1042504553'
page=0
ids=[]

while True:
	data = urllib.urlopen( "http://evewho.com/api.php?type=allilist&id=%s&page=%d" % (allianceId, page) ).read()
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
