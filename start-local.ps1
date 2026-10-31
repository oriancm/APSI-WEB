param([switch]$RequireDatabase)

$ErrorActionPreference = "Stop"

$port = 8088
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$preferredPhp = "C:\Users\orian\Downloads\UniServerZ\core\php83\php.exe"

if (Test-Path $preferredPhp) {
    $php = $preferredPhp
} else {
    $phpCommand = Get-Command php -ErrorAction Stop
    $php = $phpCommand.Source
}

$listeners = Get-NetTCPConnection -LocalPort $port -ErrorAction SilentlyContinue |
    Where-Object { $_.State -eq "Listen" }

foreach ($listener in $listeners) {
    Stop-Process -Id $listener.OwningProcess -Force
}

$previousDemoSetting = [Environment]::GetEnvironmentVariable('APSI_ALLOW_DEMO_DATA', 'Process')
try {
    [Environment]::SetEnvironmentVariable('APSI_ALLOW_DEMO_DATA', $(if ($RequireDatabase) { '0' } else { '1' }), 'Process')
    Start-Process -FilePath $php `
        -ArgumentList @("-S", "127.0.0.1:$port", "router.php") `
        -WorkingDirectory $root `
        -WindowStyle Hidden
} finally {
    [Environment]::SetEnvironmentVariable('APSI_ALLOW_DEMO_DATA', $previousDemoSetting, 'Process')
}

Write-Host "APSI local server started on http://127.0.0.1:$port"
if (!$RequireDatabase) { Write-Host "Apercu local avec donnees de demonstration. Pour utiliser une vraie base : start-local.ps1 -RequireDatabase" }
Write-Host "Important: routes propres (/aboutUs, /references...) utilisent router.php. Lance toujours ce script plutot que php -S seul."
