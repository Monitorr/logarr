FROM Monitorr/base-alpine-nginx
MAINTAINER Monitorr

# Set version label
ARG BUILD_DATE
ARG VERSION

# Install packages
RUN \
 apk add --no-cache \
         curl \
         php7-curl \
         php7-zip \
         php7-sqlite3 \
         php7-pdo_sqlite \
         php7-fpm \
         git

# Add local files
COPY root/ /

# Port and volumes
EXPOSE 80
VOLUME /app
