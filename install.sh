#!/bin/bash

# This runs as root on the server

bitUser="${1:-$1}"
bitPass="${2:-$2}"
branch="${3:-3}"

apt-get -y install git

if [ -d "/var/www" ]
then
    cd /var
    rm -R www
    mkdir www
else
    cd /var
    mkdir www
fi
if [ -d "/var/www/spasm" ]
then
    cd /var/www
    rm -R spasm
    mkdir spasm
    echo "Spasm directory deleted and re-created"
else
    cd /var/www
    mkdir spasm
    echo "Spasm directory created"
fi

cd /var/www/spasm
git clone https://$bitUser:$bitPass@github.com/imie-source/DL09_G3.git .
git checkout $branch
cd app/cache
chmod 777 acl/data.txt
cd ~/chef

chef_binary=/usr/local/bin/chef-solo

# Are we on a vanilla system?
if ! test -f "$chef_binary"; then
    export DEBIAN_FRONTEND=noninteractive
    # Upgrade headlessly (this is only safe-ish on vanilla systems)
    aptitude update && aptitude full-upgrade -y &&
    apt-get update && apt-get upgrade -y &&
    apt-get -o Dpkg::Options::="--force-confnew" --force-yes -fuy dist-upgrade &&
    # Install Ruby and Chef
    aptitude install -y ruby1.9.1 ruby1.9.1-dev make &&
    sudo gem1.9.1 install --no-rdoc --no-ri chef --version 10.32.2
fi &&

"$chef_binary" -c solo.rb -j solo.json