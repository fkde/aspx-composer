FROM alpine:3.20.1

# Define meta informations
MAINTAINER Flo Knapp
LABEL maintainer="office@florianknapp.de"

ENV ENV="/home/www/.profile"

# Install packages
RUN apk --no-cache update && \
    apk add --no-cache \
	supervisor \
	curl \
	php83 \
	php83-fpm \
	php83-json \
	php83-ldap \
	php83-curl \
	php83-phar \
	php83-pdo \
    php83-iconv \
    php83-mbstring \
	php83-pdo_mysql \
	php83-simplexml \
	php83-dom \
	php83-ctype \
	php83-tokenizer \
	php83-xml \
	php83-xmlwriter \
	php83-session \
    php83-pecl-xdebug \
    nginx

COPY rootfs /

RUN adduser --disabled-password --shell /bin/ash --home /home/www www && \
	addgroup www www && \
	mkdir -p /var/run/php && \
	echo "alias ll='ls -la --color=auto'" > /home/www/.profile && \
	wget -O /usr/bin/composer https://getcomposer.org/download/2.5.8/composer.phar && \
	chmod +x /usr/bin/composer && \
	if [ ! -e "/usr/bin/php" ]; then ln -s /usr/bin/php83 /usr/bin/php; fi && \
    chmod +x /opt/xdebug.sh

WORKDIR /var/www

# Run
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

HEALTHCHECK --interval=10s --timeout=3s \
  CMD curl -f http://localhost/health || exit 1

# Expose required ports
EXPOSE 443
