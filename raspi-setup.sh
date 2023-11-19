#!/bin/bash

#  ██████╗  ██████╗  ██████╗██╗  ██╗███████╗██████╗
#  ██╔══██╗██╔═══██╗██╔════╝██║ ██╔╝██╔════╝██╔══██╗
#  ██║  ██║██║   ██║██║     █████╔╝ █████╗  ██████╔╝
#  ██║  ██║██║   ██║██║     ██╔═██╗ ██╔══╝  ██╔══██╗
#  ██████╔╝╚██████╔╝╚██████╗██║  ██╗███████╗██║  ██║
#  ╚═════╝  ╚═════╝  ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝

# Install Docker
echo "Installing Docker..."
sudo apt update && apt upgrade -y
sudo apt install docker docker-compose -y && sudo service docker start
sudo groupadd docker
sudo usermod -aG docker $USER

docker-compose down
export DOCKER_CLIENT_TIMEOUT=120
export COMPOSE_HTTP_TIMEOUT=120


#  ███╗   ██╗ ██████╗ ████████╗███████╗ █████╗ ██╗     ██╗     ██████╗ ██╗   ██╗███╗   ██╗██╗  ██╗████████╗
#  ████╗  ██║██╔═══██╗╚══██╔══╝██╔════╝██╔══██╗██║     ██║     ██╔══██╗██║   ██║████╗  ██║██║ ██╔╝╚══██╔══╝
#  ██╔██╗ ██║██║   ██║   ██║   █████╗  ███████║██║     ██║     ██████╔╝██║   ██║██╔██╗ ██║█████╔╝    ██║
#  ██║╚██╗██║██║   ██║   ██║   ██╔══╝  ██╔══██║██║     ██║     ██╔═══╝ ██║   ██║██║╚██╗██║██╔═██╗    ██║
#  ██║ ╚████║╚██████╔╝   ██║   ██║     ██║  ██║███████╗███████╗██║     ╚██████╔╝██║ ╚████║██║  ██╗   ██║
#  ╚═╝  ╚═══╝ ╚═════╝    ╚═╝   ╚═╝     ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝      ╚═════╝ ╚═╝  ╚═══╝╚═╝  ╚═╝   ╚═╝

echo "Setting permissions..."
chmod -R 744 temp
chmod -R 744 www/htdocs/minified

echo "Please logout and login again, then execute these commands:"
echo "cd /opt/notfallpunkt"
echo "docker network create notfallpunkt-net"
echo "docker-compose up --build"


