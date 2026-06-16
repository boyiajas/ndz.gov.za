# NDZ Website Context

## Purpose
- This repository is the NDZ Municipality public website and citizen portal.
- Backend is Laravel 12 and mostly serves the SPA shell plus auth API endpoints.
- Frontend is a separate Vue 3 + Vite app in `frontend/` and contains almost all user-facing website logic.

## Architecture
- Laravel route entrypoint: `routes/web.php`
  - Catch-all route serves `resources/views/app.blade.php` for every non-`/api` path.
- Laravel API routes: `routes/api.php`
  - `GET /api/health`
  - `POST /api/register`
  - `POST /api/login`
  - Authenticated with Sanctum:
    - `GET /api/user`
    - `POST /api/logout`
- Auth controller: `app/Http/Controllers/Api/AuthController.php`
  - Simple register/login/logout token flow.
- SPA shell: `resources/views/app.blade.php`
  - In local env it prefers the Vite dev server from `FRONTEND_DEV_SERVER`.
  - If dev server is unavailable it falls back to latest built assets in `public/assets`.

## Frontend App
- Frontend root: `frontend/`
- Entry: `frontend/src/main.js`
- App shell: `frontend/src/App.vue`
  - Global layout is `AppNavbar` + routed view + `AppFooter`.
- Router: `frontend/src/router/index.js`
- Auth store: `frontend/src/stores/auth.js`
- Axios client: `frontend/src/api/axios.js`
  - Uses `VITE_API_BASE_URL` or defaults to `http://localhost:8000`
  - Reads bearer token from `localStorage`
  - Redirects to `/login` on 401

## Runtime Model
- This is effectively a content-driven SPA.
- Most pages are static or semi-static Vue SFCs with hardcoded data arrays and image references.
- Very little content is currently driven from Laravel models or database-backed CMS data.
- Current live logic is mainly:
  - Vue routing
  - Login/register/logout
  - Protected dashboard route
  - Expand/collapse style interactions in tender/quote pages
  - Image modal/gallery interactions on the home/gallery areas

## Main Route Map
- `/` -> `HomeView.vue`
- `/login` -> `LoginView.vue`
- `/register` -> `RegisterView.vue`
- `/dashboard` -> `DashboardView.vue` and requires auth
- `/mayors-office` -> `MayorsOfficeView.vue`
- `/municipal-manager` -> `MunicipalManagerView.vue`
- `/meet-speaker` -> `MeetSpeakerView.vue`
- `/councillors` -> `CouncillorsView.vue`
- `/tourism` -> `TourismView.vue`
- `/corporate-services` -> `CorporateServicesView.vue`
- `/community-services` -> `CommunityServicesView.vue`
- `/budget-treasury` -> `BudgetTreasuryView.vue`
- `/public-works` -> `PublicWorksView.vue`
- `/news` -> `NdzNewsView.vue`
- `/open-tenders` -> `OpenTendersView.vue`
- `/closed-tenders` -> `ClosedTendersView.vue`
- `/open-quotes` -> `OpenQuotesView.vue`
- `/closed-quotes` -> `ClosedQuotesView.vue`
- `/contact` -> `ContactView.vue`
- `/gallery` -> `EventGalleryView.vue`
- `/documents` -> `DocumentsView.vue`

## Content Areas
- `HomeView.vue`
  - Hero video from `public/hero-video/hero-video.mp4`
  - Quick links for careers, tenders, events, tourism, notices
  - Mayor section
  - Municipal manager section
  - Events/gallery modal interaction
  - Recent news cards
  - Community/ward and other promotional sections
  - Large file and likely a major maintenance hotspot
- `DocumentsView.vue`
  - Large list of municipal document categories
  - Most links are placeholders (`#`)
- Tender/quote views
  - Use hardcoded arrays for procurement listings and downloadable file names
  - Many download links are placeholders
- Department/about pages
  - Mostly profile/content pages with cards, contact info, and images
- `DashboardView.vue`
  - Protected mock portal dashboard
  - Uses auth state but currently shows mostly static stats/activity

## Page-By-Page Inventory
- `frontend/src/views/HomeView.vue`
  - Main landing page and the largest view in the app
  - Contains hero video, quick links, mayor section, manager section, news cards, stats, emergency numbers, weather widget, tourism gallery modal, and ward/community messaging
  - Uses hardcoded arrays for news, stats, emergency numbers, tourism images, and band cards
  - References top-level public assets such as `/mayor.jpg`, `/manager.jpg`, `/tourism-*.jpg`, `/band-*.jpg`
