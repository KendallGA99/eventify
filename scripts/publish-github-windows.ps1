param([string]$PackageRoot = (Split-Path -Parent $PSScriptRoot))
$ErrorActionPreference = 'Stop'
. (Join-Path $PSScriptRoot 'github-publish-functions.ps1')

$session = $null
$gh = $null
$savedEnvironment = @{}
$isolatedEnvironment = $false
try {
    if (-not [Environment]::Is64BitOperatingSystem -or $env:PROCESSOR_ARCHITEW6432 -eq 'ARM64' -or $env:PROCESSOR_ARCHITECTURE -eq 'ARM64') {
        throw 'Este asistente necesita Windows de 64 bits para procesadores Intel o AMD.'
    }
    $bundle = Join-Path $PackageRoot 'Eventify-Historial.bundle'
    $bundleHash = 'a8aa91dea9150214492488ee8c2cc3b7dff5e500f0d65c56e48a53bf39583ca4'
    if ((Get-FileHash -LiteralPath $bundle -Algorithm SHA256).Hash -ne $bundleHash) {
        throw 'El historial no coincide con el paquete entregado. Extraiga el ZIP completo de nuevo.'
    }
    $cache = Join-Path $env:LOCALAPPDATA 'Eventify-GitHub'
    New-Item -ItemType Directory -Path $cache -Force | Out-Null
    $session = Join-Path $cache ([guid]::NewGuid().ToString('N'))
    New-Item -ItemType Directory -Path $session | Out-Null
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
    $gh = Get-PortableGitHubTool -Folder (Join-Path $session 'gh') -Url 'https://github.com/cli/cli/releases/download/v2.102.0/gh_2.102.0_windows_amd64.zip' -Hash 'ae64e556ecc240b200f7eba60d550e4bb60d78e860e69dd88c449405b86067f4' -Executable 'bin/gh.exe'
    $git = Get-PortableGitHubTool -Folder (Join-Path $session 'git') -Url 'https://github.com/git-for-windows/git/releases/download/v2.56.0.windows.2/MinGit-2.56.0.2-64-bit.zip' -Hash 'da35e72aa21c005a5a0d298cfbae110bc1609a815730ea0dde84b01a1b3cd3be' -Executable 'cmd/git.exe'

    # No heredar tokens, destinos ni ajustes Git de otro proyecto o cuenta.
    $keys = @(@(Get-ChildItem Env: | Where-Object { $_.Name -match '^(GH_|GIT_|GITHUB_TOKEN$)' } | ForEach-Object { $_.Name }) + @('PATH', 'GH_CONFIG_DIR', 'GH_HOST', 'GIT_CONFIG_GLOBAL', 'GIT_CONFIG_NOSYSTEM', 'GIT_TERMINAL_PROMPT', 'GH_NO_UPDATE_NOTIFIER')) | Select-Object -Unique
    foreach ($key in $keys) {
        $savedEnvironment[$key] = [Environment]::GetEnvironmentVariable($key, 'Process')
        if ($key -ne 'PATH') { [Environment]::SetEnvironmentVariable($key, $null, 'Process') }
    }
    $isolatedEnvironment = $true
    $env:GH_CONFIG_DIR = Join-Path $session 'auth'
    $env:GH_HOST = 'github.com'
    $env:GH_NO_UPDATE_NOTIFIER = '1'
    $env:GIT_CONFIG_NOSYSTEM = '1'
    $env:GIT_CONFIG_GLOBAL = Join-Path $session 'gitconfig'
    [IO.File]::WriteAllText($env:GIT_CONFIG_GLOBAL, '')
    $env:GIT_TERMINAL_PROMPT = '0'
    $env:PATH = "$(Split-Path -Parent $gh);$(Split-Path -Parent $git);$env:PATH"

    Write-Host 'Autorice GitHub en el navegador. Si aparece un codigo, introduzcalo en la pagina oficial.'
    Invoke-GitHubTool -Program $gh -Arguments @('auth', 'login', '--hostname', 'github.com', '--git-protocol', 'https', '--web', '--scopes', 'workflow') -Live
    $user = (Invoke-GitHubTool -Program $gh -Arguments @('api', 'user')) | ConvertFrom-Json
    $owner = [string]$user.login
    if ($owner -notmatch '^[a-zA-Z0-9][a-zA-Z0-9-]{0,38}$') { throw 'No se pudo validar la cuenta.' }
    $name = 'eventify'
    Write-Host "Cuenta autenticada: $owner. Destino: $owner/$name (privado)."
    if ((Read-Host 'Crear o completar este repositorio en esta cuenta? Escriba SI') -cne 'SI') {
        throw 'Operacion cancelada. No se ha publicado el historial.'
    }

    $mirror = Join-Path $session 'historial.git'
    Invoke-GitHubTool -Program $git -Arguments @('clone', '--mirror', $bundle, $mirror) -Live
    Invoke-GitHubTool -Program $git -Arguments @('-C', $mirror, 'fsck', '--full') -Live
    $localRefs = ConvertTo-GitRefMap (Invoke-GitHubTool -Program $git -Arguments @('-C', $mirror, 'for-each-ref', '--format=%(objectname) %(refname)', 'refs/heads', 'refs/tags'))
    $defaultBranch = Invoke-GitHubTool -Program $git -Arguments @('-C', $mirror, 'symbolic-ref', '--short', 'HEAD')
    if (-not $localRefs.ContainsKey("refs/heads/$defaultBranch")) { throw 'Falta la rama inicial del paquete.' }

    $receiptFile = Join-Path $cache "$owner-$name-$bundleHash.json"
    $receipt = $null
    if (Test-Path -LiteralPath $receiptFile) {
        $receipt = Get-Content -LiteralPath $receiptFile -Raw | ConvertFrom-Json
        $repo = (Invoke-GitHubTool -Program $gh -Arguments @('api', "repos/$owner/$name")) | ConvertFrom-Json
        Assert-GitHubDestination $repo $owner $name $receipt
    } else {
        # GitHub rechaza el nombre si ya existe: nunca sobrescribir ni adoptar otro repositorio.
        $repo = (Invoke-GitHubTool -Program $gh -Arguments @('api', 'user/repos', '--method', 'POST', '-f', "name=$name", '-F', 'private=true', '-F', 'auto_init=false', '-f', 'description=Eventify - aplicacion de eventos y reservas')) | ConvertFrom-Json
        Assert-GitHubDestination $repo $owner $name $null
        @{ id = $repo.id; owner = $owner; bundle = $bundleHash } | ConvertTo-Json | Set-Content -LiteralPath $receiptFile -Encoding UTF8
    }
    # El historial incluye workflows; importarlo no debe iniciar ejecuciones o despliegues.
    Invoke-GitHubTool -Program $gh -Arguments @('api', "repos/$owner/$name/actions/permissions", '--method', 'PUT', '-F', 'enabled=false') -Live
    $url = "https://github.com/$owner/$name.git"
    $helper = '!"' + ($gh -replace '\\', '/') + '" auth git-credential'
    $gitAuth = @('-c', 'credential.helper=', '-c', "credential.helper=$helper")
    $remoteRefs = ConvertTo-GitRefMap (Invoke-GitHubTool -Program $git -Arguments ($gitAuth + @('ls-remote', '--refs', $url, 'refs/heads/*', 'refs/tags/*')))
    Assert-GitRemoteSubset $localRefs $remoteRefs
    Write-Host 'Subiendo ramas y etiquetas. Git mostrara el avance de la transferencia...'
    Invoke-GitHubTool -Program $git -Arguments ($gitAuth + @('-C', $mirror, 'push', $url, 'refs/heads/*:refs/heads/*', 'refs/tags/*:refs/tags/*')) -Live
    Invoke-GitHubTool -Program $gh -Arguments @('api', "repos/$owner/$name", '--method', 'PATCH', '-f', "default_branch=$defaultBranch", '--silent') -Live
    $remoteRefs = ConvertTo-GitRefMap (Invoke-GitHubTool -Program $git -Arguments ($gitAuth + @('ls-remote', '--refs', $url, 'refs/heads/*', 'refs/tags/*')))
    Assert-GitRemoteSubset $localRefs $remoteRefs -Complete
    $repo = (Invoke-GitHubTool -Program $gh -Arguments @('api', "repos/$owner/$name")) | ConvertFrom-Json
    $receipt = Get-Content -LiteralPath $receiptFile -Raw | ConvertFrom-Json
    Assert-GitHubDestination $repo $owner $name $receipt
    if ($repo.default_branch -cne $defaultBranch) { throw 'No se pudo confirmar la rama inicial.' }
    $web = "https://github.com/$owner/$name"
    Write-Host "Terminado. $($localRefs.Count) ramas y etiquetas comprobadas: $web"
    Write-Host 'El repositorio es privado. Desde Settings > Collaborators puede invitar al profesor.'
    try { Start-Process $web } catch { Write-Host "Abra $web en su navegador." }
} catch {
    Write-Host "No se completo la publicacion: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host 'No se borro ningun repositorio. Puede volver a ejecutar este CMD para reintentar.'
    $failed = $true
} finally {
    if ($isolatedEnvironment) {
        if ($null -ne $gh) {
            try { Invoke-GitHubTool -Program $gh -Arguments @('auth', 'logout', '--hostname', 'github.com') -Live } catch { }
        }
        foreach ($key in $savedEnvironment.Keys) {
            [Environment]::SetEnvironmentVariable($key, $savedEnvironment[$key], 'Process')
        }
    }
    if ($null -ne $session -and (Test-Path -LiteralPath $session)) {
        Remove-Item -LiteralPath $session -Recurse -Force -ErrorAction SilentlyContinue
    }
}
if (Get-Variable -Name failed -ErrorAction SilentlyContinue) { exit 1 }
exit 0
