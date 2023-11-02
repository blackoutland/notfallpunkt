# Notfallpunkt


## Notizen
* `docker network create blackoutland-net`
* `docker-compose up`

  volumes:
  - ./proxy-cert:/certs
  - ./nginx-proxy.conf:/etc/nginx/conf.d/default.conf
  - ./proxy-docroot:/usr/share/nginx/html
  networks:
  - mywebiq-net
  environment:
  - NGINX_HOST=localhost
  - NGINX_PORT=80