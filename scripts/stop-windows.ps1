. "$PSScriptRoot/common-windows.ps1"
try {
    Assert-EventifyDocker
    Invoke-EventifyCompose stop
    Write-Host 'Eventify detenido. Los datos se conservan.'
} catch {
    Write-Error $_ -ErrorAction Continue
    exit 1
}
