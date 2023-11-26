#!/bin/bash

# Function
function askYesNo {
        QUESTION=$1
        DEFAULT=$2
        if [ "$DEFAULT" = true ]; then
                OPTIONS="[J/n]"
                DEFAULT="j"
            else
                OPTIONS="[j/N]"
                DEFAULT="n"
        fi
        read -p "$QUESTION $OPTIONS " -n 1 -s -r INPUT
        INPUT=${INPUT:-${DEFAULT}}
        echo ${INPUT}
        if [[ "$INPUT" =~ ^[jJ]$ ]]; then
            ANSWER=true
        else
            ANSWER=false
        fi
}

# Defaults
INSTALL_KIWIX_IFIXIT=false
INSTALL_KIWIX_WIKIMED=false
INSTALL_KIWIX_KLEXIKON=false
INSTALL_WIKIPEDIA=false
INSTALL_GUTENBERG=false
INSTALL_BL_ANDROIDAPPS=false
INSTALL_BL_KNOWLEDGE=false
INSTALL_OSM_GERMANY=false
ESTIMATED_FILESIZE_MB=0

echo
echo ░█▀█░█▀█░▀█▀░█▀▀░█▀█░█░░░█░░░█▀█░█░█░█▀█░█░█░▀█▀░░░█▀▀░█▀▀░▀█▀░█░█░█▀█
echo ░█░█░█░█░░█░░█▀▀░█▀█░█░░░█░░░█▀▀░█░█░█░█░█▀▄░░█░░░░▀▀█░█▀▀░░█░░█░█░█▀▀
echo ░▀░▀░▀▀▀░░▀░░▀░░░▀░▀░▀▀▀░▀▀▀░▀░░░▀▀▀░▀░▀░▀░▀░░▀░░░░▀▀▀░▀▀▀░░▀░░▀▀▀░▀░░
echo

# (1) KIWIX: iFixit
askYesNo "Kiwix: Wiki:iFixit installieren? (~3 GB)" true
ESTIMATED_FILESIZE_MB=$((ESTIMATED_FILESIZE_MB + 3072))
INSTALL_KIWIX_IFIXIT=$ANSWER

# (2) KIWIX: Wikipedia - Medizin
askYesNo "Kiwix: Medizin-Wikipedia installieren? (~400 MB)" true
ESTIMATED_FILESIZE_MB=$((ESTIMATED_FILESIZE_MB + 400))
INSTALL_KIWIX_WIKIMED=$ANSWER

# (3) KIWIX: Klexikon
askYesNo "Kiwix: Kinder-Lexikon KLEXIKON installieren? (~150 MB)" false
ESTIMATED_FILESIZE_MB=$((ESTIMATED_FILESIZE_MB + 150))
INSTALL_KIWIX_KLEXIKON=$ANSWER

# (4) KIWIX: Wikipedia komplett
askYesNo "Kiwix: Wikipedia deutsch komplett installieren? (~40 GB)" false
ESTIMATED_FILESIZE_MB=$((ESTIMATED_FILESIZE_MB + 40000))
INSTALL_WIKIPEDIA=$ANSWER

# (5) KIWIX: Gutenberg Texte
askYesNo "Kiwix: Projekt Gutenberg Texte installieren? (~3 GB)" false
ESTIMATED_FILESIZE_MB=$((ESTIMATED_FILESIZE_MB + 3072))
INSTALL_GUTENBERG=$ANSWER

# (6) BL: Android Apps
askYesNo "Android-Apps installieren für Download? (~700 MB)" true
ESTIMATED_FILESIZE_MB=$((ESTIMATED_FILESIZE_MB + 700))
INSTALL_BL_ANDROIDAPPS=$ANSWER

# (7) BL: Knowledge
askYesNo "Wissens-Downloads (Erste Hilfe, Notfunk usw.) installieren? (~160 MB)" true
ESTIMATED_FILESIZE_MB=$((ESTIMATED_FILESIZE_MB + 160))
INSTALL_BL_KNOWLEDGE=true

# (8) OpenStreetMap
askYesNo "OpenStreetMap-Kartendaten für Deutschland gesamt installieren? (~1 GB)" true
ESTIMATED_FILESIZE_MB=$((ESTIMATED_FILESIZE_MB + 1024))
INSTALL_OSM_GERMANY=$ANSWER

# Summary
echo
echo Installation bestätigen
echo -----------------------

if [[ $INSTALL_KIWIX_IFIXIT == true ]]
then
    echo [x] KIWIX: iFixit
else
    echo [ ] KIWIX: iFixit
fi

if [[ $INSTALL_KIWIX_WIKIMED == true ]]
then
    echo [x] KIWIX: Wikipedia Medizin
else
    echo [ ] KIWIX: Wikipedia Medizin
fi

if [[ $INSTALL_KIWIX_KLEXIKON == true ]]
then
    echo [x] KIWIX: Kinderlexikon Klexikon
