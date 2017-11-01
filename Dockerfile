FROM 1and1internet/debian-8
MAINTAINER brian.wojtczak@1and1.co.uk
COPY files/ /
ARG PHP_VERSION=7.1
RUN export DEBIAN_FRONTEND=noninteractive DEBCONF_NONINTERACTIVE_SEEN=true \
	&& echo ${PHP_VERSION} > /etc/PHP_VERSION \
	&& apt-get update \
	&& apt-get install --no-install-recommends \
		nginx-light fcgiwrap curl \
		php${PHP_VERSION}-fpm php${PHP_VERSION}-dba php${PHP_VERSION}-mysql php${PHP_VERSION}-opcache \
		php${PHP_VERSION}-mbstring php${PHP_VERSION}-zip php${PHP_VERSION}-gd php${PHP_VERSION}-json \
		php${PHP_VERSION}-xml php${PHP_VERSION}-curl \
	&& cd /tmp \
	&& FILE=/etc/supervisor/conf.d/php-fpm.conf && envsubst '${PHP_VERSION}' < ${FILE}.template > ${FILE} \
	&& FILE=/etc/nginx/sites-available/phpmyadmin.conf && envsubst '${PHP_VERSION}' < ${FILE}.template > ${FILE} \
	&& ln -s /etc/nginx/sites-available/phpmyadmin.conf /etc/nginx/sites-enabled/ \
	&& rm /etc/nginx/sites-enabled/default \
	&& sed -i -r -e 's/^;clear_env = no/clear_env = no/' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf \
	&& sed -i -r -e 's/^user/;user/' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf \
	&& sed -i -r -e 's/^group/;group/' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf \
	&& sed -i -r -e 's/^listen.owner/;listen.owner/' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf \
	&& sed -i -r -e 's/^listen.group/;listen.group/' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf \
	&& sed -i -r -e 's/^user /#user /' /etc/nginx/nginx.conf \
	&& echo "daemon off;" >> /etc/nginx/nginx.conf \
	&& URL=https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.tar.gz \
	&& curl --output phpMyAdmin.tar.gz --location $URL \
	&& curl --output phpMyAdmin.tar.gz.asc --location $URL.asc \
	&& gpgv --keyring /etc/phpmyadmin/gnupg.keyring phpMyAdmin.tar.gz.asc phpMyAdmin.tar.gz \
	&& tar -x -zf phpMyAdmin.tar.gz \
	&& rm -rf /var/www/html/* \
	&& mv phpMyAdmin*/* /var/www/html/ \
	&& mv /etc/phpmyadmin/config.container.inc.php /var/www/html/config.inc.php \
	&& rm -rf \
		/tmp/* \
		/var/www/html/examples /var/www/html/test /var/www/html/po /var/www/html/js/jquery/src \
		/var/www/html/composer.json \
	&& mkdir -p /var/run/php /var/lib/nginx /var/lib/php \
	&& chmod -R 755 /init /hooks \
	&& find /var/www -type d -exec chmod 777 {} \; \
	&& find /var/www -type f -exec chmod 666 {} \; \
	&& nginx -t \
	&& chmod -R 777 /var/run /var/log /var/lib/nginx /var/lib/php /etc/nginx /etc/php \
	&& rm -rf /var/log/nginx/error.log /var/run/nginx.pid \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*
ENV \
	PMA_ARBITRARY=1 \
	PMA_HOST="mysql" \
	PMA_VERBOSE="mysql" \
	PMA_PORT="3306" \
	PMA_HOSTS="" \
	PMA_VERBOSES="" \
	PMA_PORTS="" \
	PMA_CONTROL_HOST="" \
	PMA_CONTROL_PORT="" \
	PMA_CONTROL_USER="" \
	PMA_CONTROL_PASSWORD="" \
	PMA_ABSOLUTE_URI=""
EXPOSE 8080
