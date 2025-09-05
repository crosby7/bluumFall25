### Database Setup
1. Install Docker
2. Use the .env.example and fill out the 'DB' related values
3. Navigate to server-laravel folder, then run:
```bash
docker compose up -d
```
4. Docker container will take a minute to initialize
5. Seed the db 
```bash
php artisan migrate:fresh --seed
```