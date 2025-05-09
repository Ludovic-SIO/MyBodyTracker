### Fichier `docker-compose.yml`

Le fichier `docker-compose.yml` suivant permet de construire et exécuter le conteneur PHP avec support HTTPS :

```yaml
php:
  build:
    context: .
    dockerfile: Dockerfile
  container_name: php_gestion_tournament
  ports:
    - "8000:80"
    - "443:443"
  volumes:
    - ./Video_game_tournament_management/site:/var/www/html
```

#### Description du Service

**Service PHP / Apache** :

- **Construction** : Utilise un `Dockerfile` personnalisé pour ajouter le support SSL à Apache.
- **Nom du Conteneur** : `php_gestion_tournament`.
- **Ports** :
    - 8000 pour HTTP (port 80 interne exposé sur 8000). 
    - 443 pour HTTPS.
- **Volumes** :
    - Monte le code source local du site dans `/var/www/html` dans le conteneur.

---

### Fichier `Dockerfile`

Le fichier `Dockerfile` construit l’image Apache avec SSL activé :

```Dockerfile
FROM php:8.2-apache

# Installation des dépendances PHP et Git
RUN apt-get update && apt-get install -y \
    git \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && a2enmod ssl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copier les fichiers de certificat dans le conteneur
COPY /Video_game_tournament_management/docker/certificate/certificat.crt /etc/ssl/certs/
COPY /Video_game_tournament_management/docker/certificate/certificat.key /etc/ssl/private/

# Copier la configuration SSL personnalisée
COPY /Video_game_tournament_management/docker/config/ssl/gestion-tournament-ssl-config.conf /etc/apache2/sites-available/000-default.conf

# Activer le site par défaut avec SSL
RUN a2ensite 000-default.conf

EXPOSE 80 443
```

#### Étapes Importantes

- **Activation du module SSL** : `a2enmod ssl` permet à Apache de servir des connexions sécurisées.
- **Copie des Certificats SSL** :
    - `certificat.crt` : Certificat public.
    - `certificat.key` : Clé privée.
- **Configuration Apache SSL** : Le VirtualHost HTTPS est défini dans `gestion-tournament-ssl-config.conf`.
- **Exposition des Ports** : 80 et 443 sont exposés pour HTTP et HTTPS respectivement.

---

### Fichier `gestion-tournament-ssl-config.conf`

```apache
<VirtualHost *:443>
    ServerAdmin webmaster@localhost
    ServerName gestionTournament.com
    DocumentRoot /var/www/html

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/certificat.crt
    SSLCertificateKeyFile /etc/ssl/private/certificat.key

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

#### Description

- **VirtualHost :443** : Déclaration du site sur le port HTTPS.
- **SSL Configuration** :
    - Active SSL avec `SSLEngine on`.
    - Spécifie les chemins vers les certificats.
- **Sécurité & Permissions** :
    - Accès accordé à tout le monde (`Require all granted`).
- **Logs** :
    - Enregistre les erreurs et accès dans les fichiers de log A