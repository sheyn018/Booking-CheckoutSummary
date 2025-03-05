# Use an official PHP runtime with Apache
FROM php:8.2-apache

# Set the ServerName to avoid warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Change Apache's DocumentRoot to /app/public
RUN sed -i 's|/var/www/html|/app/public|' /etc/apache2/sites-available/000-default.conf

# Copy all project files to /app instead of /var/www/html
COPY . /app/

# Set proper permissions
RUN chown -R www-data:www-data /app

# Enable directory listing or create an index file
RUN echo '<Directory "/app/public">' >> /etc/apache2/apache2.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
