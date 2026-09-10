# Use the base image
FROM dunglas/frankenphp:php8.4.8

ARG APP_ENV=dev
ARG REDIS_URL

ENV APP_ENV=${APP_ENV}
ENV REDIS_URL=${REDIS_URL}

COPY Caddyfile /etc/frankenphp/Caddyfile
COPY Caddyfile.local /etc/frankenphp/Caddyfile.local

RUN if [ "$APP_ENV" = "local" ]; then \
    cp /etc/frankenphp/Caddyfile.local /etc/frankenphp/Caddyfile; \
fi

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini \
    && sed -i 's/^memory_limit = .*/memory_limit = 128M/' "$PHP_INI_DIR/php.ini" \
    && echo "curl.cainfo = /etc/ssl/certs/ca-certificates.crt" >> "$PHP_INI_DIR/php.ini" \
    && echo "openssl.cafile = /etc/ssl/certs/ca-certificates.crt" >> "$PHP_INI_DIR/php.ini" \
    && if [ -n "$REDIS_URL" ]; then \
        echo "session.save_handler = redis" >> "$PHP_INI_DIR/php.ini"; \
        echo "session.save_path = \"$REDIS_URL?timeout=2&read_timeout=2&persistent=1\"" >> "$PHP_INI_DIR/php.ini"; \
        echo "session.gc_maxlifetime = 3600" >> "$PHP_INI_DIR/php.ini"; \
        echo "session.cookie_lifetime = 3600" >> "$PHP_INI_DIR/php.ini"; \
        echo "redis.session.locking_enabled = 1" >> "$PHP_INI_DIR/php.ini"; \
        echo "redis.session.lock_expire = 30" >> "$PHP_INI_DIR/php.ini"; \
        echo "redis.session.lock_retries = 100" >> "$PHP_INI_DIR/php.ini"; \
        echo "redis.session.lock_wait_time = 10000" >> "$PHP_INI_DIR/php.ini"; \
    fi

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    default-mysql-client \
    default-libmysqlclient-dev \
    ca-certificates \
    nano &&\
    update-ca-certificates &&\
    install-php-extensions \
	pdo_mysql \
	gd \
	intl \
	zip \
	opcache \
	redis

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY . /app
WORKDIR /app

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN if [ "$APP_ENV" = "dev" ] || [ "$APP_ENV" = "local" ]; then \
    pecl install xdebug && docker-php-ext-enable xdebug; \
fi


RUN cd lib/composer && composer install && composer dump-autoload && cd .. && cd ..


EXPOSE 80