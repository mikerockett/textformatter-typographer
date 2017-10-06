@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../mundschenk-at/php-typography/src/bin/update-iana.php
php "%BIN_TARGET%" %*
