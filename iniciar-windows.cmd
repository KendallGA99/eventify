@echo off
setlocal DisableDelayedExpansion
cd /d "%~dp0"
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\setup-windows.ps1"
set "eventify_exit=%errorlevel%"
pause
exit /b %eventify_exit%
