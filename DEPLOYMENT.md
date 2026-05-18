# Deploying Nusagrade to Hostinger (no SSH — Git + hPanel only)

This guide is written for a Hostinger plan **without SSH access**. Everything
is done through the browser:

- **hPanel** (Git deployment, MySQL, Email, File Manager, phpMyAdmin)
- **Your local machine** (build assets, commit, push)
- **The browser** (one-shot `run-setup.php` to migrate + seed + cache)

The project lives entirely inside `public_html`. A root `.htaccess` rewrites
every request into `/public`, so you do **not** need to change the document
root in hPanel.

---

## Architecture at a glance

```
public_html/                 <-- Hostinger Git deploys here
├── .htaccess                <-- forwards / → /public, blocks .env, forces HTTPS
├── .env                     <-- you create this once via File Manager
├── app/  bootstrap/  config/  database/  resources/  routes/  storage/
├── vendor/                  <-- committed (no composer on server)
└── public/
    ├── .htaccess
    ├── index.php            <-- real document root
    ├── build/               <-- committed Vite assets (no npm on server)
    └── run-setup.php        <-- delete after first use
```

---

## 1. One-time local prep (do this once on your Mac)

You need **PHP**, **Composer**, and **Node** locally.

```bash
brew install node composer        # if not already installed

cd ~/Herd/Nusagrade

# Build production PHP deps (no dev packages, optimized autoloader)
composer install --no-dev --optimize-autoloader

# Build production JS/CSS assets
npm install
npm run build
```

> **Important:** the `--no-dev` flag means you'll need to run
> `composer install` again afterwards to bring dev tools back for local work.

Commit everything:

```bash
git add -A
git commit -m "Production deployment setup"
git push origin main
```

---

## 2. Hostinger setup in hPanel (do once)

### 2a. Create the MySQL database

1. hPanel → **Databases → Management** → **Create database**.
2. Use the credentials already in `.env.production.example`:
   - DB name: `u404974420_nusagrade`
   - DB user: `u404974420_nusagradedb`
   - Password: the one stored in your local notes
3. Note the actual prefix Hostinger gives you — if it differs from
   `u404974420_…`, update `.env` later to match.

### 2b. Create the email inbox

1. hPanel → **Emails → Email Accounts** → **Create email account**.
2. Address: `contact@nusagrade.com`
3. Set a password and save it.

### 2c. Connect Git

1. hPanel → **Advanced → Git** → **Create repository**.
2. Repository URL: your GitHub/GitLab repo URL.
3. Branch: `main`
4. Install path: `public_html` (leave it empty / use the default)
5. Click **Create**. Hostinger clones the repo into `public_html`.
6. (Optional but recommended) Copy the webhook URL Hostinger shows and paste
   it into your GitHub repo: **Settings → Webhooks → Add webhook**. Content
   type = `application/json`. Pushes to `main` will now auto-deploy.

If you don't use the webhook, click **Deploy** in hPanel after each `git push`.

### 2d. Force HTTPS at the panel level

hPanel → **Security → SSL** → enable **Force HTTPS** (in addition to the
`.htaccess` rule). Make sure the free SSL certificate is **Active** for
both `nusagrade.com` and `www.nusagrade.com`.

---

## 3. Create `.env` on the server (File Manager)

1. hPanel → **Files → File Manager** → navigate to `public_html/`.
2. Right-click `.env.production.example` → **Copy** → rename copy to `.env`.
3. Right-click `.env` → **Edit**.
4. Fill in these values:

   | Key | Value |
   |---|---|
   | `APP_KEY` | leave blank — `run-setup.php` will generate it |
   | `DB_PASSWORD` | the MySQL password from step 2a |
   | `MAIL_PASSWORD` | the inbox password from step 2b |
   | `ADMIN_EMAIL` | `contact@nusagrade.com` (or whatever you want) |
   | `ADMIN_PASSWORD` | a strong password — you'll log in with this |
   | `SETUP_TOKEN` | any long random string (40+ chars) |

5. Save the file.

---

## 4. Make `storage/` and `bootstrap/cache/` writable

1. In File Manager, navigate to `public_html/storage`.
2. Right-click `storage` → **Permissions** (or **CHMOD**) → set numeric value
   `775` and check **Apply to all subfolders and files**. Save.
