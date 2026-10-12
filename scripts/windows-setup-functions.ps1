$ErrorActionPreference = 'Stop'

function Update-EventifyWindowsPath {
    $env:Path = [Environment]::GetEnvironmentVariable('Path', 'Machine') + ';' +
        [Environment]::GetEnvironmentVariable('Path', 'User') + ';' +
        "$env:ProgramFiles\Docker\Docker\resources\bin;" +
        "$env:LOCALAPPDATA\Programs\DockerDesktop\resources\bin"
}

function Assert-EventifyWindowsHost {
    param([switch]$AllowLowMemory)
    if ([Environment]::OSVersion.Platform -ne [PlatformID]::Win32NT) {
        throw 'Este instalador es para Windows.'
    }
    $os = Get-CimInstance Win32_OperatingSystem
    $cpu = Get-CimInstance Win32_Processor | Select-Object -First 1
    $computer = Get-CimInstance Win32_ComputerSystem
    if (-not [Environment]::Is64BitOperatingSystem -or $cpu.Architecture -ne 9) {
        throw 'Este paquete prepara Docker para Windows x64 (Intel/AMD). ARM requiere instalacion especifica.'
    }
    if ([int]$os.BuildNumber -lt 19045) {
        throw 'Windows necesita actualizarse. Revise los requisitos oficiales de Docker Desktop.'
    }
    if ([long]$computer.TotalPhysicalMemory -lt (8GB - 256MB)) {
        Write-Warning 'Este equipo tiene menos de 8 GB de RAM. Eventify continuara para validar la instalacion; Docker puede necesitar mas recursos.'
    }
    if (-not $cpu.VirtualizationFirmwareEnabled -and -not $computer.HypervisorPresent) {
        throw 'Active la virtualizacion en BIOS/UEFI antes de instalar Docker.'
    }
}

function Test-EventifyDockerReady {
    if (-not (Get-Command docker -ErrorAction SilentlyContinue)) { return $false }
    try {
        $kind = & docker info --format '{{.OSType}}' 2>$null
        if ($LASTEXITCODE -ne 0) { return $false }
    } catch { return $false }
    if (($kind | Out-String).Trim() -ne 'linux') {
        throw 'Docker esta en modo Windows. Seleccione Linux containers en Docker Desktop y vuelva a iniciar.'
    }
    return $true
}

function Find-EventifyDockerDesktop {
    foreach ($path in @(
        "$env:ProgramFiles\Docker\Docker\Docker Desktop.exe",
        "$env:LOCALAPPDATA\Programs\DockerDesktop\Docker Desktop.exe"
    )) {
        if (Test-Path -LiteralPath $path) { return $path }
    }
    return $null
}

function Test-EventifyWslInstalled {
    if (-not (Get-Command wsl.exe -ErrorAction SilentlyContinue)) { return $false }
    try {
        $details = & wsl.exe --version 2>$null
        if ($LASTEXITCODE -ne 0) { return $false }
    } catch { return $false }
    $match = [regex]::Match((($details | Out-String) -replace "`0", ''), '\d+\.\d+\.\d+')
    return ($match.Success -and [version]$match.Value -ge [version]'2.1.5')
}

function Initialize-EventifyWsl {
    param([string]$SetupScript)
    Write-Host 'Preparando WSL. Windows solicitara permiso de administrador.'
    $process = Start-Process powershell.exe -Verb RunAs -Wait -PassThru -ArgumentList (
        '-NoProfile -ExecutionPolicy Bypass -File "' + $SetupScript + '" -PrepareWsl'
    )
    if ($process.ExitCode -ne 0 -and $process.ExitCode -ne 3010) {
        throw 'WSL no se pudo preparar. No se modificaron los datos del proyecto.'
    }
    # La activacion de caracteristicas puede requerir reinicio aun si WSL devuelve cero.
    Write-Host 'Reinicie Windows y vuelva a abrir instalar-windows.cmd. No se reinicia automaticamente.'
    return 3010
}

function Get-EventifyDownloadStatus {
    param([long]$Received, [long]$Total, [double]$ElapsedSeconds)
    $mb = $Received / 1MB
    $elapsed = [TimeSpan]::FromSeconds([Math]::Max(0, $ElapsedSeconds)).ToString('hh\:mm\:ss')
    if ($Total -gt 0) {
        $percent = [Math]::Min(100, [Math]::Floor(100.0 * $Received / $Total))
        return ('Descarga Docker: {0}% | {1:N1} / {2:N1} MB | {3}' -f $percent, $mb, ($Total / 1MB), $elapsed)
    }
    return ('Descarga Docker: {0:N1} MB | {1} | tamano total no informado' -f $mb, $elapsed)
}

