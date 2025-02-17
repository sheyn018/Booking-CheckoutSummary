# Use an official PHP runtime as a parent image
FROM php:8.2-apache

# Copy application files to the container
COPY public/ /var/www/html/

# Expose port 80 to Render
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
