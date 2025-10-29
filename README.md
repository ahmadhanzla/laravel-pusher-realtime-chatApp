## Installation & Setup

Follow the steps below to set up the project on your local environment.
Make sure you have the required tools installed before starting.

Prerequisites
Before cloning the project, make sure you have the following installed:
- [PHP 8.2+]
- [Composer]
- Node.js & NPM
- Database [MySQL or SQLite]
- [Pusher Account (Free)]
- [Real-time event broadcasting]
⚠️ These are required to run Laravel and handle real-time events using Pusher.

## Step 01: Clone the Repository

Clone this project from GitHub and navigate into the directory:

git clone https://github.com/yourusername/laravel-pusher-realtime-chatApp.git
cd laravel-pusher-realtime-chatApp

## Step 02: Install Dependencies

Install Dependencies

- composer install

Then install frontend dependencies (Tailwind, Vite, etc.) using NPM:

- npm install && npm run dev

💡 Tip: If npm run dev fails, try deleting node_modules and package-lock.json, then reinstall.

## Step 03: Create Environment File

Duplicate the example .env file to create your environment configuration:

- cp .env.example .env

## Step 04: Generate Application Key
- php artisan key:generate

## Step 05: Run Migrations

- php artisan migrate

## Step 06: Setup Pusher Credentials

Create a free account on Pusher.com and get your app credentials.
Then, update your .env file:

BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

📘 Reference: Laravel Broadcasting with Pusher Docs

## Step 07: Compile Frontend Assets

Run the following to build and watch frontend assets:

- npm run dev

## Step 08: Start Development Server

- php artisan serve

Visit your app at:
👉 http://127.0.0.1:8000

💡 Usage

Register or log in to the app.
Open two browsers or accounts to test live chat in real-time.
Start sending messages — you’ll see them instantly appear without refresh thanks to Pusher + Laravel Echo.

## Folder Highlights

app/
 ├── Events/MessageSent.php           # Event triggered on sending a message
 ├── Listeners/SendChatNotification.php # Listener that broadcasts events
resources/views/
 └── chat.blade.php                   # Chat UI view
routes/
 └── web.php                          # App routes configuration


🧑‍💻 Author

Ahmad Hanzla
🚀 Backend-Focused Full Stack Developer | Laravel, PHP & Node.js
📫 [LinkedIn] (https://www.linkedin.com/in/ahmadhanzla/)
 • GitHub