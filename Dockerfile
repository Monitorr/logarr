FROM lsiobase/alpine.nginx:3.7

MAINTAINER Monitorr

# Install packages
RUN \
 apk add --no-cache \
	php7-session \
	php7-zip \
	git
	
  
  
# Add local files
COPY root/ /

# Port and volumes
EXPOSE 80
VOLUME /config
