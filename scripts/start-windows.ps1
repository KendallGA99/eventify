. "$PSScriptRoot/common-windows.ps1"
try {
    Assert-EventifyDocker
    Initialize-EventifyEnv
    Invoke-EventifyCompose config --quiet
    Invoke-EventifyCompose up --build --detach --wait --wait-timeout 180
    Invoke-EventifyCompose exec -T web php bin/migrate.php
    Invoke-EventifyCompose exec -T web php bin/prepare-examples.php
    & docker compose exec -T web test -f storage/example-credentials.json
    if ($LASTEXITCODE -eq 0) {
        Invoke-EventifyCompose cp web:/var/www/html/storage/example-credentials.json .\storage\example-credentials.json
        Write-Host "Acceso local: abra storage\example-credentials.json en privado."
    }
    $portLine = Get-Content -LiteralPath '.env' | Where-Object { $_ -match '^APP_PORT=[0-9]+$' } | Select-Object -First 1
    if (-not $portLine) { throw 'Compruebe APP_PORT en .env.' }
    $port = $portLine.Substring('APP_PORT='.Length)
    Write-Host "Eventify disponible en http://localhost:$port"
    exit 0
} catch {
    Write-Error $_ -ErrorAction Continue
    exit 1
}