3. Repeat for `public_html/bootstrap/cache`.

(Some Hostinger plans already use the right defaults — if you skip this and
later see a blank page, come back and do it.)

---

## 5. Run the one-shot setup

In your browser, open:

```
https://nusagrade.com/run-setup.php?token=PASTE_YOUR_SETUP_TOKEN_HERE
```

You should see a plain-text log with `OK` next to each command:

```
Running: php artisan key:generate
  -> OK

Running: php artisan migrate
  -> OK

Running: php artisan db:seed
  -> OK
...
```

If anything says `ERROR`, see [Troubleshooting](#troubleshooting) below.

---

## 6. DELETE `run-setup.php`

This file can re-run migrations and re-seed the database. Don't leave it on
the server.

1. hPanel → File Manager → `public_html/public/run-setup.php` → **Delete**.

Confirm by visiting `https://nusagrade.com/run-setup.php` — you should get
a 404.

---

## 7. Smoke-test the site

- [ ] `https://nusagrade.com` loads the homepage
- [ ] `https://nusagrade.com/up` returns a 200 (Laravel health endpoint)
- [ ] `https://www.nusagrade.com` redirects to `https://nusagrade.com`
- [ ] `https://nusagrade.com/.env` returns 403 / 404
- [ ] `https://nusagrade.com/articles` loads
- [ ] `/login` → log in with `ADMIN_EMAIL` + `ADMIN_PASSWORD`
- [ ] `/admin/articles` is accessible (admin guard works)
- [ ] Submit the contact form — check `contact@nusagrade.com` inbox for the email

---

## 8. Future deploys

For any future change:

```bash
# On your Mac
# ...make your code changes...

# If JS/CSS changed:
npm run build

# If PHP dependencies changed (rare):
composer install --no-dev --optimize-autoloader

# If there are new migrations, you'll need to re-enable run-setup.php — see below.

git add -A
git commit -m "describe your change"
git push origin main
```

Then in hPanel → Git → click **Deploy** (or wait for the webhook).

### Re-running migrations on later deploys

Since you deleted `run-setup.php`, when you push a new migration you have
two options:

**Option A — temporarily restore the runner.** Locally:

```bash
git checkout HEAD~ -- public/run-setup.php   # or git restore from history
git commit -m "Restore setup runner for migration"
git push origin main
```

Then hit `https://nusagrade.com/run-setup.php?token=…` and delete it again.

**Option B — run migrations directly in phpMyAdmin.** Open hPanel → MySQL
Databases → phpMyAdmin → run the SQL from the new migration manually. Only
sane for very small migrations.

**Option A is recommended.**

### Clearing the cache after deploys

The cached config/route/view files in `bootstrap/cache/` are *also* deployed
by Git (since they're text files), but when your code changes the cache
needs rebuilding. The cleanest way: keep `run-setup.php` available behind
the SETUP_TOKEN for any deploy that changes config/routes/views, then
delete it again.

---

## Troubleshooting

| Symptom | Likely cause / fix |
|---|---|
| Blank white page | `storage/` not writable → set 775 in File Manager |
| "No application encryption key has been specified" | `APP_KEY` is empty and `run-setup.php` failed before generating it. Re-run setup, or in File Manager edit `.env` and set `APP_KEY=` then re-run setup. |
| 500 Internal Server Error | Check `public_html/storage/logs/laravel.log` via File Manager. Usually wrong DB credentials. |
| Vite assets 404 | `public/build/` not committed. Run `npm run build` locally, commit, push, redeploy. |
| Contact form emails not delivered | Wrong SMTP password, or Hostinger blocked port 465. Try port `587` with `MAIL_ENCRYPTION=tls` in `.env`, then re-run setup. |
| Sessions don't persist after login | `SESSION_DOMAIN` set incorrectly — leave it as `null` in `.env`. |
| "Route [login] not defined" | `route:cache` ran before all routes were registered. In File Manager, delete `bootstrap/cache/routes-v7.php` and re-hit `run-setup.php`. |
| Admin user wasn't created | `ADMIN_EMAIL` or `ADMIN_PASSWORD` was blank when you ran setup. Fill them in `.env`, then re-run setup. |
| Setup runner returns 404 | Token in URL doesn't match `SETUP_TOKEN` in `.env`. Double-check there are no extra spaces or quotes. |
