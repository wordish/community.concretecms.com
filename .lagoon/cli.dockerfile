FROM amazeeio/php:7.4-cli

# Install PHP extensions
RUN docker-php-ext-install intl

# Add GD
RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
  docker-php-ext-configure gd \
    --with-gd \
    --with-freetype-dir=/usr/include/ \
    --with-png-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} gd && \
  apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

# Pull in concrete console
RUN composer global require concrete5/console
RUN curl -LO https://github.com/jackhftang/tusc/releases/download/0.4.7/tusc_linux_amd64 && chmod +x tusc_linux_amd64 && mv tusc_linux_amd64 /usr/bin/tusc

COPY . /app
RUN php -d memory_limit=-1 `which composer` install --no-dev -o

# Set up symlinks
RUN rm -rf /app/public/application/files
RUN rm -rf /app/public/application/config/generated_overrides

RUN mkdir -p /storage/files
RUN mkdir -p /storage/generated_overrides

RUN ln -sf /storage/files /app/public/application/files
RUN mkdir -p /app/public/application/config/doctrine/proxies && ln -sf /storage/proxies /app/public/application/config/doctrine/proxies
RUN ln -sf /storage/generated_overrides /app/public/application/config/generated_overrides

# Define where the webroot is located
ENV WEBROOT=public

