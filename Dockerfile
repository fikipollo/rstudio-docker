############################################################
# Dockerfile to build RStudio container image for the eBioKit
# Based on rocker/rstudio-stable
# Version 0.9 September 2017
# TODO:
# - Enable Shiny server?
############################################################

# Set the base image to rocker/rstudio-stable
FROM rocker/rstudio-stable:3.4.1

# File Author / Maintainer
MAINTAINER Rafael Hernandez <https://github.com/fikipollo>

################## BEGIN INSTALLATION ######################
#Install dependencies
RUN apt-get update \
    && apt-get -y install nginx php-fpm apache2-utils \
    && apt-get clean

#Copy files
COPY configs/* /tmp/

RUN mv /tmp/index.html /usr/lib/rstudio-server/www/templates/encrypted-sign-in.htm \
    && mv /tmp/*.png /usr/lib/rstudio-server/www/images/ \
    && rm /var/www/html/* \
    && mv /tmp/*.php /var/www/html \
    && chown www-data:www-data /var/www/html/* \
    && chmod 660 /var/www/html/* \
    && cat /tmp/rules >> /etc/sudoers \
    && mv /tmp/default /etc/nginx/sites-available/ \
    && mv /tmp/entrypoint.sh /usr/bin/entrypoint.sh \
    && chmod +x /usr/bin/entrypoint.sh \
    && rm -r /tmp/* \
    && htpasswd -b -c /etc/nginx/.htpasswd admin supersecret

##################### INSTALLATION END #####################

ENTRYPOINT ["/usr/bin/entrypoint.sh"]
