# GVSPACE production deployment

The production stack contains Caddy (HTTPS/reverse proxy), Next.js, WordPress and MySQL. Only Caddy exposes ports to the internet. A successful CI run for `main` triggers deployment of that exact tested commit.

## 1. DNS

At the DNS provider, create these records for `gvspace.com`, `gvspace.ua` and the legacy `gvspace.com.ua` domain, then wait for them to resolve. The legacy domain remains pointed at the server so HTTPS requests can be redirected to `gvspace.ua`.

| Type | Name  | Value            |
| ---- | ----- | ---------------- |
| A    | `@`   | `173.242.58.232` |
| A    | `www` | `173.242.58.232` |
| A    | `cms` | `173.242.58.232` |

The `cms` record is required only in the `gvspace.com` zone. Do not remove existing mail records.

Do not enable a proxy/CDN until the first HTTPS certificates have been issued.

## 2. Prepare Ubuntu 24.04

Log in once as root, update the server, create an unprivileged deployment user and install Docker from Docker's official Ubuntu repository. Use the current official instructions at <https://docs.docker.com/engine/install/ubuntu/> rather than an unofficial convenience script.

After Docker is installed:

```bash
adduser deploy
usermod -aG docker deploy
install -d -m 700 -o deploy -g deploy /home/deploy/.ssh
ufw allow OpenSSH
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 443/udp
ufw enable
```

Add your public SSH key to `/home/deploy/.ssh/authorized_keys`, set mode `600`, then verify a new login as `deploy` before closing the root session. Disable root/password SSH login only after key login has been tested.

## 3. Clone and configure

As `deploy`:

```bash
git clone https://github.com/Bohdan-ybf/gvspace.git /home/deploy/gvspace
cd /home/deploy/gvspace
cp .env.production.example .env.production
chmod 600 .env.production
```

Edit `.env.production`. Set `SITE_DOMAIN=gvspace.com`, `UK_SITE_DOMAIN=gvspace.ua`, and `LEGACY_UK_SITE_DOMAIN=gvspace.com.ua`. Keep both Ukrainian domains pointed at the server: Caddy serves `gvspace.ua` and permanently redirects `gvspace.com.ua` (including each path and query string) to it. Use two different long random database passwords. Confirm the real domains and email. Keep `SITE_INDEXING_ENABLED=false` throughout development and migration; enable it only after the final content, redirect and SEO review.

For a non-public preview, create `deploy/auth.caddy` from `deploy/auth.caddy.example` and replace the placeholder with a hash produced by `caddy hash-password`. The real file is ignored by Git. Remove the `import /etc/caddy/auth.caddy` line and its Compose mount when public HTTP authentication is no longer required.

Start the stack:

```bash
docker compose --env-file .env.production -f compose.prod.yml up -d --build
docker compose --env-file .env.production -f compose.prod.yml ps
docker compose --env-file .env.production -f compose.prod.yml logs --tail=100 proxy frontend wordpress database
```

Open `https://cms.gvspace.com/wp-admin/install.php`, complete WordPress setup, install **WPGraphQL**, and activate both **WPGraphQL** and **GVSPACE Core**. Never expose MySQL port 3306.

## 4. Automatic deployment from GitHub

Create a dedicated SSH key for GitHub Actions. Put its public key in `/home/deploy/.ssh/authorized_keys`. In GitHub, open **Settings → Environments → New environment**, create `production`, then add these environment secrets:

| Secret               | Value                                                                                         |
| -------------------- | --------------------------------------------------------------------------------------------- |
| `DEPLOY_HOST`        | `173.242.58.232`                                                                              |
| `DEPLOY_USER`        | `deploy`                                                                                      |
| `DEPLOY_PATH`        | `/home/deploy/gvspace`                                                                        |
| `DEPLOY_SSH_KEY`     | Complete private deployment key                                                               |
| `DEPLOY_KNOWN_HOSTS` | Output of `ssh-keyscan -H 173.242.58.232` after independently checking the server fingerprint |

The `Deploy production` workflow runs only after `CI` succeeds and deploys the tested commit. The normal workflow is:

```bash
git status
git add <files>
git commit -m "feat: describe the change"
git push origin main
```

Watch both workflows in the repository's **Actions** tab. Do not commit `.env.production`, database dumps, private keys or passwords.

## 5. WordPress migration and backups

WordPress content is not stored in Git. Before switching DNS, export the current database and `wp-content/uploads`, copy both backups to the server, import them, and replace the old WordPress URL with `https://cms.gvspace.com` using WP-CLI. Do the URL replacement with WP-CLI because serialized WordPress data must not be changed with plain SQL.

Back up production regularly:

- a daily MySQL dump;
- a daily archive of the `gvspace_wordpress_data` Docker volume;
- encrypted off-server storage;
- a periodic restore test.

## 6. Release checklist

Verify `/uk`, `/en`, contact pages, images, GraphQL data, WordPress login, mobile layout, redirects and HTTPS. Then set `SITE_INDEXING_ENABLED=true` in `.env.production` and redeploy:

```bash
sh deploy/deploy.sh
```

Useful rollback command (replace the revision with a previously successful commit SHA):

```bash
sh deploy/deploy.sh <commit-sha>
```
