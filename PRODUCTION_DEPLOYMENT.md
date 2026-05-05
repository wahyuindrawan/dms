# Production Deployment Guide

Panduan untuk deploy Surat App ke production server menggunakan Docker.

## 🎯 Pre-Deployment Checklist

- [ ] Laravel application fully tested
- [ ] All migrations created and tested
- [ ] Static assets built and optimized
- [ ] Environment configuration prepared
- [ ] Database backup strategy configured
- [ ] Monitoring & logging setup
- [ ] SSL/HTTPS certificate obtained
- [ ] Domain configured
- [ ] GitHub repository updated

## 🖥️ Server Requirements

**Minimum:**
- OS: Ubuntu 20.04 LTS (recommended)
- RAM: 2GB
- Storage: 20GB
- CPU: 2 cores

**Recommended for Production:**
- RAM: 4GB+
- Storage: 50GB+
- CPU: 4+ cores
- Dedicated database server

## 📦 Installation Steps

### 1. Server Preparation

```bash
# Update system
sudo apt-get update && sudo apt-get upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add user to docker group
sudo usermod -aG docker $USER

# Verify installation
docker --version
docker-compose --version
```

### 2. Application Deployment

```bash
# Clone repository
cd /opt
sudo git clone https://github.com/your-org/surat-app.git
cd surat-app

# Set permissions
sudo chown -R $USER:$USER .

# Create production environment
cp docker/.env.docker .env.production

# Edit configuration
nano .env.production
```

### 3. Production Environment Configuration

```env
# .env.production - Production specific settings

APP_NAME="Surat App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=Asia/Jakarta

# Security
APP_KEY=base64:your_secure_key_here

# Database
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=surat_prod
DB_USERNAME=surat_user
DB_PASSWORD=very_secure_password_min_32_chars

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=very_secure_redis_password
REDIS_PORT=6379

# Session & Cache
CACHE_STORE=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@domain.com
MAIL_FROM_NAME="Surat App"

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=notice

# Ports
NGINX_PORT=80
PHPMYADMIN_PORT=8081  # Disable in production!
```

### 4. HTTPS/SSL Configuration

```bash
# Option 1: Let's Encrypt (Free)
sudo apt-get install certbot python3-certbot-nginx -y
sudo certbot certonly --standalone -d yourdomain.com

# Copy certificate to project
mkdir -p docker/ssl
sudo cp /etc/letsencrypt/live/yourdomain.com/fullchain.pem docker/ssl/
sudo cp /etc/letsencrypt/live/yourdomain.com/privkey.pem docker/ssl/
sudo chown $USER:$USER docker/ssl/*
```

Update `docker/nginx.conf`:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    
    ssl_certificate /var/www/html/docker/ssl/fullchain.pem;
    ssl_certificate_key /var/www/html/docker/ssl/privkey.pem;
    
    # ... rest of configuration
}
```

### 5. Build & Deploy

```bash
# Copy production env
cp .env.production .env

# Build images
docker-compose build

# Start services
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force

# Optimize application
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Verify deployment
docker-compose ps
curl https://yourdomain.com/health
```

## 🔄 Automated Updates

### GitHub Actions CI/CD Pipeline

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Deploy to server
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.SERVER_HOST }}
        username: ${{ secrets.SERVER_USER }}
        key: ${{ secrets.SERVER_SSH_KEY }}
        script: |
          cd /opt/surat-app
          git pull origin main
          docker-compose down
          docker-compose up -d --build
          docker-compose exec -T app php artisan migrate --force
          docker-compose exec -T app php artisan cache:clear
```

## 🛡️ Security Hardening

### 1. Firewall Configuration

```bash
sudo ufw enable
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp   # SSH
sudo ufw allow 80/tcp   # HTTP
sudo ufw allow 443/tcp  # HTTPS
sudo ufw allow 3306/tcp --src 127.0.0.1  # MySQL (local only)
```

### 2. SSH Hardening

```bash
# Edit /etc/ssh/sshd_config
sudo nano /etc/ssh/sshd_config

# Changes:
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes
Port 2222  # Change from default

# Restart SSH
sudo systemctl restart sshd
```

### 3. Docker Security

```bash
# Update docker-compose.yml
services:
  app:
    security_opt:
      - no-new-privileges:true
    read_only: true
    tmpfs:
      - /tmp
      - /var/run
```

