FROM wordpress
RUN update-ca-certificates
RUN a2enmod ssl headers deflate expires
RUN service apache2 restart

EXPOSE 80
EXPOSE 443

RUN chown -R www-data:www-data /tmp/
RUN chmod -R 777 /tmp/
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 777 /var/www/html/

# FROM mysql
# RUN update-ca-certificates
# RUN a2enmod ssl headers deflate expires
# RUN service apache2 restart