FROM php:8.2-apache

# Installation des dépendances PHP, Git et client MySQL
RUN apt-get update && apt-get install -y \
  libpq-dev \
  git \
  openssl \
  default-mysql-client \
  && docker-php-ext-install pdo pdo_mysql mysqli

# Clonage du dépôt MyBodyTracker (public)
RUN git clone https://github.com/Ludovic-SIO/MyBodyTracker.git /tmp/MyBodyTracker

# Copie des fichiers PHP dans Apache
RUN cp -r /tmp/MyBodyTracker/site/* /var/www/html/

# Nettoyage du dépôt temporaire
RUN rm -rf /tmp/MyBodyTracker

# Activation des modules Apache nécessaires pour SSL
RUN a2enmod ssl && a2enmod rewrite

# Création d'un certificat SSL auto-signé (pour le développement uniquement)
RUN mkdir -p /etc/ssl/certs /etc/ssl/private \
  && openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/ssl/private/mybodytracker.key \
  -out /etc/ssl/certs/mybodytracker.crt \
  -subj "/C=FR/ST=Paris/L=Paris/O=MyBodyTracker/OU=Dev/CN=localhost"

# Configuration d'Apache pour HTTPS
COPY ./apache/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
RUN a2ensite default-ssl

# Exposer les ports HTTP et HTTPS
EXPOSE 80 443
