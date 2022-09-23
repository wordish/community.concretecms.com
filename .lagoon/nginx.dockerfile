ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM amazeeio/nginx-drupal

COPY --from=cli /app /app

ENV WEBROOT=public

RUN apk update && apk upgrade --all
