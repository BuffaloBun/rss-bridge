FROM php:7-apache

ENV APACHE_DOCUMENT_ROOT=/home/container

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
	&& apt-get --yes update \
	&& apt-get --yes --no-install-recommends install \
		zlib1g-dev \
		libmemcached-dev \
	&& pecl install memcached \
	&& docker-php-ext-enable memcached \
	&& sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
	&& sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
	&& sed -ri -e 's/(MinProtocol\s*=\s*)TLSv1\.2/\1None/' /etc/ssl/openssl.cnf \
	&& sed -ri -e 's/(CipherString\s*=\s*DEFAULT)@SECLEVEL=2/\1/' /etc/ssl/openssl.cnf \
	&& mkdir -p /home/container \
	&& chown www-data:www-data / && chown www-data:www-data /home && chown www-data:www-data /home/container \
	&& chmod +x / && chmod +x /home
	
ARG USER_ID=1000
ARG GROUP_ID=1000
RUN userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -g www-data www-data &&\
    install -d -m 0755 -o www-data -g www-data /home/container
USER www-data

COPY --chown=www-data:www-data ./ /home/container