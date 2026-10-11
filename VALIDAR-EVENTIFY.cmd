@echo off
setlocal DisableDelayedExpansion
if exist "%~dp0Eventify\validar-windows.cmd" (
    call "%~dp0Eventify\validar-windows.cmd"
) else (
    call "%~dp0validar-windows.cmd"
)
exit /b %errorlevel%
