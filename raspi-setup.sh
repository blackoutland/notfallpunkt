#!/bin/bash

#  ██████╗  ██████╗  ██████╗██╗  ██╗███████╗██████╗
#  ██╔══██╗██╔═══██╗██╔════╝██║ ██╔╝██╔════╝██╔══██╗
#  ██║  ██║██║   ██║██║     █████╔╝ █████╗  ██████╔╝
#  ██║  ██║██║   ██║██║     ██╔═██╗ ██╔══╝  ██╔══██╗
#  ██████╔╝╚██████╔╝╚██████╗██║  ██╗███████╗██║  ██║
#  ╚═════╝  ╚═════╝  ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝

## From https://docs.docker.com/engine/install/raspberry-pi-os/
echo "Removing previous versions of Docker..."
# TODO: Enable!
for pkg in docker.io docker-doc docker-compose podman-docker containerd runc; do sudo apt-get remove $pkg -y; done

# Add Docker's official GPG key:
echo "Setting up official Docker repository..."
sudo apt-get update
sudo apt-get install ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/raspbian/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo \
  "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/raspbian \
  "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update

# Install Docker
echo "Installing Docker..."
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin


#  ███╗   ██╗ ██████╗ ████████╗███████╗ █████╗ ██╗     ██╗     ██████╗ ██╗   ██╗███╗   ██╗██╗  ██╗████████╗
#  ████╗  ██║██╔═══██╗╚══██╔══╝██╔════╝██╔══██╗██║     ██║     ██╔══██╗██║   ██║████╗  ██║██║ ██╔╝╚══██╔══╝
#  ██╔██╗ ██║██║   ██║   ██║   █████╗  ███████║██║     ██║     ██████╔╝██║   ██║██╔██╗ ██║█████╔╝    ██║
#  ██║╚██╗██║██║   ██║   ██║   ██╔══╝  ██╔══██║██║     ██║     ██╔═══╝ ██║   ██║██║╚██╗██║██╔═██╗    ██║
#  ██║ ╚████║╚██████╔╝   ██║   ██║     ██║  ██║███████╗███████╗██║     ╚██████╔╝██║ ╚████║██║  ██╗   ██║
#  ╚═╝  ╚═══╝ ╚═════╝    ╚═╝   ╚═╝     ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝      ╚═════╝ ╚═╝  ╚═══╝╚═╝  ╚═╝   ╚═╝

echo "Setting permissions..."
chmod -R 744 temp
chmod -R 744 www/htdocs/minified

echo "Starting container..."
docker-compose up --build
