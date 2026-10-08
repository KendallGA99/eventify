. "$PSScriptRoot/common-windows.ps1"
try {
    Assert-EventifyDocker
    Invoke-EventifyCompose exec -T web php bin/seed-demo.php
} catch {
    Write-Error $_ -ErrorAction Continue
    exit 1
}