function Receive-EventifyDockerInstaller {
    param([uri]$Uri, [string]$OutFile)
    if ($Uri.Scheme -ne 'https' -and -not ($Uri.Scheme -eq 'http' -and $Uri.IsLoopback)) {
        throw 'La descarga requiere HTTPS.'
    }
    $response = $null
    $inputStream = $null
    $outputStream = $null
    try {
        $request = [Net.HttpWebRequest]::Create($Uri)
        $request.Timeout = 30000
        $request.ReadWriteTimeout = 60000
        $request.UserAgent = 'Eventify-Windows-Installer'
        $response = $request.GetResponse()
        $total = $response.ContentLength
        $inputStream = $response.GetResponseStream()
        $outputStream = [IO.File]::Create($OutFile)
        $buffer = New-Object byte[] 65536
        $received = [long]0
        $watch = [Diagnostics.Stopwatch]::StartNew()
        $nextUpdate = 0.0
        while (($read = $inputStream.Read($buffer, 0, $buffer.Length)) -gt 0) {
            $outputStream.Write($buffer, 0, $read)
            $received += $read
            if ($watch.Elapsed.TotalSeconds -ge $nextUpdate) {
                Write-Host -NoNewline ("`r" + (Get-EventifyDownloadStatus $received $total $watch.Elapsed.TotalSeconds) + '     ')
                $nextUpdate = $watch.Elapsed.TotalSeconds + 1
            }
        }
        if ($total -ge 0 -and $received -ne $total) { throw 'La descarga quedo incompleta. Vuelva a ejecutar el instalador.' }
        Write-Host ("`r" + (Get-EventifyDownloadStatus $received $total $watch.Elapsed.TotalSeconds) + '     ')
        Write-Host 'Descarga terminada. Verificando la firma del instalador...'
    } finally {
        if ($outputStream) { $outputStream.Dispose() }
        if ($inputStream) { $inputStream.Dispose() }
        if ($response) { $response.Dispose() }
    }
}

