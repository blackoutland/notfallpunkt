# Notfallpunkt

## Notizen

* `docker network create blackoutland-net`
* `docker-compose up`

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
Use Varnish as cache? https://hub.docker.com/_/varnish

Use Memcache flag!

Wenn memcache aktiv, dann alle Lokalisierungen und EInstellungen aus DB in memcache schreiben und nur von dort lesen!
Und alle Userdaten!
Bei Verwendung von Varnish nur Daten von nicht eingeloggten Usern cachen?

Logout -> delete memcache key for being online