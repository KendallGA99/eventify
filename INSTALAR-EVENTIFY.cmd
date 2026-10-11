@echo off
setlocal DisableDelayedExpansion
if exist "%~dp0Eventify\instalar-windows.cmd" (
    call "%~dp0Eventify\instalar-windows.cmd"
) else (
    call "%~dp0instalar-windows.cmd"
)
exit /b %errorlevel%
