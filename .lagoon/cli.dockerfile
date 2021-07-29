FROM amazeeio/php:7.4-cli
ENV CONCRETE5_ENV=lagoon

# Pull in concrete console
RUN composer global require concrete5/console
RUN curl -LO https://github.com/jackhftang/tusc/releases/download/0.4.7/tusc_linux_amd64 && chmod +x tusc_linux_amd64 && mv tusc_linux_amd64 /usr/bin/tusc

COPY . /app

# Install PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/1.2.45/install-php-extensions /usr/local/bin/
RUN sh /app/.lagoon/setup.sh

# Install composer
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

