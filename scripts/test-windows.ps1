. "$PSScriptRoot/common-windows.ps1"
try {
    Assert-EventifyDocker
    Initialize-EventifyEnv
    Invoke-EventifyCompose run --build --rm --no-deps web php bin/lint.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/config.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/database-config.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/reservation-policy.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/registration.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/reservation-input.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/catalog-filter.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/event-weather.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/admin-input.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/admin-read.php
    Invoke-EventifyCompose run --rm --no-deps web php tests/unit/ticket-qr.php
    Invoke-EventifyCompose exec -T web php bin/health.php
    Write-Host 'Sintaxis, configuración y conexión MySQL verificadas.'
} catch {
    Write-Error $_ -ErrorAction Continue
    exit 1
}
