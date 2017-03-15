#!/bin/sh

cd "$(dirname "$0")";
./evewho-HADES.py
./evewho-S1R3N.py
rm ../../webroot/auth/alliance_characters.txt
cp alliance_characters.txt ../../webroot/auth/alliance_characters.txt
