@echo off
@SETLOCAL
@SET PATHEXT=%PATHEXT:;.JS;=;%
php "%~dp0\dce" %*