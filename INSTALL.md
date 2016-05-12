Requirements
============

MySQL, PHP, Tomcat, Python


Database
========

1. Create mysql schema
    a. Apply schema.sql
2. Configure connection via DATABASE* settings in BraveIntelServer/src/main/java/de/schoar/braveintelserver/C.java and $cfg_sql_* settings in webroot.php (after copying from config.php.dist)


Data
====

Build these data files manually and place them in the correct directories.

1. Filter settings
    a. cd data/brave-intel-server/filter
    b. Adapt files as needed

2. Map generation
    a. cd data/map-generation
    b. ./dotlan-download.sh
    c. ./dotlan-convert.sh
    d. Copy dotlan/*.svg.json files to ../brave-intel-server/maps

3. Jumpbridge generation
    a. cd data/jb-generation
    b. Edit jb_friendly.txt and jb_hostile.txt
    c. ./jb-json.py
    d. Copy jb.svg.json file to ../brave-intel-server/maps

4. Deployment
    a. Make data/brave-intel-server readable to the tomcat process
    b. Configure DATA_DIR in BraveIntelServer/src/main/java/de/schoar/braveintelserver/C.java to point to this directory.


Backend
=======

1. Settings
    a. Edit BraveIntelServer/src/de/schoar/braveintelserver/C.java
	- Update mysql connection settings
	- Update location of brave-intel-server data directory

2. Deploy
    a. cd BraveIntelServer
    b. mvn clean package
    c. Copy target/EveIntelServer.war into tomcat webapps folder


Frontend
========

1. Config
    a. Copy config.php.dist to config.php
    b. Edit config.php
	- Update mysql connection settings
        - Update alliance/coalition specific display settings, add custom graphics logo
	- Choose authorization scheme, then do one of ...
	    - Update brave core tokens
	    - Update eve SSO tokens
    c. Customize default region by setting 'map' in webroot/js/intel_map_draw.js
    d. Customize region list in webroot/tpl/tpl_nav_map.php (duplicate most relevant regions at top of list)

2. Auth
    1. If using brave core authorization.
    2. cd webroot/auth && composer install


Uploader
========

See the unbranded fork of the Brave Intel Reporter at https://github.com/IslayTzash/EveIntelReporter
