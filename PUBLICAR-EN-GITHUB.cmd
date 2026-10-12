@echo off
setlocal
cd /d "%~dp0"
powershell.exe -NoLogo -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\publish-github-windows.ps1"
set "RESULT=%ERRORLEVEL%"
echo.
pause
exit /b %RESULT%
