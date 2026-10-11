param([switch]$PrepareWsl, [switch]$AllowLowMemory)
$ErrorActionPreference = 'Stop'
try {
    if ($PrepareWsl) {
        $identity = [Security.Principal.WindowsIdentity]::GetCurrent()
        $principal = New-Object Security.Principal.WindowsPrincipal $identity
        if (-not $principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
            throw 'WSL requiere permiso de administrador.'
        }
        & wsl.exe --install --no-distribution
        if ($LASTEXITCODE -ne 0 -and $LASTEXITCODE -ne 3010) {
            throw 'WSL no se pudo instalar. Revise Windows Update y la virtualizacion.'
        }
        & wsl.exe --update
        if ($LASTEXITCODE -ne 0 -and $LASTEXITCODE -ne 3010) {
            throw 'WSL no se pudo actualizar. Revise su conexion y Windows Update.'
        }
        exit 3010
    }
    . "$PSScriptRoot/windows-setup-functions.ps1"
    $root = Split-Path -Parent $PSScriptRoot
    Set-Location -LiteralPath $root
    $code = Invoke-EventifyWindowsSetup -Root $root -SetupScript $PSCommandPath -AllowLowMemory:$AllowLowMemory
    exit $code
} catch {
    Write-Error $_ -ErrorAction Continue
    exit 1
}
