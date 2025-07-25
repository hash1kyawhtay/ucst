# Use official PHP with Apache
FROM php:8.2-apache

# Copy frontend code
COPY . /var/www/html/

# Install PDO and MySQL driver
COPY ../backend /var/www/html/backend

# Set proper permissions (optional)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
