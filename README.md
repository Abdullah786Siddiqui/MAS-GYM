# 🏋️ MAS GYM

A simple, no-nonsense workout tracker built for a small group of friends. Plan your weekly split, log your sets, and watch your numbers go up.

## ✨ Features

- **Weekly Schedule Builder** — assign exercises to each day by muscle group (Chest, Back, Shoulders, Biceps, Triceps, Legs)
- **Daily Workout Logging** — track sets, reps, and weight for every session
- **Personal Records (PRs)** — automatically detects and saves your best lifts
- **Workout History** — browse past sessions and track progress over time
- **Clean, Fast UI** — built with Inertia.js, so it feels like a single-page app without the API overhead

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 |
| Frontend | Vue 3 (Composition API) + Inertia.js |
| Styling | Tailwind CSS |
| Database | PostgreSQL (Supabase) |
| Auth | Laravel Breeze |

## 🚀 Getting Started (Local Development)

**Requirements:** PHP 8.3+, Composer, Node.js, MySQL/PostgreSQL

```bash
# Clone the repo
git clone https://github.com/Abdullah786Siddiqui/MAS-GYM.git
cd MAS-GYM

# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then run migrations
php artisan migrate

# Run the dev servers (two terminals)
php artisan serve
npm run dev
```

Visit `` 🎉