Set-StrictMode -Version Latest

function Invoke-GitHubTool {
    param([string]$Program, [string[]]$Arguments, [switch]$Live)
    $previous = $ErrorActionPreference
    try {
        $ErrorActionPreference = 'Continue'
        if ($Live) {
            & $Program @Arguments | Out-Host
        } else {
            $result = @(& $Program @Arguments)
        }
        if ($LASTEXITCODE -ne 0) {
            throw "No se pudo completar una operacion de $([IO.Path]::GetFileName($Program)). Revise el mensaje anterior y vuelva a ejecutar el asistente."
        }
        if (-not $Live) { return ($result -join "`n") }
    } finally {
        $ErrorActionPreference = $previous
    }
}

function Get-PortableGitHubTool {
    param([string]$Folder, [string]$Url, [string]$Hash, [string]$Executable)
    # La huella corresponde al archivo oficial de esta version, no a 'latest'.
    $archive = "$Folder.zip"
    Write-Host "Preparando $([IO.Path]::GetFileName($Folder))..."
    Invoke-WebRequest -UseBasicParsing -Uri $Url -OutFile $archive -TimeoutSec 300
    if ((Get-FileHash -LiteralPath $archive -Algorithm SHA256).Hash -ne $Hash) {
        throw 'La descarga no coincide con la huella esperada. No se ejecutara.'
    }
    Expand-Archive -LiteralPath $archive -DestinationPath $Folder -Force
    Remove-Item -LiteralPath $archive
    $path = Join-Path $Folder $Executable
    if (-not (Test-Path -LiteralPath $path -PathType Leaf)) {
        throw 'La herramienta descargada no tiene la estructura esperada.'
    }
    return $path
}

function ConvertTo-GitRefMap {
    param([AllowEmptyString()][string]$Text)
    $map = @{}
    foreach ($line in ($Text -split "`r?`n")) {
        if ([string]::IsNullOrWhiteSpace($line)) { continue }
        if ($line -notmatch '^([0-9a-f]{40})\s+(refs/(?:heads|tags)/\S+)$') {
            throw 'El inventario de ramas y etiquetas no tiene el formato esperado.'
        }
        if ($map.ContainsKey($Matches[2])) { throw 'Referencia repetida en el inventario.' }
        $map[$Matches[2]] = $Matches[1]
    }
    return $map
}

function Assert-GitRemoteSubset {
    param([hashtable]$Local, [hashtable]$Remote, [switch]$Complete)
    foreach ($ref in $Remote.Keys) {
        if (-not $Local.ContainsKey($ref) -or $Local[$ref] -ne $Remote[$ref]) {
            throw 'El repositorio de destino tiene cambios diferentes. Se detiene para conservarlos.'
        }
    }
    if ($Complete -and $Local.Count -ne $Remote.Count) {
        throw 'Todavia faltan ramas o etiquetas en GitHub.'
    }
}

function Assert-GitHubDestination {
    param($Repository, [string]$Owner, [string]$Name, $Receipt)
    if ($Repository.owner.login -cne $Owner -or $Repository.name -cne $Name -or -not $Repository.private) {
        throw 'El destino no corresponde al repositorio privado esperado.'
    }
    if ($null -ne $Receipt -and ([string]$Repository.id -ne [string]$Receipt.id)) {
        throw 'El repositorio fue sustituido desde el intento anterior. No se modificara.'
    }
}
