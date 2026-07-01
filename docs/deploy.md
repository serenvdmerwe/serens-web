# Deploying SerensWeb to production (serensweb.com on Hostinger)

Moving the site from the local Docker stack to Hostinger is not one upload. It is
three separate parcels plus a fix step. Getting this mental model right avoids
the usual failures (a broken database, missing images, a site that redirects
back to `serensweb.test`).

## The three parcels

| Parcel | What it holds | Where it lives locally | How it travels |
|--------|---------------|------------------------|----------------|
| Theme | Templates, CSS, JS, portrait, og-card, CV, the Florida map demo | `wordpress/wp-content/themes/serensweb-child/` | Theme zip, uploaded in wp-admin or dropped into `wp-content/themes/` |
| Media library | Uploaded images such as the 33figs example | `wordpress/wp-content/uploads/` | Copy the folder into `public_html/wp-content/uploads/` |
| Database | Pages, posts, settings, and the references that point at the media | a `.sql` dump at the repo root | Import into MySQL through phpMyAdmin, then search-replace the domain |

Key point: a `.sql` file is a database dump, a text file full of SQL commands.
WordPress never reads `.sql` files from disk. Dropping one into the file manager
does nothing. It has to be imported into the database, and a public dump sitting
in `public_html` is a security leak, so it gets deleted afterwards.

## Building the theme zip

The zip must contain exactly one top-level folder, `serensweb-child/`. A zip that
unpacks files loose, or with an extra nested folder, is rejected by the WordPress
uploader.

From the repo root on Windows PowerShell:

```powershell
$src = "C:\dev\serens-web\wordpress\wp-content\themes\serensweb-child"
$dest = "C:\dev\serens-web\serensweb-child.zip"
if (Test-Path $dest) { Remove-Item $dest -Force }
Compress-Archive -Path $src -DestinationPath $dest -CompressionLevel Optimal
```

Verify the structure before uploading (every entry should sit under
`serensweb-child/`):

```powershell
Add-Type -AssemblyName System.IO.Compression.FileSystem
$zip = [System.IO.Compression.ZipFile]::OpenRead("C:\dev\serens-web\serensweb-child.zip")
$zip.Entries | ForEach-Object { $_.FullName.Split('/')[0] } | Sort-Object -Unique
$zip.Dispose()
```

## Runbook

Do these in order.

### 0. Prerequisites (hPanel)

hPanel is Hostinger's main control panel at https://hpanel.hostinger.com, reached
by logging in and clicking Manage on the hosting plan. The File Manager and
phpMyAdmin are tools launched from inside it.

- `serensweb.com` is added as the website on this hosting account and its DNS
  points to Hostinger.
- SSL is active for the domain (hPanel, Security, SSL). The database stores every
  URL as `https://`, so SSL must be live or the site throws mixed-content and
  redirect errors.

### 1. Theme

Upload `serensweb-child.zip` via wp-admin, Appearance, Themes, Add New, Upload.
Alternatively drop the unzipped `serensweb-child` folder into
`public_html/wp-content/themes/`. The parent theme `twentytwentyfive` must also be
present; Hostinger's WordPress usually ships it, and if not it installs from the
theme directory.

### 2. Media

Copy the local `wordpress/wp-content/uploads/` contents into
`public_html/wp-content/uploads/`, preserving the year and month folders (for
example `uploads/2026/06/33figs-src.png`). The database points at these files by
absolute path, so if the folder is missing the 33figs project image shows as
broken even after the database import.

### 3. Database import

hPanel, Databases, phpMyAdmin. Open the database tied to the WordPress install,
then Import, choose the `.sql` dump, and run it.

- Table prefix must match. The dump uses `wp_`. Open Hostinger's
  `public_html/wp-config.php` and confirm `$table_prefix = 'wp_';`. If it differs,
  the site cannot find the imported tables; set it to `wp_`.
- Do not upload the local `wp-config.php`. Keep Hostinger's file, which already
  holds the correct database name, user, and password for their server. The local
  one reads credentials from Docker environment variables and will not work.
