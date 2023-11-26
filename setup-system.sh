#!/bin/bash

#  ██████╗  ██████╗  ██████╗██╗  ██╗███████╗██████╗
#  ██╔══██╗██╔═══██╗██╔════╝██║ ██╔╝██╔════╝██╔══██╗
#  ██║  ██║██║   ██║██║     █████╔╝ █████╗  ██████╔╝
#  ██║  ██║██║   ██║██║     ██╔═██╗ ██╔══╝  ██╔══██╗
#  ██████╔╝╚██████╔╝╚██████╗██║  ██╗███████╗██║  ██║
#  ╚═════╝  ╚═════╝  ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝

# Install Docker
echo "Installiere Docker..."
sudo apt update && apt upgrade -y
sudo apt install docker docker-compose -y && sudo service docker start
sudo groupadd docker
sudo usermod -aG docker $USER

docker-compose down
export DOCKER_CLIENT_TIMEOUT=180
export COMPOSE_HTTP_TIMEOUT=180


#  ███╗   ██╗ ██████╗ ████████╗███████╗ █████╗ ██╗     ██╗     ██████╗ ██╗   ██╗███╗   ██╗██╗  ██╗████████╗
#  ████╗  ██║██╔═══██╗╚══██╔══╝██╔════╝██╔══██╗██║     ██║     ██╔══██╗██║   ██║████╗  ██║██║ ██╔╝╚══██╔══╝
#  ██╔██╗ ██║██║   ██║   ██║   █████╗  ███████║██║     ██║     ██████╔╝██║   ██║██╔██╗ ██║█████╔╝    ██║
#  ██║╚██╗██║██║   ██║   ██║   ██╔══╝  ██╔══██║██║     ██║     ██╔═══╝ ██║   ██║██║╚██╗██║██╔═██╗    ██║
#  ██║ ╚████║╚██████╔╝   ██║   ██║     ██║  ██║███████╗███████╗██║     ╚██████╔╝██║ ╚████║██║  ██╗   ██║
#  ╚═╝  ╚═══╝ ╚═════╝    ╚═╝   ╚═╝     ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝      ╚═════╝ ╚═╝  ╚═══╝╚═╝  ╚═╝   ╚═╝

echo "Setze Berechtigungen..."
chmod -R 777 temp
chmod -R 777 www/htdocs/minified

echo "Bitte ausloggen, dann erneut einloggen und diese Befehle ausführen:"
echo "cd /opt/notfallpunkt"
echo "docker network create notfallpunkt-net"

