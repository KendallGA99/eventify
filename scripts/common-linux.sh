#!/usr/bin/env bash
set -euo pipefail

EVENTIFY_ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
cd -- "$EVENTIFY_ROOT"
EVENTIFY_DOCKER=(docker)

require_docker() {
    if ! command -v docker >/dev/null 2>&1; then
        echo 'Falta Docker. En Linux Mint/Ubuntu: bash scripts/install-docker-linux.sh' >&2
        return 1
    fi
    if ! docker info >/dev/null 2>&1; then
        echo 'Docker requiere acceso al servicio. Se comprobará con sudo en esta terminal.' >&2
        if ! sudo docker info >/dev/null; then
            echo 'No se pudo acceder a Docker. Revise que el servicio esté iniciado.' >&2
            return 1
        fi
        EVENTIFY_DOCKER=(sudo docker)
    fi
    "${EVENTIFY_DOCKER[@]}" compose version >/dev/null
}

prepare_env() {
    if [[ ! -f .env ]]; then
        command -v openssl >/dev/null || { echo 'Falta openssl para generar claves locales.' >&2; return 1; }
        umask 077
        local template
        template="$(cat .env.example)"
        template="${template/__GENERATE_APP_SECRET__/$(openssl rand -hex 32)}"
        template="${template/__GENERATE_DB_PASSWORD__/$(openssl rand -hex 32)}"
        template="${template/__GENERATE_ROOT_PASSWORD__/$(openssl rand -hex 32)}"
        printf '%s\n' "$template" > .env
        echo 'Configuración local creada con claves únicas. No comparta .env.'
    fi
    if grep -q '__GENERATE_' .env; then
        echo '.env contiene claves de ejemplo. Configure claves reales antes de iniciar; no se sobrescribirá su archivo.' >&2
        return 1
    fi
}
