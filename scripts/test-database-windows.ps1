. "$PSScriptRoot/common-windows.ps1"
$result = 0
try {
    Assert-EventifyDocker
    Initialize-EventifyEnv
    Invoke-EventifyCompose -f docker-compose.test.yml up --build --abort-on-container-exit --exit-code-from tests
} catch {
    Write-Error $_ -ErrorAction Continue
    $result = 1
} finally {
    & docker compose -f docker-compose.test.yml down
    if ($LASTEXITCODE -ne 0) { $result = 1 }
}
exit $result
