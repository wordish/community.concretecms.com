ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM amazeeio/php:7.4-fpm

ENV CONCRETE5_ENV=lagoon

COPY --from=cli /app /app

# Install required extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/1.5.39/install-php-extensions /usr/local/bin/

RUN apk update && apk upgrade --all
RUN sh /app/.lagoon/setup.sh
