@ECHO OFF
SET BIN_TARGET=%~dp0/../codeception/codeception/codecept
php "%BIN_TARGET%" %*
