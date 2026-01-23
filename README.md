# ByteLog: CMS (Laragon + Google Drive)

## Order of operations to avoid install blockers
1) Extract zip to `C:\laragon\www\bytelog-cms` (or your chosen name).
2) Open a terminal in that folder.
3) Copy env **first**:
   copy .env.example .env

4) Create the MySQL database before installs (Laragon Menu → MySQL → HeidiSQL → Create database) name: `bytelog`.
5) Configure `.env` for that DB:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bytelog
   DB_USERNAME=root    
   DB_PASSWORD=     

6) Install backend deps:
   composer install

7) Generate app key:
   php artisan key:generate

8) Install frontend deps:
   npm install

9) Migrate:
   php artisan migrate
   php artisan db:seed   # sample data

## Run the app
- Open two terminals (from the project root):
  # Terminal 1
  php artisan serve    
  # Terminal 2
  npm run dev
- Once both are running, visit the URL: http://localhost:8000/login to proceed with login.

   Demo Accounts (must do 'php artisan db:seed' first):
      Admin: admin@admin.com / admin123
      User: user@user.com / password
