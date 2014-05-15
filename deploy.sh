#!/bin/bash

# Usage: ./deploy.sh login@target-server environnement
echo "=========================================================================="
echo "Usage : ./deploy.sh root@111.222.333.444 GitUsername GitPassword GitBranch"
echo "=========================================================================="
host="${1:-$1}"
bitUser="${2:-$2}"
bitPass="${3:-$3}"
branch="${4:-$4}"

# The host key might change when we instantiate a new VM, so
# we remove (-R) the old host key from known_hosts
ssh-keygen -R "${host#*@}" 2> /dev/null

tar cj ./install/chef/cookbooks ./install.sh ./solo.json ./solo.rb | ssh -o 'StrictHostKeyChecking no' "$host" 'sudo rm -rf ~/chef && mkdir ~/chef && cd ~/chef && tar xj && sudo bash install.sh ' $2 $3 $4