🚀 How to Run
Clone project: git clone [URL]

Install dependencies: composer install และ npm install && npm run build

Setup Env: cp .env.example .env แล้วไปตั้งค่า DB ในไฟล์ .env

Generate Key: php artisan key:generate

Migrate Database: php artisan migrate --seed

Link Storage: php artisan storage:link (สำคัญมากสำหรับการแสดงผลรูปสลิป)

Start Server: php artisan serve