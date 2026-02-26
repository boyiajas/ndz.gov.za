# NDZ Municipality Portal

Front-facing SPA built with Vue 3 + Vite (frontend folder) and served by Laravel. This README explains how to run the app in hot-reload mode, build for production, and work with assets.

## Prerequisites
- PHP 8.x + Composer
- Node 18+ and npm

## Local development (hot reload)
1) Install JS deps once: `npm --prefix frontend install`
2) Start Vite dev server (served to Laravel with live reload): `npm --prefix frontend run dev -- --host`
3) In another terminal, run Laravel: `php artisan serve --port=8001`
4) Browse http://127.0.0.1:8001/ — the blade will auto-load from the Vite dev server when it’s running (controlled by `APP_ENV=local` and `FRONTEND_DEV=true` in `.env`, defaults are already set in blade).

## Production build
- Build static assets directly into `public/assets`: `npm --prefix frontend run build`
- Laravel blade auto-picks the latest `index-*.js/css` in `public/assets`.

## Useful paths
- Frontend entry: `frontend/src/main.js`
- Vue router/views: `frontend/src/router`, `frontend/src/views`
- Styles: `frontend/src/assets/main.css`
- SPA blade shell: `resources/views/app.blade.php`
- Laravel routes: `routes/web.php` (SPA catch-all) and `routes/api.php`

## Dev server toggle
- Dev server URL: `FRONTEND_DEV_SERVER` (default `http://localhost:5173`)
- Enable/disable using `FRONTEND_DEV=true/false` in `.env` (only in `APP_ENV=local`)

## Assets
- Built assets: `public/assets`
- Static files served directly: `frontend/public` (copied/served by Vite)

## Testing
- PHP tests: `php artisan test`
- (Add frontend tests if/when needed.)

## Notes
- Avoid running `npm run build` during active frontend work; prefer `npm run dev -- --host` for live updates.
- If the dev server isn’t running, the blade falls back to the latest built bundle automatically.
