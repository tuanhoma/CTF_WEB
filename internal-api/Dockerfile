FROM php:8.1-fpm

RUN apt-get update && apt-get install -y cron curl libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql curl

# Copy source files
COPY src/ /var/www/internal-api/

# VULN: .env with hardcoded credentials baked into image
RUN mkdir -p /app && \
    printf 'DB_HOST=db\nDB_PORT=3306\nDB_NAME=corpdb\nDB_USER=appuser\nDB_PASS=SuperSecret123!\nAPP_SECRET=jwt_signing_key_abc123\nINTERNAL_API_URL=http://internal-api\nADMIN_TOKEN=eyJhbGciOiJIUzI1NiJ9.secret\n' > /app/.env

# Backup script setup
RUN mkdir -p /backup
COPY backup/run.sh /backup/run.sh
RUN chmod 755 /backup/run.sh

# VULN: Cron job runs as root — tar wildcard injection
COPY backup/backup.cron /etc/cron.d/backup
RUN chmod 0644 /etc/cron.d/backup && crontab /etc/cron.d/backup

WORKDIR /var/www/internal-api

# Start cron daemon and PHP-FPM
CMD cron && php-fpm
