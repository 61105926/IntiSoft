# Coolify Deployment Guide

## Environment Variables Required

Set these in your Coolify application environment variables:

```env
APP_KEY=base64:your-generated-key-here
APP_URL=https://your-domain.com
DB_PASSWORD=your-secure-database-password
DB_DATABASE=folcklore
DB_USERNAME=root
```

## Deployment Steps

1. **Connect Repository**: Connect your Git repository to Coolify
2. **Configure Build**:
   - Dockerfile: `Dockerfile`
   - Docker Compose: `docker-compose.yml`
3. **Set Environment Variables**: Add the variables listed above
4. **Deploy**: Click deploy in Coolify

## Database Setup

The MySQL database will be automatically created with the docker-compose configuration. Make sure to set a strong `DB_PASSWORD` in your environment variables.

## Post-Deployment

After successful deployment, the application will:
- Run database migrations automatically
- Cache configurations for optimal performance
- Be available on port 80

## Volumes

The following data will persist between deployments:
- MySQL data: `/var/lib/mysql`
- Laravel storage: `/var/www/storage`
- Redis data: `/data`

## Services Included

- **App**: Laravel application with Nginx and PHP-FPM
- **MySQL**: Database server
- **Redis**: Cache and session storage