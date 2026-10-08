@echo off
cd /d "%~dp0"
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\start-windows.ps1"
set "eventify_exit=%errorlevel%"
pause
exit /b %eventify_exit%
