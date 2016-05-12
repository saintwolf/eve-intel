Requirements
============

MySQL, PHP, Tomcat, Python


Database
========

1. Create mysql schema
  1. Apply `schema.sql`
2. Configure connection via `DATABASE*` settings in `BraveIntelServer/src/main/java/de/schoar/braveintelserver/C.java` and `$cfg_sql_*` settings in `webroot.php` (after copying from `config.php.dist`)


Data
====

Build these data files manually and place them in the correct directories.

1. Filter settings
  1. `cd data/brave-intel-server/filter`
  2. Adapt files as needed

2. Map generation
  1. `cd data/map-generation`
  2. `./dotlan-download.sh`
  3. `./dotlan-convert.sh`
  4. `cp dotlan/*.svg.json ../brave-intel-server/maps`

3. Jumpbridge generation
  1. `cd data/jb-generation`
  2. Edit `jb_friendly.txt` and `jb_hostile.txt`
  3. `./jb-json.py`
  4. `cp jb.svg.json ../brave-intel-server/maps`

4. Deployment
  1. Make `data/brave-intel-server` readable to the tomcat process
  2. Configure `DATA_DIR` in `BraveIntelServer/src/main/java/de/schoar/braveintelserver/C.java` to point to this directory.


Backend
=======

1. Settings
  1. Edit `BraveIntelServer/src/de/schoar/braveintelserver/C.java`
    - Update mysql connection settings - `DATABASE*` variables
    - Update location of brave-intel-server data directory - `DATA_DIR` variable

2. Deploy
  1. `cd BraveIntelServer`
  2. `mvn clean package`
  3. Copy `target/EveIntelServer.war` into tomcat webapps folder


Frontend
========

1. Config
  1. Copy `config.php.dist` to `config.php`
  2. Edit `config.php`
    - Update mysql connection settings -  `$cfg_sql_*`
    - Update alliance/coalition specific display settings, add custom graphics logo
    - Choose authorization scheme, then do one of ...
      - Update brave core tokens
      - Update eve SSO tokens
  3. Customize default region by setting `map` in `webroot/js/intel_map_draw.js`
  4. Customize region list in `webroot/tpl/tpl_nav_map.php` (duplicate most relevant regions at top of list)

2. Auth
  - If using brave core authorization.
    - `cd webroot/auth && composer install`


Uploader
========

See the unbranded fork of the Brave Intel Reporter at https://github.com/IslayTzash/EveIntelReporter
