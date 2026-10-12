$ErrorActionPreference = 'Stop'
$EventifyRoot = Split-Path -Parent $PSScriptRoot
Set-Location -LiteralPath $EventifyRoot

function Assert-EventifyDocker {
    if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
        throw 'Instale Docker Desktop y actívelo en modo de contenedores Linux. Consulte docs/INSTALACION.md.'
    }
    & docker info *> $null
    if ($LASTEXITCODE -ne 0) { throw 'Docker no está disponible. Abra Docker Desktop y espere a que termine de iniciar.' }
    & docker compose version
    if ($LASTEXITCODE -ne 0) { throw 'Falta Docker Compose v2.' }
}

function New-EventifySecret {
    $bytes = New-Object byte[] 32
    $rng = [System.Security.Cryptography.RandomNumberGenerator]::Create()
    try { $rng.GetBytes($bytes) } finally { $rng.Dispose() }
    return ([BitConverter]::ToString($bytes)).Replace('-', '').ToLowerInvariant()
}

function Initialize-EventifyEnv {
    $envPath = Join-Path $EventifyRoot '.env'
    if (-not (Test-Path -LiteralPath $envPath)) {
        $template = [IO.File]::ReadAllText((Join-Path $EventifyRoot '.env.example'))
        $template = $template.Replace('__GENERATE_APP_SECRET__', (New-EventifySecret))
        $template = $template.Replace('__GENERATE_DB_PASSWORD__', (New-EventifySecret))
        $template = $template.Replace('__GENERATE_ROOT_PASSWORD__', (New-EventifySecret))
        [IO.File]::WriteAllText($envPath, $template, (New-Object Text.UTF8Encoding $false))
        Write-Host 'Configuración local creada con claves únicas. No comparta .env.'
    }
    if ([IO.File]::ReadAllText($envPath).Contains('__GENERATE_')) {
        throw '.env contiene claves de ejemplo. Configure claves reales; su archivo no se sobrescribirá.'
    }
}

function Invoke-EventifyCompose {
    # La salida simple funciona también cuando el asistente redirige la consola.
    & docker compose --ansi never --progress plain @args
    if ($LASTEXITCODE -ne 0) { throw 'Docker Compose no pudo completar la operación. Revise el mensaje anterior.' }
}
