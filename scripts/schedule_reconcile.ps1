<#
.SYNOPSIS
  Create a Windows Scheduled Task to run the reconciliation PHP CLI script.

.DESCRIPTION
  This helper registers a scheduled task using schtasks.exe. The task runs
  the project's `bin_reconcile.php` using the specified `php.exe` binary.

.NOTES
  - Run this script in an elevated PowerShell prompt (Run as Administrator).
  - Adjust the `-PhpPath` and `-AppPath` parameters as needed.

USAGE
  # Register task to run every 30 minutes (defaults)
  .\schedule_reconcile.ps1 -PhpPath 'C:\php\php.exe' -AppPath 'C:\Users\olipas\my-ksc-app' -Minutes 30

# Remove the task
  schtasks /Delete /TN "SeedStorage_Reconcile" /F
#>
param(
    [string]$PhpPath = "C:\\php\\php.exe",
    [string]$AppPath = (Get-Location).Path,
    [int]$Minutes = 30,
    [string]$TaskName = "SeedStorage_Reconcile"
)

if (-not (Test-Path $PhpPath)) {
    Write-Error "php.exe not found at '$PhpPath'. Please install PHP and provide the correct path via -PhpPath."
    exit 1
}

$scriptPath = Join-Path -Path $AppPath -ChildPath 'bin_reconcile.php'
if (-not (Test-Path $scriptPath)) {
    Write-Error "bin_reconcile.php not found at '$scriptPath'. Ensure you passed the correct -AppPath."
    exit 1
}

Write-Output "Scheduling reconciliation task '$TaskName' to run every $Minutes minute(s)."
Write-Output "PHP: $PhpPath"
Write-Output "Script: $scriptPath"

$action = "`"$PhpPath`" `"$scriptPath`""
$cmd = "schtasks /Create /SC MINUTE /MO $Minutes /TN `"$TaskName`" /TR `"$action`" /F"

Write-Output "Registering task... (you may be prompted for elevation if required)"
Write-Output "Command: $cmd"

try {
    Invoke-Expression $cmd
    Write-Output "Scheduled task created. Verify with: schtasks /Query /TN \"$TaskName\" /V"
} catch {
    Write-Error "Failed to register scheduled task: $_"
    exit 1
}
