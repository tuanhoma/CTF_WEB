$hostsPath = "C:\Windows\System32\drivers\etc\hosts"

$lines = @(
    "127.0.0.1 portal.lab.local",
    "127.0.0.1 api.lab.local",
    "127.0.0.1 staff.lab.local"
)

foreach ($line in $lines) {
    $domain = $line.Split(" ")[1]
    $current = Get-Content $hostsPath -Raw
    if ($current -notmatch $domain) {
        Add-Content -Path $hostsPath -Value $line
        Write-Host "Added: $line"
    } else {
        Write-Host "Skip (exists): $domain"
    }
}

ipconfig /flushdns
Write-Host "Done. Press any key..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