else
    echo [ ] KIWIX: Kinderlexikon Klexikon
fi

if [[ $INSTALL_WIKIPEDIA == true ]]
then
    echo [x] KIWIX: Wikipedia komplett deutsch
else
    echo [ ] KIWIX: Wikipedia komplett deutsch
fi

if [[ $INSTALL_GUTENBERG == true ]]
then
    echo [x] KIWIX: Projekt Gutenberg - deutsche Texte
else
    echo [ ] KIWIX: Projekt Gutenberg - deutsche Texte
fi

if [[ $INSTALL_BL_ANDROIDAPPS == true ]]
then
    echo [x] Android Apps
else
    echo [ ] Android Apps
fi

if [[ $INSTALL_BL_KNOWLEDGE == true ]]
then
    echo [x] Wissensdownloads - Erste Hilfe, Notfunk usw.
else
    echo [ ] Wissensdownloads - Erste Hilfe, Notfunk usw.
fi

if [[ $INSTALL_OSM_GERMANY == true ]]
then
    echo [x] OpenStreetMap Deutschland
else
    echo [ ] OpenStreetMap Deutschland
fi


AVAIL_SPACE=`df -h /opt | grep -vi Filesystem | awk '{ print $4 }'`
EST_SIZE_GB=~$(($ESTIMATED_FILESIZE_MB/1024))

echo
printf 'Geschätzte Downloadgröße: %sGB' "$EST_SIZE_GB"
echo
printf 'Verfügbarer Platz:        %s%s' "$AVAIL_SPACE" "B"
echo

echo
askYesNo "Installation jetzt ausführen?" true
echo
echo "Starte Installation...."

# DOWNLOADS
if [ "$INSTALL_KIWIX_IFIXIT" = true ]; then
    echo Download: Kiwix - iFixit
    wget https://download.kiwix.org/zim/ifixit/ifixit_de_all_2023-07.zim -P fileshare/kiwix/
fi
if [ "$INSTALL_KIWIX_WIKIMED" = true ]; then
    echo Download: Kiwix - Wikipedia Medizin
    wget https://download.kiwix.org/zim/wikipedia/wikipedia_de_medicine_maxi_2023-11.zim -P fileshare/kiwix/
fi
if [ "$INSTALL_KIWIX_KLEXIKON" = true ]; then
    echo Download: Kiwix - Klexikon
    wget https://download.kiwix.org/zim/wikipedia/wikipedia_de_medicine_maxi_2023-11.zim -P fileshare/kiwix/
fi
if [ "$INSTALL_KIWIX_KLEXIKON" = true ]; then
    echo Download: Kiwix - Klexikon
    wget https://download.kiwix.org/zim/other/klexikon_de_all_maxi_2023-11.zim -P fileshare/kiwix/
fi
if [ "$INSTALL_GUTENBERG" = true ]; then
    echo Download: Kiwix - Projekt Gutenberg
    wget https://download.kiwix.org/zim/gutenberg/gutenberg_de_all_2023-11.zim -P fileshare/kiwix/
fi
if [ "$INSTALL_WIKIPEDIA" = true ]; then
    echo Download: Kiwix - Wikipedia deutsch komplett
    wget https://download.kiwix.org/zim/wikipedia/wikipedia_de_all_nopic_2023-11.zim -P fileshare/kiwix/
fi

# TODO: That does not work as expected! This needs to be adopted!
# TODO: We jave to create a single JSON file instead per download - not a large file!
copy conf/fileshare/kiwix.json fileshare/kiwix/.files.json

if [ "$INSTALL_BL_ANDROIDAPPS" = true ]; then
    echo Download: Android Apps
    wget https://blackout.land/nfp/dl_apk.zip -P temp/
    echo Entpacken der Android Apps
    unzip -d fileshare/public/files/apk temp/dl_apk.zip
    rm temp/dl_apk.zip
fi

if [ "$INSTALL_BL_ANDROIDAPPS" = true ]; then
    echo Download: Wissensdateien
    wget https://blackout.land/nfp/dl_knowledge.zip -P temp/
    echo Entpacken des Wissensarchivs
    unzip -d fileshare/public/knowledge temp/dl_knowledge.zip
    rm temp/dl_knowledge.zip
fi

if [ "$INSTALL_OSM_GERMANY" = true ]; then
    echo Download: OpenStreetMap Deutschland
    wget https://download.openstreetmap.fr/extracts/europe/germany-latest.osm.pbf -P fileshare/public/files/osm/
    copy conf/fileshare/osm.json fileshare/public/files/osm/.files.json
fi

echo
echo ░█▀▀░█▀▀░█▀▄░▀█▀░▀█▀░█▀▀
echo ░█▀▀░█▀▀░█▀▄░░█░░░█░░█░█
echo ░▀░░░▀▀▀░▀░▀░░▀░░▀▀▀░▀▀▀
echo