- `frontend/src/views/LoginView.vue`
  - Simple login form
  - Calls Pinia auth store `login()` and redirects to dashboard or requested route
- `frontend/src/views/RegisterView.vue`
  - Simple registration form
  - Calls Pinia auth store `register()` and redirects to dashboard on success
- `frontend/src/views/DashboardView.vue`
  - Auth-protected citizen portal page
  - Real auth user data for name/email, but stats/activity/quick links are static placeholders
- `frontend/src/views/MayorsOfficeView.vue`
  - Static mayor biography/profile page
- `frontend/src/views/MunicipalManagerView.vue`
  - Static municipal manager profile page
- `frontend/src/views/MeetSpeakerView.vue`
  - Static speaker profile page
- `frontend/src/views/CouncillorsView.vue`
  - Hardcoded councillor roster with names, roles, emails, and images from `/img/councillors/...`
  - Good candidate for future data extraction if council membership changes often
- `frontend/src/views/TourismView.vue`
  - Tourism content page with supporting imagery and gallery link
- `frontend/src/views/CorporateServicesView.vue`
  - Static department overview with leadership/profile content and placeholder document actions
- `frontend/src/views/CommunityServicesView.vue`
  - Static department overview with leadership/profile content
- `frontend/src/views/BudgetTreasuryView.vue`
  - Static department overview with leadership/profile content
- `frontend/src/views/PublicWorksView.vue`
  - Static department overview with leadership/profile content and placeholder downloads
- `frontend/src/views/NdzNewsView.vue`
  - Static list of municipality news cards
  - Current card content is hardcoded in the component
- `frontend/src/views/EventGalleryView.vue`
  - Static image gallery
  - Uses files in `/public/img/gallery/`
  - No lightbox logic on this page; only hover scaling
- `frontend/src/views/DocumentsView.vue`
  - Grid of document categories and year buckets
  - Almost all links are placeholder `#` links
  - Best treated as a navigation/catalog page until real document URLs are supplied
- `frontend/src/views/OpenTendersView.vue`
  - Hardcoded tender table with expandable detail rows
  - Includes contact people, dates, file metadata, and fake download buttons
  - Filters/pagination are presentational only
- `frontend/src/views/ClosedTendersView.vue`
  - Hardcoded archive-style tender listing
  - Download/pagination patterns are mostly static
- `frontend/src/views/OpenQuotesView.vue`
  - Hardcoded quote table with expandable detail rows
  - Similar structure to open tenders
- `frontend/src/views/ClosedQuotesView.vue`
  - Hardcoded archive-style quote listing
- `frontend/src/views/ContactView.vue`
  - Mixed real and placeholder contact details
  - Contains a non-functional contact form and embedded Google Map iframe
  - Includes external image usage for a fake reCAPTCHA logo
- `frontend/src/views/NotFoundView.vue`
  - Simple SPA 404 page

## Shared Layout And Navigation
- `frontend/src/components/AppNavbar.vue`
  - Primary navigation source for public information architecture
  - Contains many dropdown items that are placeholders or not routed
  - Also controls login/logout display and portal access
- `frontend/src/components/AppFooter.vue`
  - Footer contacts, service emails, document shortcuts, and newsletter UI
  - Newsletter form is not wired to any backend
- `frontend/src/App.vue`
  - Mounts navbar/footer and applies route transition
  - Scroll reset logic happens here after route changes

## Maintenance Map
- If the request is "change homepage content":
  - Start in `frontend/src/views/HomeView.vue`
  - Then check related assets under `public/`
- If the request is "fix menu/link structure":
  - Start in `frontend/src/components/AppNavbar.vue`
  - Then confirm matching routes in `frontend/src/router/index.js`
- If the request is "fix footer/contact/newsletter content":
  - Start in `frontend/src/components/AppFooter.vue`
- If the request is "update a department page":
  - Edit the specific Vue view in `frontend/src/views/`
- If the request is "update councillors":
  - Edit `frontend/src/views/CouncillorsView.vue`
  - Verify image files under `public/img/councillors/`
