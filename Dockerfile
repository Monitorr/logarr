FROM organizrtools/base-alpine-nginx

# Set version label
ARG BUILD_DATE
ARG VERSION
LABEL build_version="Logarr version:- ${VERSION} Build-date:- ${BUILD_DATE}"
LABEL maintainer="Monitorr"



# Install packages
RUN \
 apk add --no-cache \
         curl \
         php7-curl \
         php7-zip \
         php7-sqlite3 \
         php7-pdo_sqlite \
         php7-fpm \
         php7-xmlrpc \
         git

# Add local files
COPY root/ /

# Port and volumes
EXPOSE 80
VOLUME /app
