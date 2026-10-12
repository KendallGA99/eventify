param([string]$FunctionsPath, [string]$PublisherPath)
$ErrorActionPreference = 'Stop'
. $FunctionsPath
$script:checks = 0
function Check($Condition, [string]$Message) {
    if (-not $Condition) { throw $Message }
    $script:checks++
}
function Reject([scriptblock]$Action) {
    $rejected = $false
    try { & $Action } catch { $rejected = $true }
    Check $rejected 'Se acepto un caso que debia rechazarse.'
}
$a = 'a' * 40
$b = 'b' * 40
$local = ConvertTo-GitRefMap "$a refs/heads/main`n$b refs/tags/v1"
Check ($local.Count -eq 2) 'Inventario incorrecto.'
Check ((ConvertTo-GitRefMap '').Count -eq 0) 'Un repositorio vacio no se reconoce.'
Reject { ConvertTo-GitRefMap 'salida inesperada' }
Reject { ConvertTo-GitRefMap "$a refs/heads/main`n$a refs/heads/main" }
Assert-GitRemoteSubset $local @{}
Check $true 'Destino vacio.'
Assert-GitRemoteSubset $local @{ 'refs/heads/main' = $a }
Check $true 'Reintento parcial.'
Assert-GitRemoteSubset $local $local -Complete
Check $true 'Destino completo.'
Reject { Assert-GitRemoteSubset $local @{} -Complete }
Reject { Assert-GitRemoteSubset $local @{ 'refs/heads/main' = $b } }
Reject { Assert-GitRemoteSubset $local @{ 'refs/heads/ajena' = $a } }
$repo = [pscustomobject]@{ id = 1; owner = @{login='receptor'}; name='eventify'; private=$true }
Assert-GitHubDestination $repo 'receptor' 'eventify' $null
Check $true 'Destino creado.'
Assert-GitHubDestination $repo 'receptor' 'eventify' ([pscustomobject]@{id=1})
Check $true 'Destino de reintento.'
Reject { Assert-GitHubDestination $repo 'otra' 'eventify' $null }
Reject { Assert-GitHubDestination $repo 'receptor' 'otro' $null }
Reject { Assert-GitHubDestination $repo 'receptor' 'eventify' ([pscustomobject]@{id=2}) }
$repo.private=$false
Reject { Assert-GitHubDestination $repo 'receptor' 'eventify' $null }
# Probar procesos nativos reales: stdout, salida de error y codigo propagado.
$cmd = Join-Path $env:SystemRoot 'System32/cmd.exe'
Check ((Invoke-GitHubTool $cmd @('/d','/c','echo correcto')).Trim() -eq 'correcto') 'stdout perdido.'
Reject { Invoke-GitHubTool $cmd @('/d','/c','exit 7') }
Invoke-GitHubTool $cmd @('/d','/c','echo progreso') -Live
Check $true 'Salida interactiva.'
$tokens=$null; $errors=$null
[Management.Automation.Language.Parser]::ParseFile($PublisherPath,[ref]$tokens,[ref]$errors) | Out-Null
Check ($errors.Count -eq 0) 'Error de sintaxis en el publicador.'
Write-Host "Publicador GitHub: $script:checks controles correctos en PowerShell $($PSVersionTable.PSVersion)."
