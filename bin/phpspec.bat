@ECHO OFF
SET BIN_TARGET=%~dp0/../vendor/phpspec/phpspec/bin/phpspec
php "%BIN_TARGET%" %*