- Importing overwrites whatever content Hostinger's auto-install created. That is
  the intent for a migration, but it is a replace, not a merge.

### 4. Search-replace the domain (serialization-safe)

The dump references `https://serensweb.test` and it must become
`https://serensweb.com`. The two strings are different lengths, and WordPress
stores some settings as serialized PHP that records string byte-lengths, so a
plain find-and-replace in phpMyAdmin corrupts those settings. Use a
serialization-aware tool instead.

Replace `https://serensweb.test` with `https://serensweb.com`. Include the
`https://` so the two `@serensweb.test` email addresses in the dump are left
untouched.

- With SSH (Hostinger Business plans and up):

  ```
  wp search-replace 'https://serensweb.test' 'https://serensweb.com' --all-tables --precise
  ```

- Without SSH, use the standalone Search-Replace-DB script
  (https://github.com/interconnectit/Search-Replace-DB). Upload the folder to
  `public_html`, open it in the browser, enter the two values, run a dry run then
  a live run, then delete the folder immediately. It can read the entire database,
  so it must not be left in place.

### 5. Clean up and finish

- Delete the `.sql` dump from `public_html` if it was uploaded there.
- wp-admin, Settings, Permalinks, Save (rebuilds the URL rewrite rules).
- Hard-refresh serensweb.com and confirm: the home page loads, the About portrait
  shows, the 33figs project image shows, and the Florida map button opens.

## Hostinger gotchas learned in the first production deploy

These bit us on the real serensweb.com deploy (2026-07-01). Save yourself the time.

- **`wp db export` and `wp db import` silently no-op on this host.** They exit 0 and
  write nothing, a managed-hosting restriction on the wrapper. Use the raw client
  instead. Export a backup with `mysqldump`, import with `mysql`, reading the
  credentials from wp-config:

  ```
  DB=$(wp config get DB_NAME); U=$(wp config get DB_USER)
  P=$(wp config get DB_PASSWORD); H=$(wp config get DB_HOST)
  mysqldump --no-tablespaces -h"$H" -u"$U" -p"$P" "$DB" > backup.sql   # backup
  mysql -h"$H" -u"$U" -p"$P" "$DB" < fresh-local.sql                   # import
  ```

- **The LiteSpeed object-cache dropin serves stale reads.** After importing a
  database whose `active_plugins` differs, the `wp-content/object-cache.php` dropin
  is left orphaned (LiteSpeed inactive, dropin still loaded) and keeps returning the
  pre-import cached values. `wp option get siteurl` will lie to you. Either flush
  (`wp cache flush`) or move the dropin aside before trusting any read, and do the
  post-import writes (password reset, option updates) with it out of the way.

- **Flush rewrite rules with the theme loaded.** The `project` custom post type and
  its `/projects/<slug>/` permalinks are registered by the theme, so
  `wp rewrite flush --skip-themes` leaves them out and the project pages 404. Run a
  plain `wp rewrite flush` (no `--skip-themes`) once the theme is in place.

- **`wp db export` broke, but `wp search-replace` works fine.** The serialization
  fix in step 4 is safe to run through wp-cli on this host.

- **Reset the admin password immediately.** The local database ships `admin`/`admin`.
  A full import makes that the live login until you change it:
  `wp user update admin --user_pass='...' --user_email='...'`.

## Post-deploy: enable the contact form email

The theme routes `wp_mail()` through Gmail SMTP when two secrets are present
(`includes/smtp.php`). On production, set them as constants in the server's
`wp-config.php`, above the "That's all, stop editing" line. The file lives outside
the theme, so theme re-uploads never overwrite it, and it is not in git:

```php
define( 'SW_SMTP_USER', 'vandermerweseren@gmail.com' );   // Gmail that sends
define( 'SW_SMTP_PASS', 'xxxxxxxxxxxxxxxx' );             // 16-char App Password
```

Generate the App Password from the sending Google account (2-Step Verification must
be on). Full walkthrough: `docs/contact-email-setup.md`. Until both are set the form
validates and shows success but sends nothing; WhatsApp and the mailto link are the
working channels.
