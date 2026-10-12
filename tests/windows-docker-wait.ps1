param([string]$FunctionsPath = "$PSScriptRoot\..\scripts\windows-setup-functions.ps1")
$ErrorActionPreference = 'Stop'
. $FunctionsPath
$script:checks = 0
$script:probes = 0
$script:launches = 0
$script:messages = New-Object 'Collections.Generic.List[string]'
$script:progress = New-Object 'Collections.Generic.List[object]'
function Assert-Wait($Condition, $Message) {
    if (-not $Condition) { throw "Fallo: $Message" }
    $script:checks++
}
function Test-EventifyDockerReady {
    $script:probes++
    return ($script:readyAfter -gt 0 -and $script:probes -ge $script:readyAfter)
}
function Start-Process { $script:launches++ }
function Write-Host { param([string]$Object) $script:messages.Add($Object) }
function Write-Progress {
    param($Id, $Activity, $Status, $PercentComplete, [switch]$Completed)
    $script:progress.Add(@{ Completed = [bool]$Completed; Percent = $PercentComplete })
}
$script:readyAfter = 1
Wait-EventifyDocker -DesktopPath $null
Assert-Wait ($script:launches -eq 0) 'Docker listo no se abre de nuevo'
$script:probes = 0
$script:readyAfter = 3
Wait-EventifyDocker -DesktopPath 'Docker Desktop.exe' -TimeoutSeconds 5 -PollSeconds 1
Assert-Wait ($script:launches -eq 1 -and $script:probes -eq 3) 'Continua cuando Docker responde despues de esperar'
Assert-Wait (($script:messages | Where-Object { $_ -match 'Tiempo transcurrido:' }).Count -ge 1) 'Espera informa actividad y tiempo'
Assert-Wait ($script:progress[-1].Completed) 'Indicador se cierra al quedar listo'
Assert-Wait (($script:progress | Where-Object { -not $_.Completed -and $_.Percent -ne -1 }).Count -eq 0) 'Arranque no inventa porcentaje'
$script:readyAfter = 0
$script:probes = 0
$failed = $false
try { Wait-EventifyDocker -DesktopPath 'Docker Desktop.exe' -TimeoutSeconds 1 -PollSeconds 1 }
catch { $failed = $_.Exception.Message -match 'No necesita reinstalar ni borrar datos' }
Assert-Wait $failed 'Timeout explica como retomar sin borrar datos'
Assert-Wait ($script:progress[-1].Completed) 'Indicador tambien se cierra al agotar espera'
$failed = $false
try { Wait-EventifyDocker -DesktopPath $null -TimeoutSeconds 1 } catch { $failed = $true }
Assert-Wait $failed 'Motor detenido sin Desktop devuelve error'
[Console]::WriteLine("Espera Docker: $script:checks comprobaciones correctas. Disponibilidad simulada, reloj real.")
