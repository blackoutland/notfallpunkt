#!/bin/bash

echo "Downloading KIWIX archives..."
echo "Download iFixit..."
wget https://download.kiwix.org/zim/ifixit/ifixit_de_all_2023-07.zim -P fileshare/kiwix/

echo "Download Wikipedia Medizin (DE)..."
wget https://download.kiwix.org/zim/wikipedia/wikipedia_de_medicine_maxi_2023-11.zim -P fileshare/kiwix/

echo "Download Klexikon..."
wget https://download.kiwix.org/zim/other/klexikon_de_all_maxi_2023-09.zim -P fileshare/kiwix/

#echo "Download Gutenberg Texts (DE)..."
#wget https://download.kiwix.org/zim/gutenberg/gutenberg_de_all_2023-08.zim -P fileshare/kiwix/

#echo "Download Wikipedia (DE, full)..."
#wget https://download.kiwix.org/zim/wikipedia/wikipedia_de_all_nopic_2023-10.zim -P fileshare/kiwix/

copy conf/fileshare/kiwix.json fileshare/kiwix/.files.json

echo "Downloading knowledge and Android files..."
wget https://blackout.land/nfp/dl_knowledge.zip -P temp/
wget https://blackout.land/nfp/dl_apk.zip -P temp/

echo "Unpacking files..."
unzip -d fileshare/public/files/apk temp/dl_apk.zip
unzip -d fileshare/public/knowledge temp/dl_knowledge.zip
rm temp/dl_apk.zip
rm temp/dl_knowledge.zip

echo "Dowloading OpenStreetMap files..."
wget https://download.openstreetmap.fr/extracts/europe/germany-latest.osm.pbf -P fileshare/public/files/osm/
copy conf/fileshare/osm.json fileshare/public/files/osm/.files.json
