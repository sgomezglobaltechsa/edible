echo off
cd C:\xampp\htdocs\edible
del *.php
echo -Borrando Php
cd C:\Work\_GIT GUB\edible
xcopy *.php C:\xampp\htdocs\edible
echo -Copiando php
cls
echo ***xampp actualizado***
exit