## 📊 Monitoring & Logging

### Application Logs

```bash
# View logs
docker-compose logs app

# Follow logs
docker-compose logs -f app

# Logs per service
docker-compose logs -f app db redis nginx
```

### System Monitoring

```bash
# Install Portainer for Docker monitoring
docker run -d \
  --name=portainer \
  -p 8000:8000 \
  -p 9000:9000 \
  -v /var/run/docker.sock:/var/run/docker.sock \
  portainer/portainer-ce

# Access at http://yourdomain.com:9000
```

### Health Checks

```bash
# Check health endpoint
curl https://yourdomain.com/health

# Setup monitoring script
cat > /opt/surat-app/docker/healthcheck.sh << 'EOF'
#!/bin/bash
while true; do
  STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://yourdomain.com/health)
  if [ $STATUS -ne 200 ]; then
    # Send alert (email, Slack, etc)
    echo "Health check failed: $STATUS"
  fi
  sleep 300  # Check every 5 minutes
done
EOF

chmod +x docker/healthcheck.sh
```

## 💾 Backup & Recovery

### Database Backup

```bash
# Create backup directory
mkdir -p /backups/surat-app

# Backup script
cat > /opt/surat-app/docker/backup.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/backups/surat-app"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Database backup
docker-compose exec -T db mysqldump \
  -u $DB_USERNAME -p$DB_PASSWORD \
  --single-transaction --quick --lock-tables=false \
  $DB_DATABASE > $BACKUP_DIR/db_$TIMESTAMP.sql

# Compress
gzip $BACKUP_DIR/db_$TIMESTAMP.sql

# Keep only 7 days of backups
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $BACKUP_DIR/db_$TIMESTAMP.sql.gz"
EOF

chmod +x docker/backup.sh

# Schedule daily backup via cron
(crontab -l 2>/dev/null; echo "0 2 * * * /opt/surat-app/docker/backup.sh") | crontab -
```

### Restore Database

```bash
# Restore from backup
gunzip -c /backups/surat-app/db_20240101_020000.sql.gz | \
  docker-compose exec -T db mysql -u root -p$DB_ROOT_PASSWORD $DB_DATABASE
```

## 🚀 Performance Optimization

### 1. PHP-FPM Tuning

Update `docker/php-fpm.conf`:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 10000
pm.process_idle_timeout = 60s
```

### 2. Database Optimization

```bash
# Run MySQL optimization
docker-compose exec db mysqlcheck -u root -p$DB_ROOT_PASSWORD -o -A

# Create indexes
docker-compose exec app php artisan optimize:idempotent
```

### 3. Asset Caching

```bash
# In nginx.conf
location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg)$ {
    expires 30d;
    add_header Cache-Control "public, immutable";
}
```

## 📈 Scaling

### Load Balancing

```yaml
version: '3.8'

services:
  # Multiple app instances
  app1:
    <<: *app-service
    container_name: surat-app-1
  
  app2:
    <<: *app-service
    container_name: surat-app-2
  
  app3:
    <<: *app-service
    container_name: surat-app-3

  nginx:
    # Configure upstream
    volumes:
      - ./docker/nginx-lb.conf:/etc/nginx/conf.d/default.conf
```

Update `docker/nginx-lb.conf`:

```nginx
upstream backend {
    server app1:9000;
    server app2:9000;
    server app3:9000;
}

server {
    location ~ \.php$ {
        fastcgi_pass backend;
        # ... rest of config
    }
}
```

## 🆘 Troubleshooting

### Services won't start

```bash
# Check logs
docker-compose logs

# Rebuild images
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Database connection failed

```bash
# Verify database is running
docker-compose exec db mysql -u root -p$DB_ROOT_PASSWORD -e "SELECT 1"

# Check network
docker network ls
docker inspect surat-app_surat-network
```

### High memory usage

```bash
# Check container stats
docker stats

# Adjust limits in docker-compose.yml
deploy:
  resources:
    limits:
      memory: 512M
    reservations:
      memory: 256M
```

## 📞 Support & Maintenance

- **Regular Updates**: `docker-compose pull && docker-compose up -d`
- **Database Maintenance**: Run `optimize` monthly
- **Log Rotation**: Configure logrotate for Docker logs
- **Security**: Keep Docker and dependencies updated

---

**Last Updated:** April 2026
**Version:** 1.0.0