- If the request is "update tenders/quotes":
  - Edit `OpenTendersView.vue`, `ClosedTendersView.vue`, `OpenQuotesView.vue`, or `ClosedQuotesView.vue`
  - Current listings are local arrays, not API-fed
- If the request is "add real downloads/documents":
  - Likely update both `DocumentsView.vue` and the relevant public file locations or backend file-serving approach
- If the request is "fix login/register/dashboard":
  - Inspect `frontend/src/stores/auth.js`, `frontend/src/api/axios.js`, `routes/api.php`, and `app/Http/Controllers/Api/AuthController.php`
- If the request is "fix build/dev asset loading":
  - Inspect `resources/views/app.blade.php`, `frontend/package.json`, and `frontend/src/main.js`
- If the request is "change global look and feel":
  - Start in `frontend/src/assets/main.css`
  - Then inspect component-scoped styles inside affected views/components

## Placeholder And Risk Map
- Placeholder links are widespread in:
  - `AppNavbar.vue`
  - `AppFooter.vue`
  - `DocumentsView.vue`
  - procurement/tender/quote pages
  - career center links
- Presentational-only UI exists in:
  - tender/quote filters
  - tender/quote pagination
  - newsletter subscribe form
  - contact form
  - fake reCAPTCHA block on contact page
- Mixed-quality data exists in:
  - contact page phone/address values
  - dashboard stats/activity
  - news cards
  - councillor records
- External dependencies to watch:
  - weather widget behavior in `HomeView.vue`
  - embedded Google Maps iframe in `ContactView.vue`
  - remote placeholder images such as `picsum.photos` in home news cards

## Public Asset Conventions
- Root-level assets are referenced directly like `/mayor.jpg` or `/tourism-1.jpg`
- Structured image folders also exist, especially under `/img/...`
- Before renaming or moving assets, search for direct string references in Vue files because many are not imported through Vite

## Suggested Upgrade Paths
- If the municipality wants non-technical staff to update content:
  - Introduce backend-managed data or a CMS-like admin flow
  - Highest-value first targets are news, documents, tenders, quotes, councillors, and contact info
- If the site needs working forms:
  - Build backend endpoints and validation for contact and newsletter submissions
- If the site needs real procurement publishing:
  - Replace hardcoded tender/quote arrays with database tables and admin CRUD

## Current UX and Data Reality
- The website visually presents many municipality sections, but much of the data is embedded directly in Vue files.
- Many links in navbar, footer, documents, careers, and procurement sections are still placeholders.
- There is no evidence of a full backend CMS, tender management system, document library, or partner/news CRUD.
- If future work needs dynamic content, expect to build new API endpoints and replace hardcoded arrays in Vue.

## Key Assets
- Public assets and images live under `public/`
- Notable directories:
  - `public/img`
  - `public/hero-video`
- Some current untracked files exist:
  - `public/img/band-mission.jpg`
  - `public/img/band-priorities.jpg`
  - `public/img/band-tourism.jpg`
- Do not remove untracked assets unless the user asks.

## Development Commands
- Backend dev server: `php artisan serve --port=8001`
- Frontend dev server: `npm --prefix frontend run dev -- --host`
- Frontend production build: `npm --prefix frontend run build`
- PHP tests: `php artisan test`

## Working Rules For Future Codex Sessions
- Read this file first before making assumptions about the NDZ system.
- Assume frontend changes usually belong in `frontend/src/...`, not in Laravel Blade, unless the task is about the SPA shell or API.
- Preserve the existing municipal content structure and route names unless the user asks for IA changes.
- When fixing broken content, first verify whether the target is:
  - a hardcoded Vue placeholder
  - a real route
  - a missing backend endpoint
  - a missing public asset
- For auth issues, inspect:
  - `frontend/src/stores/auth.js`
  - `frontend/src/api/axios.js`
  - `routes/api.php`
  - `app/Http/Controllers/Api/AuthController.php`
- For rendering/build issues, inspect:
  - `resources/views/app.blade.php`
  - `frontend/package.json`
  - `frontend/src/main.js`
- For content updates, prefer editing the specific view component first; most pages are standalone and not shared through a CMS.

## Constraints To Remember
- The repo may contain user work in progress; do not revert unrelated changes.
- Placeholder links are common and should not be assumed to be bugs unless the task specifically requires completion.
- Because the frontend is mostly hardcoded, line-level content changes can have immediate user-facing impact; verify route/component pairing before editing.