function Install-EventifyDockerDesktop {
    if (Get-Command winget.exe -ErrorAction SilentlyContinue) {
        Write-Host 'Instalando Docker Desktop desde el catalogo oficial de WinGet...'
        & winget.exe install --id Docker.DockerDesktop --exact --source winget --accept-source-agreements --accept-package-agreements | Out-Host
        $code = $LASTEXITCODE
        if ($code -eq 3010 -or $code -eq 1641) { return 3010 }
        if ($code -ne 0) { throw "WinGet no pudo instalar Docker Desktop (codigo $code)." }
        return 0
    }

    Write-Host 'WinGet no esta disponible. Descargando el instalador oficial de Docker...'
    $folder = Join-Path ([IO.Path]::GetTempPath()) ('eventify-docker-' + [Guid]::NewGuid().ToString('N'))
    New-Item -ItemType Directory -Path $folder | Out-Null
    $installer = Join-Path $folder 'Docker Desktop Installer.exe'
    try {
        [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
        Receive-EventifyDockerInstaller -Uri 'https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe' -OutFile $installer
        $signature = Get-AuthenticodeSignature -LiteralPath $installer
        if ($signature.Status -ne 'Valid' -or $signature.SignerCertificate.Subject -notmatch '(^|,\s*)O="?Docker Inc\.?"?(,|$)') {
            throw 'No se pudo verificar la firma de Docker. El archivo no se ejecutara.'
        }
        Write-Host 'Firma correcta. Instalando Docker Desktop; esta etapa no informa un porcentaje.'
        $process = Start-Process -FilePath $installer -Verb RunAs -Wait -PassThru -ArgumentList 'install --quiet --backend=wsl-2'
        if ($process.ExitCode -eq 3010 -or $process.ExitCode -eq 1641) { return 3010 }
        if ($process.ExitCode -ne 0) { throw 'El instalador de Docker no termino correctamente.' }
        return 0
    } finally {
        if (Test-Path -LiteralPath $folder) { Remove-Item -LiteralPath $folder -Recurse -Force }
    }
}

function Wait-EventifyDocker {
    param([string]$DesktopPath, [int]$TimeoutSeconds = 1800, [int]$PollSeconds = 5)
    if (Test-EventifyDockerReady) { return }
    if (-not $DesktopPath) {
        throw 'No se encontro Docker Desktop en su ruta habitual. Abralo manualmente y vuelva a iniciar.'
    }
    Start-Process -FilePath $DesktopPath | Out-Null
    Write-Host 'Abriendo Docker. Acepte sus terminos si aparecen; no necesita iniciar sesion.'
    Write-Host 'El primer inicio puede tardar varios minutos. Eventify continuara cuando el motor este listo.'
    $watch = [Diagnostics.Stopwatch]::StartNew()
    $nextNotice = 0
    try {
        while ($watch.Elapsed.TotalSeconds -lt $TimeoutSeconds) {
            $elapsed = $watch.Elapsed.ToString('hh\:mm\:ss')
            $status = "Esperando el motor de Docker | Tiempo transcurrido: $elapsed"
            # Docker no informa un porcentaje de arranque; mostrar tiempo evita inventarlo.
            Write-Progress -Id 2 -Activity 'Iniciando Docker Desktop' -Status $status -PercentComplete -1
            if ($watch.Elapsed.TotalSeconds -ge $nextNotice) {
                Write-Host $status
                $nextNotice = $watch.Elapsed.TotalSeconds + 15
            }
            if (Test-EventifyDockerReady) {
                Write-Host "Docker esta listo. Tiempo de espera: $elapsed. Continuando con Eventify."
                return
            }
            Start-Sleep -Seconds $PollSeconds
        }
        throw 'Docker no estuvo disponible dentro del tiempo de espera. No necesita reinstalar ni borrar datos: espere a que Docker indique Engine running y vuelva a abrir INSTALAR-EVENTIFY.cmd. Si no inicia, revise WSL, virtualizacion y memoria.'
    } finally {
        Write-Progress -Id 2 -Activity 'Iniciando Docker Desktop' -Completed
        $watch.Stop()
    }
}

function New-EventifyDesktopShortcuts {
    param([string]$Root, [string]$Desktop = [Environment]::GetFolderPath('Desktop'), [switch]$AllowLowMemory)
    if (-not $Desktop) { throw 'No se encontro el escritorio del usuario.' }
    $shell = New-Object -ComObject WScript.Shell
    $startFile = 'iniciar-windows.cmd'
    if ($AllowLowMemory) { $startFile = 'validar-windows.cmd' }
    foreach ($entry in @(
        @{ Name = 'Iniciar Eventify'; File = $startFile },
        @{ Name = 'Detener Eventify'; File = 'detener-windows.cmd' }
    )) {
        $link = $shell.CreateShortcut((Join-Path $Desktop ($entry.Name + '.lnk')))
        $link.TargetPath = $env:ComSpec
        $link.Arguments = '/c ""' + (Join-Path $Root $entry.File) + '""'
        $link.WorkingDirectory = $Root
        $link.Description = $entry.Name
        $link.Save()
    }
}

function Invoke-EventifyWindowsSetup {
    param([string]$Root, [string]$SetupScript, [switch]$AllowLowMemory)
    Write-Host '[1/4] Comprobando el equipo.'
    Assert-EventifyWindowsHost -AllowLowMemory:$AllowLowMemory
    Update-EventifyWindowsPath
    Write-Host '[2/4] Preparando Docker y comprobando su motor.'
    if (-not (Test-EventifyDockerReady)) {
        $desktop = Find-EventifyDockerDesktop
        if (-not $desktop) {
            if (-not (Test-EventifyWslInstalled)) {
                return (Initialize-EventifyWsl -SetupScript $SetupScript)
            }
            $code = Install-EventifyDockerDesktop
            if ($code -eq 3010) {
                Write-Host 'Reinicie Windows y vuelva a ejecutar instalar-windows.cmd.'
                return 3010
            }
            Update-EventifyWindowsPath
            $desktop = Find-EventifyDockerDesktop
        }
        Wait-EventifyDocker -DesktopPath $desktop
    }
    Write-Host '[3/4] Preparando Eventify y su base de datos. La primera construccion puede tardar.'
    & (Join-Path $Root 'scripts\start-windows.ps1') | Out-Host
    if ($LASTEXITCODE -ne 0) { throw 'Eventify no pudo iniciar. Revise el mensaje anterior.' }
    Write-Host '[4/4] Creando accesos y abriendo Eventify.'
    try {
        New-EventifyDesktopShortcuts -Root $Root -AllowLowMemory:$AllowLowMemory
        Write-Host 'Accesos creados en el escritorio: Iniciar Eventify y Detener Eventify.'
    } catch {
        Write-Warning 'No se pudieron crear los accesos. Use iniciar-windows.cmd y detener-windows.cmd en esta carpeta.'
    }
    $line = Get-Content -LiteralPath (Join-Path $Root '.env') | Where-Object { $_ -match '^APP_PORT=[0-9]+$' } | Select-Object -First 1
    if ($line) { Start-Process ('http://localhost:' + $line.Substring(9)) | Out-Null }
    return 0
}
