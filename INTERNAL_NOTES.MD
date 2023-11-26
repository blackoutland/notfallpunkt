# Notfallpunkt


## Zu testen (aus Notfunkwiki)

Ganz wichtig: Wir schalten den Stromspar-Modus des WiFi-Dongles aus, bevor wir das vergessen ;-)
Sonst muss man als Benutzer alle Nase lang die WiFi-/WLAN-Verbindung neu starten und sich neu anmelden.

`sudo iw dev wlan0 set power_save off`
`sudo nano /boot/config.txt` - Am ENDE hinzufügen:

```
### Internen WLAN-Chip abschalten (Soweit vorhanden)
dtoverlay=disable-wifi
### Bluetooth abschalten
dtoverlay=disable-bt
```

## Sonstige Notizen


TODO:

- nginx: Cache-Header für statische Inhalte!  /minified usw.
- add caching (static page unless changed!)

* Runterfahren über Dashboard / + Dateisystem als RW? in RAM kopieren?
* GPIO-Port zur Scharfschaltung (Abschaltung Testbetrieb)

Bootstrap 5.3.2
Bootstrap Themes: https://bootswatch.com/

Test caching w/ query params!
Don't cache login pages, user profiles etc.

- Nachrichten an andere Benutzer senden
- Anzeigen, welche User online sind (memcache + Cron)

self-signed Zertifikat erstellen für HTTPS, da WLAN nicht verschlüsselt

Do not expose ports not to be exposed (memcached etc.)

Wenn memcache aktiv, dann alle Lokalisierungen und EIistellungen aus DB in memcache schreiben und nur von dort lesen!
Und alle Userdaten!

Logout -> delete memcache key for being online


## APK - Android Downloads
* Quelle: https://apkpure.com/de/
* `tools/scrapeAplPureData.php` kann z.B. im PHP-Container direkt ausgeführt werden und extrahiert JSON-Daten aus der zuvor
gespeicherten HTML-App-Ansicht von APKPure - diese können dann in der `.files`-Datei eingetragen werden! Die HTML-Seiten der
jeweiligen App ist im `tools/dl`-Verzeichnis zu speichern, bevor das PHP-Script ausgeführt wird!

## OSM-Daten
* Downloadquellen:
 * https://download.bbbike.org/osm/planet/sub-planet-daily/
 * https://download.openstreetmap.fr/extracts/europe/
 * ... - siehe https://wiki.openstreetmap.org/wiki/Planet.osm#Downloading
* Speichern in `public/files/osm`

ADD SAMBA WITH RANDOMLY CREATED PASSWORD!

Show loader whenever user clicks on any navigation link!
