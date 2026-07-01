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

## Related

- Contact form email delivery needs the Gmail App Password secret set in the
  production environment. See `docs/contact-email-setup.md`. Until it is set the
  form validates and shows success but sends nothing; WhatsApp and the mailto link
  are the working channels.
