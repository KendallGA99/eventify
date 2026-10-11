param(
    [string]$FunctionsPath = "$PSScriptRoot\..\scripts\windows-setup-functions.ps1",
    [string]$CmdDirectory = "$PSScriptRoot\.."
)
$ErrorActionPreference = 'Stop'
. $FunctionsPath
$realShortcuts = ${function:New-EventifyDesktopShortcuts}
$realWait = ${function:Wait-EventifyDocker}
$realInstall = ${function:Install-EventifyDockerDesktop}
$realWsl = ${function:Test-EventifyWslInstalled}
$realReady = ${function:Test-EventifyDockerReady}
$realHost = ${function:Assert-EventifyWindowsHost}
$testRoot = Join-Path ([IO.Path]::GetTempPath()) ("Eventify ensayo ' " + [Guid]::NewGuid().ToString('N'))
$script:checks = 0
function Assert-Setup($Condition, $Message) {
    if (-not $Condition) { throw "Fallo: $Message" }
    $script:checks++
}
try {
    New-Item -ItemType Directory -Path (Join-Path $testRoot 'scripts') -Force | Out-Null
    New-Item -ItemType Directory -Path (Join-Path $testRoot 'desktop') | Out-Null
    & $realShortcuts -Root $testRoot -Desktop (Join-Path $testRoot 'desktop')
    $shell = New-Object -ComObject WScript.Shell
    foreach ($name in @('Iniciar', 'Detener')) {
        $link = $shell.CreateShortcut((Join-Path $testRoot "desktop\$name Eventify.lnk"))
        Assert-Setup ($link.TargetPath -eq $env:ComSpec) 'Acceso usa CMD'
        Assert-Setup ($link.WorkingDirectory -eq $testRoot) 'Ruta con espacios y apostrofo'
        Assert-Setup ($link.Arguments -eq ('/c ""' + (Join-Path $testRoot ($name.ToLowerInvariant() + '-windows.cmd')) + '""')) 'Comillas del acceso'
    }
    & $realShortcuts -Root $testRoot -Desktop (Join-Path $testRoot 'desktop') -AllowLowMemory
    $validationLink = $shell.CreateShortcut((Join-Path $testRoot 'desktop\Iniciar Eventify.lnk'))
    Assert-Setup ($validationLink.Arguments.Contains('validar-windows.cmd')) 'Acceso conserva modo de validacion'
    function Get-CimInstance {
        param($ClassName)
        switch ($ClassName) {
            'Win32_OperatingSystem' { return @{BuildNumber=26100} }
            'Win32_Processor' { return @{Architecture=9;VirtualizationFirmwareEnabled=$true} }
            'Win32_ComputerSystem' { return @{TotalPhysicalMemory=4GB;HypervisorPresent=$true} }
        }
    }
    $failed=$false
    try { & $realHost } catch { $failed=$true }
    Assert-Setup $failed 'Modo normal rechaza host simulado de 4 GB'
    & $realHost -AllowLowMemory
    Assert-Setup $true 'Modo de ensayo acepta host simulado de 4 GB'
    Remove-Item Function:\Get-CimInstance
    foreach ($layout in @('directo','entrega')) {
        $base = Join-Path $testRoot ('CMD ' + $layout)
        $project = $base
        if ($layout -eq 'entrega') { $project = Join-Path $base 'Eventify' }
        New-Item -ItemType Directory -Path (Join-Path $project 'scripts') -Force | Out-Null
        Copy-Item -LiteralPath (Join-Path $CmdDirectory 'INSTALAR-EVENTIFY.cmd') -Destination $base
        Copy-Item -LiteralPath (Join-Path $CmdDirectory 'instalar-windows.cmd') -Destination $project
        Set-Content -LiteralPath (Join-Path $project 'scripts\setup-windows.ps1') -Encoding ASCII -Value @'
[IO.File]::WriteAllText((Join-Path $PSScriptRoot 'llamado.txt'),'CMD correcto')
exit 37
'@
        $launcher = Join-Path $base 'INSTALAR-EVENTIFY.cmd'
        & $env:ComSpec /d /c ('call "' + $launcher + '" < NUL') | Out-Host
        Assert-Setup ($LASTEXITCODE -eq 37) 'CMD propaga codigo del asistente'
        Assert-Setup (Test-Path -LiteralPath (Join-Path $project 'scripts\llamado.txt')) 'CMD encuentra proyecto en ambos formatos'
    }
    Set-Content -LiteralPath (Join-Path $testRoot '.env') -Value 'APP_PORT=8099' -Encoding ASCII
    Set-Content -LiteralPath (Join-Path $testRoot 'scripts\start-windows.ps1') -Encoding ASCII -Value @'
$global:EventifySetupTestContext.Calls.Add('start')
Write-Output 'Salida de Compose: no debe mezclarse con el codigo de retorno.'
if ($global:EventifySetupTestContext.Scenario -eq 'app-fails') { $global:LASTEXITCODE = 1 } else { $global:LASTEXITCODE = 0 }
'@
    function Assert-EventifyWindowsHost {
        param([switch]$AllowLowMemory)
        $script:lastMemoryFlag = [bool]$AllowLowMemory
        $script:calls.Add('host')
        if ($script:scenario -eq 'bad-host') { throw 'Equipo incompatible' }
    }
    function Update-EventifyWindowsPath { $script:calls.Add('path') }
    function Test-EventifyDockerReady { return ($script:scenario -eq 'ready') }
    function Find-EventifyDockerDesktop {
        $script:calls.Add('find')
        if ($script:scenario -eq 'asleep' -or $script:installed) { return 'Docker Desktop.exe' }
        return $null
    }
    function Test-EventifyWslInstalled {
        $script:calls.Add('wsl')
        return ($script:scenario -ne 'no-wsl')
    }
    function Initialize-EventifyWsl { $script:calls.Add('prepare-wsl'); return 3010 }
    function Install-EventifyDockerDesktop {
        $script:calls.Add('install')
        if ($script:scenario -eq 'install-fails') { throw 'Instalacion rechazada' }
        if ($script:scenario -eq 'reboot') { return 3010 }
        $script:installed = $true
        return 0
    }
    function Wait-EventifyDocker {
        $script:calls.Add('wait')
        if ($script:scenario -eq 'timeout') { throw 'Docker no responde' }
    }
    function New-EventifyDesktopShortcuts {
        $script:calls.Add('shortcuts')
        if ($script:scenario -eq 'no-shortcuts') { throw 'Escritorio protegido' }
    }
    function Start-Process { $script:calls.Add('open') }
    foreach ($case in @('ready','asleep','install','no-wsl','reboot','bad-host','install-fails','timeout','app-fails','no-shortcuts')) {
        $script:scenario = $case
        $script:installed = $false
        $script:calls = New-Object 'Collections.Generic.List[string]'
        $global:EventifySetupTestContext = @{ Calls = $script:calls; Scenario = $case }
        $failed = $false
        $code = $null
        try { $code = Invoke-EventifyWindowsSetup -Root $testRoot -SetupScript 'C:\Proyecto con espacios\setup-windows.ps1' }
        catch { $failed = $true; Write-Host ('Caso ' + $case + ': ' + $_.Exception.Message) }
        if ($case -in @('bad-host','install-fails','timeout','app-fails')) {
            Assert-Setup $failed "$case impide continuar"
            Assert-Setup (-not $script:calls.Contains('open')) "$case no abre una pagina sin iniciar"
        } elseif ($case -in @('no-wsl','reboot')) {
            Assert-Setup ($code -eq 3010) "$case solicita reinicio"
            Assert-Setup (-not $script:calls.Contains('start')) "$case no inicia antes del reinicio"
        } else {
            Assert-Setup (-not $failed -and $code -is [int] -and $code -eq 0) "$case retorna cero sin salida mezclada"
            Assert-Setup ($script:calls.Contains('start') -and $script:calls.Contains('open')) "$case inicia y abre"
        }
        if ($case -in @('ready','asleep')) {
            Assert-Setup (-not $script:calls.Contains('install') -and -not $script:calls.Contains('wsl')) 'Docker existente conservado'
        }
        if ($case -eq 'bad-host') { Assert-Setup ($script:calls.Count -eq 1) 'Sin instalaciones en host incompatible' }
    }
    $script:scenario = 'ready'
    $script:installed = $false
    $script:calls.Clear()
    $global:EventifySetupTestContext = @{ Calls = $script:calls; Scenario = 'ready' }
    $code = Invoke-EventifyWindowsSetup -Root $testRoot -SetupScript 'setup.ps1' -AllowLowMemory
    Assert-Setup ($code -eq 0 -and $script:lastMemoryFlag) 'Modo RAM se transmite al control del host'
    $script:calls.Clear()
    & $realWait -DesktopPath $null -TimeoutSeconds 0
    Assert-Setup ($script:calls.Count -eq 0) 'Motor disponible no vuelve a abrir Docker'
    $script:scenario = 'timeout'
    $failed = $false
    try { & $realWait -DesktopPath 'Docker Desktop.exe' -TimeoutSeconds 0 } catch { $failed = $true }
    Assert-Setup $failed 'Espera agotada devuelve error'
    function Get-Command {
        [CmdletBinding()]
        param([string]$Name)
        if ($Name -eq 'winget.exe' -and $script:hasWinget) { return $true }
        if ($Name -eq 'wsl.exe') { return $true }
        if ($Name -eq 'docker') { return $true }
        return $null
    }
    function winget.exe {
        Write-Output 'Descarga simulada: progreso en pantalla'
        $global:LASTEXITCODE = $script:installerExit
    }
    $script:hasWinget = $true
    foreach ($value in @(0,3010,1641,42)) {
        $script:installerExit = $value
        $failed = $false
        $code = $null
        try { $code = & $realInstall } catch { $failed = $true }
        if ($value -eq 42) { Assert-Setup $failed 'WinGet fallido no se considera instalado' }
        else {
            $expected = 0
            if ($value -ne 0) { $expected = 3010 }
            Assert-Setup ($code -is [int] -and $code -eq $expected) 'Salida WinGet no contamina retorno'
        }
    }
    $script:hasWinget = $false
    function Invoke-WebRequest {
        param($Uri,$OutFile,[switch]$UseBasicParsing)
        Assert-Setup ($Uri -eq 'https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe') 'Descarga desde Docker oficial'
        [IO.File]::WriteAllText($OutFile,'Instalador ficticio, no ejecutable')
        $script:downloadPath = $OutFile
    }
    function Get-AuthenticodeSignature {
        param($LiteralPath)
        return @{Status=$script:signatureStatus;SignerCertificate=@{Subject=$script:signer}}
    }
    function Start-Process {
        param($FilePath,$Verb,[switch]$Wait,[switch]$PassThru,$ArgumentList)
        $script:launches++
        return @{ExitCode=0}
    }
    foreach ($case in @('valid','bad-signature','wrong-publisher')) {
        $script:signatureStatus = 'Valid'
        $script:signer = 'CN=Docker Inc, O=Docker Inc, L=Palo Alto, C=US'
        if ($case -eq 'bad-signature') { $script:signatureStatus = 'HashMismatch' }
        if ($case -eq 'wrong-publisher') { $script:signer = 'CN=Another Docker Tool, O=Another Publisher, C=US' }
        $script:launches = 0
        $failed = $false
        try { $code = & $realInstall } catch { $failed = $true }
        if ($case -eq 'valid') { Assert-Setup (-not $failed -and $code -eq 0 -and $script:launches -eq 1) 'Firma correcta permite instalador' }
        else { Assert-Setup ($failed -and $script:launches -eq 0) 'Firma o editor incorrecto impide ejecucion' }
        Assert-Setup (-not (Test-Path -LiteralPath (Split-Path -Parent $script:downloadPath))) 'Descarga temporal retirada'
    }
    function wsl.exe {
        $global:LASTEXITCODE = $script:wslExit
        Write-Output $script:wslVersion
    }
    foreach ($case in @(
        @{Text='Version WSL: 2.1.5.0';Code=0;Ready=$true},
        @{Text='WSL version: 2.6.3.0';Code=0;Ready=$true},
        @{Text='Version WSL: 2.0.0.0';Code=0;Ready=$false},
        @{Text='WSL no instalado';Code=1;Ready=$false},
        @{Text='Version desconocida';Code=0;Ready=$false}
    )) {
        $script:wslExit=$case.Code
        $script:wslVersion=$case.Text
        Assert-Setup ((& $realWsl) -eq $case.Ready) 'Version WSL minima antes de instalar Docker'
    }
    function docker {
        if ($script:dockerCase -eq 'stderr') { Write-Error 'Motor detenido' }
        $global:LASTEXITCODE = 0
        if ($script:dockerCase -eq 'offline') { $global:LASTEXITCODE = 1 }
        if ($script:dockerCase -eq 'windows') { Write-Output 'windows' } else { Write-Output 'linux' }
    }
    foreach ($case in @('linux','offline','stderr','windows')) {
        $script:dockerCase=$case
        $failed=$false
        $ready=$false
        try { $ready = & $realReady } catch { $failed=$true }
        if ($case -eq 'windows') { Assert-Setup $failed 'Modo Windows no cambia backend automaticamente' }
        else { Assert-Setup (-not $failed -and $ready -eq ($case -eq 'linux')) 'Docker apagado no interrumpe deteccion' }
    }
    Write-Host "Instalador Windows: $script:checks comprobaciones correctas. Operaciones de sistema simuladas; accesos creados solo en carpeta temporal."
} finally {
    Remove-Variable -Name EventifySetupTestContext -Scope Global -ErrorAction SilentlyContinue
    if (Test-Path -LiteralPath $testRoot) { Remove-Item -LiteralPath $testRoot -Recurse -Force }
}
