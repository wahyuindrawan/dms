# Docker Setup Guide - Surat App

## Prerequisites

- Docker & Docker Compose installed
- Linux/macOS or Windows with WSL2

## Initial Setup

### 1. Create Docker Network

Pastikan network `kinarya-networks` sudah ada. Jika belum, buatnya dengan:

```bash
docker network create kinarya-networks
```

Verifikasi network sudah terbuat:
```bash
docker network ls | grep kinarya-networks
```

### 2. Environment Configuration

Update `.env` file sesuai kebutuhan Anda:

```env
APP_ENV=local          # Ubah ke 'production' untuk production
APP_DEBUG=true         # Ubah ke false untuk production
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8085
```

### 3. Start Docker Containers

```bash
# Build image dan start container
docker-compose up -d

# Cek status container
docker-compose ps
```

### 4. Initialize Laravel Application

```bash
# Install dependencies (jika belum di-build image)
docker-compose exec app composer install

# Generate app key (jika belum ada)
docker-compose exec app php artisan key:generate

# Run database migrations
docker-compose exec app php artisan migrate

# Seed database (opsional)
docker-compose exec app php artisan db:seed
```

### 5. Access Application

- **Web**: http://localhost:8085
- **Container logs**: `docker-compose logs -f app`

## Common Commands

### View Logs

```bash
# Lihat logs semua services
docker-compose logs -f

# Lihat logs app saja
docker-compose logs -f app

# Lihat logs 100 baris terakhir
docker-compose logs --tail=100 app
```

### Database Commands

```bash
# Akses MySQL CLI
docker-compose exec app mysql -h mysql -u dev -p surat-app

# Run migrations
docker-compose exec app php artisan migrate

# Rollback migrations
docker-compose exec app php artisan migrate:rollback

# Fresh migrations + seeds
docker-compose exec app php artisan migrate:fresh --seed
```

### Artisan Commands

```bash
# Cache configuration
docker-compose exec app php artisan config:cache

# Clear all caches
docker-compose exec app php artisan cache:clear

# Generate storage link
docker-compose exec app php artisan storage:link

# Tinker (interactive shell)
docker-compose exec app php artisan tinker
```

### Composer Commands

```bash
# Install dependencies
docker-compose exec app composer install

# Update dependencies
docker-compose exec app composer update

# Show installed packages
docker-compose exec app composer show
```

## Troubleshooting

### Container Won't Start

```bash
# Check logs
docker-compose logs app

# Rebuild image
docker-compose down
docker-compose up -d --build
```

### Permission Denied Errors

Jika terjadi permission issues:

```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache

# Verify permissions
docker-compose exec app ls -la storage/
```

### Database Connection Failed

```bash
# Cek apakah MySQL service running
docker-compose ps

# Cek env variables
docker-compose exec app php artisan env

# Test database connection
docker-compose exec app php artisan tinker
# Di tinker: DB::connection()->getPdo()
```

### Health Check Failed

Tunggu 40 detik setelah container start (sesuai `start_period` di docker-compose.yml). Jika tetap failed:

```bash
# Rebuild container
docker-compose down
docker-compose up -d --build
```

## Production Deployment

### Pre-Deployment Checklist

```bash
# 1. Build production image
docker build -t surat-app:latest .

# 2. Update environment
# APP_ENV=production
# APP_DEBUG=false
# DEBUGBAR_ENABLED=false

# 3. Run migrations with backup
docker-compose exec app php artisan migrate --force

# 4. Cache configuration
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# 5. Optimize autoloader
docker-compose exec app composer install --optimize-autoloader --no-dev
```

### Monitoring

```bash
# Check container resource usage
docker stats surat-app

# View running processes
docker-compose top app

# Inspect container
docker-compose exec app top
```

## Network Configuration

### External Network

Container ini menggunakan external network `kinarya-networks`. Ini memungkinkan komunikasi dengan services lain di network yang sama.

Jika ingin menambah service lain ke network yang sama:

```yaml
services:
  other-service:
    networks:
      - kinarya-networks

networks:
  kinarya-networks:
    external: true
```

### Internal Networking

- **Container Name**: `surat-app`
- **Internal URL**: `http://surat-app:8080` (dari service lain)
- **External URL**: `http://localhost:8085`

## Backup & Restore

### Backup Database

```bash
docker-compose exec app mysqldump -h mysql -u dev -p surat-app > backup.sql
```

### Restore Database

```bash
docker-compose exec -T app mysql -h mysql -u dev -p surat-app < backup.sql
```

### Backup Volumes

```bash
# Backup storage
docker run --rm -v surat-app_storage:/data -v $(pwd):/backup \
  alpine tar czf /backup/storage-backup.tar.gz -C /data .
```

## Cleanup

### Stop Containers

```bash
docker-compose stop
```

### Remove Containers

```bash
docker-compose down
```

### Remove Everything (including volumes)

```bash
docker-compose down -v
```

**Warning**: Ini akan menghapus semua data di volumes!

## Docker Compose File Structure

```yaml
Services:
  - app: Main Laravel application (PHP-FPM + Nginx + Supervisor)

Volumes:
  - surat-app_logs: Laravel logs
  - surat-app_storage: File storage
  - surat-app_nginx_logs: Nginx access/error logs

Networks:
  - kinarya-networks: External network untuk multi-container setup
```

## Performance Tips

1. **Use .dockerignore** - Sudah ada di project root
2. **Enable OPcache** - Sudah dikonfigurasi di php.ini
3. **Gzip Compression** - Sudah enabled di nginx.conf
4. **Client Body Size** - Set ke 1G untuk file uploads besar
5. **Keep Image Size Small** - Using Alpine base image

## Security Considerations

1. ✅ Non-root user (appuser) untuk running services
2. ✅ Security headers di Nginx
3. ✅ Sensitive files protection (`.env`, `.htaccess`, dll)
4. ✅ Deny access ke sensitive directories
5. ❌ Jangan expose `.env` ke image (use volumes)

Untuk production:
- Ganti `APP_DEBUG=false`
- Ganti credentials di environment variables
- Use proper secrets management (Docker Secrets atau third-party)
- Enable HTTPS dengan SSL certificate
