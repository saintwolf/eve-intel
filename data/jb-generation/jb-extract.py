#!/usr/bin/python

from bs4 import BeautifulSoup
import roman

# BUILD A JB LIST SUITABLE FOR IMPORT INTO THE EVE-INTEL SERVER FROM A DOTLAN WEBPAGE

# be sure to pip install roman and BeautifulSoup4

# Load http://evemaps.dotlan.net/bridges/XXXXXXXX in a browser after authenticating to dotlan
# - view the source
# - paste the source into jb.html
# - run this script: python jb-extract.py 
# - use the contents to modify jb_friendly.tx
# - run ./jb-json.py
# - copy the results so the server can see them: cp ./jb.svg.json ../brave-intel-server/maps/jb.svg.json
# - kick the server - http://HOSTNAME:8080/EveIntelServer/reload

def extractMoon(td):
	split = td.contents[0].split()
	return "%d-%s" % (roman.fromRoman(split[0]), split[2])

def extractSystem(td):
	return td.a['href'].rsplit('/', 1)[-1].upper()

def bridge_tr_filter(tag):
    return tag.name == 'tr' and tag.has_attr('id') and tag['id'].startswith('bridge-')
# possibly re-enable strikethrough when dotlan gets better at recognizing jb sov is ihub - ihub matching.
#    return tag.name == 'tr' and tag.has_attr('id') and tag['id'].startswith('bridge-') and not (tag.has_attr('style') and -1 != tag['style'].find('line-through'))


def go():
	bridges = {}
	soup = BeautifulSoup(open("jb.html","r"), "lxml")
	for row in soup.find_all(bridge_tr_filter):
		tds = row.find_all("td")
		if 9 != len(tds):
			continue
		near = "%s %s" % (extractSystem(tds[0]), extractMoon(tds[1]))
		far  = "%s %s" % (extractSystem(tds[4]), extractMoon(tds[5]))
		# Assume only one bridge per system
		if bridges.has_key(near) or bridges.has_key(far):
			continue
		bridges[near] = far
	for key, value in bridges.items():
		print("%s <-> %s" % (key, value))
				
			
go()
