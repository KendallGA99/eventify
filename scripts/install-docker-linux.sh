#!/usr/bin/env bash
set -euo pipefail

# Instalación local por el usuario; no recibe ni almacena su contraseña.
source /etc/os-release
if [[ "${ID:-}" != 'linuxmint' && "${ID:-}" != 'ubuntu' ]]; then
    echo 'Este script se prepara para Linux Mint basado en Ubuntu y Ubuntu. Consulte el manual para otras distribuciones.' >&2
    exit 1
fi
if command -v docker >/dev/null 2>&1; then
    echo 'Docker ya existe. No se reemplazará ni se eliminarán paquetes/datos.'
    docker compose version
    exit
fi
echo 'Se instalarán docker.io y docker-compose-v2 desde los repositorios de su distribución.'
echo 'sudo solicitará la contraseña únicamente en su terminal. No agregaremos usuarios al grupo docker.'
sudo apt-get update
sudo apt-get install -y docker.io docker-compose-v2
sudo systemctl enable --now docker
sudo docker info >/dev/null
sudo docker compose version
echo 'Docker instalado. Inicie el proyecto con bash scripts/start-linux.sh'
