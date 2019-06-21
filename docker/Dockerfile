FROM 10.201.102.46/sunface/sunface-php:rar
COPY cms.conf /etc/nginx/conf.d/
COPY html/. /var/www/html/
COPY run.sh /run.sh
RUN chmod 755 /run.sh
