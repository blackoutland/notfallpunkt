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

