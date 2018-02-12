FROM lsiobase/alpine.nginx:3.6

MAINTAINER Monitorr

# Add local files
COPY root/ /

# Port and volumes
EXPOSE 80
VOLUME /config
