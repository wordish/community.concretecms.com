ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM amazeeio/php:7.4-fpm

ENV CONCRETE5_ENV=lagoon

COPY --from=cli /app /app

# Install required extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/1.2.45/install-php-extensions /usr/local/bin/
RUN sh /app/.lagoon/setup.sh
