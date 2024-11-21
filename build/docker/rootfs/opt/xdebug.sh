#!/bin/ash

function enableXdebug() {

  if [ -f "/etc/php83/conf.d/101_xdebug.ini" ]; then
    echo "is already enabled."
    exit 0
  fi

  CONFIG=$(cat <<-END
[Xdebug]
zend_extension=xdebug.so
xdebug.mode=develop,debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.idekey=PHPSTORM
xdebug.client_port=9003
xdebug.file_link_format=phpstorm://open?%f:%l
END
)
  echo "$CONFIG" > /etc/php83/conf.d/101_xdebug.ini
  supervisorctl -c /etc/supervisord.conf restart php-fpm &> /dev/null
}

function disableXdebug() {
  rm /etc/php83/conf.d/101_xdebug.ini
  supervisorctl -c /etc/supervisord.conf restart php-fpm &> /dev/null
}

MODE="$1"

if [ "$MODE" == "on" ]; then
  echo -n "Enabling Xdebug... "
  enableXdebug
  echo "done."
elif [ "$MODE" == "off" ]; then
  echo -n "Disabling Xdebug... "
  disableXdebug
  echo "done."
else
  echo "Please specify \"on\" or \"off\""
fi

