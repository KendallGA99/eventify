@echo off
setlocal DisableDelayedExpansion
cd /d "%~dp0"
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\setup-windows.ps1" -AllowLowMemory
set "eventify_exit=%errorlevel%"
if "%eventify_exit%"=="3010" echo Reinicie Windows y vuelva a ejecutar validar-windows.cmd.
pause
exit /b %eventify_exit%
