#!/bin/bash

############################################################################
#
# This file is part of BuildADoc.
#
# (c) Guido Obst
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
############################################################################

xdebug_ini="/etc/php/8.3/mods-available/xdebug.ini"
xdebug_ini_cli="/etc/php/8.3/cli/conf.d/20-xdebug.ini"

enable_xdebug() {
    sed -i 's/;zend_extension = xdebug.so/zend_extension = xdebug.so/' $xdebug_ini
    sed -i 's/;zend_extension = xdebug.so/zend_extension = xdebug.so/' $xdebug_ini_cli
    /etc/init.d/apache2 restart
    echo "Xdebug has been activated."
}

disable_xdebug() {
    sed -i 's/zend_extension = xdebug.so/;zend_extension = xdebug.so/' $xdebug_ini
    sed -i 's/zend_extension = xdebug.so/;zend_extension = xdebug.so/' $xdebug_ini_cli
    /etc/init.d/apache2 restart
    echo "Xdebug has been deactivated."
}

enable_xdebug_profiler() {
    sed -i 's/xdebug.profiler_enable = 0/xdebug.profiler_enable = 1/' $xdebug_ini
    sed -i 's/xdebug.profiler_enable = 0/xdebug.profiler_enable = 1/' $xdebug_ini_cli
    /etc/init.d/apache2 restart
    echo "Xdebug Profiler has been activated."
}

disable_xdebug_profiler() {
    sed -i 's/xdebug.profiler_enable = 1/xdebug.profiler_enable = 0/' $xdebug_ini
    sed -i 's/xdebug.profiler_enable = 1/xdebug.profiler_enable = 0/' $xdebug_ini_cli
    /etc/init.d/apache2 restart
    echo "Xdebug Profiler has been deactivated."
}

if [ "$(id -u)" != "0" ]; then
    echo "This script must be executed with root rights."
    exit 1
fi

if [ "$1" = "on" ]; then
    enable_xdebug
elif [ "$1" = "off" ]; then
    disable_xdebug
elif [ "$1" = "profile_on" ]; then
    enable_xdebug_profiler
elif [ "$1" = "profile_off" ]; then
    disable_xdebug_profiler
else
    echo "Usage: $0 [on|off|profile_on|profile_off]"
    exit 1
fi