# Contact form email delivery

The contact form posts to a theme-only REST endpoint
(`includes/ajax-contact.php`) that validates the submission and calls
`wp_mail()`. WordPress ships PHPMailer but no mail transport, so on a host with
no local mail server nothing is actually sent. `includes/smtp.php` supplies the
transport: when Gmail SMTP credentials are present in the environment, it routes
`wp_mail()` through Gmail. No plugin is involved, so the active-plugin count
stays at zero.

When the credentials are absent (the default locally, and on a fresh deploy
before the secret is set), the form still validates and shows its success state,
but no email leaves the server. The working fallback channels remain the
WhatsApp button and the `vandermerweseren@gmail.com` mailto link.

## What you need

A Google account (the Gmail address that will send and receive the enquiries)
with 2-Step Verification enabled, plus an App Password generated from it. An App
Password is a 16-character token that lets one app authenticate without your
main password, and it can be revoked on its own.

## Generate the Gmail App Password

1. Turn on 2-Step Verification at https://myaccount.google.com/security (App
   Passwords are only available once it is on).
2. Go to https://myaccount.google.com/apppasswords.
3. Name the app (for example "SerensWeb contact form") and create it.
4. Copy the 16-character password. Google shows it once. Store it safely.

## Settings

`includes/smtp.php` resolves each setting from a `wp-config.php` **constant**
first, then a matching **environment variable**. So the same theme code works on
shared hosting (constants) and on the Docker stack (env vars) with no changes.

| Key            | Required | Purpose                                              |
|----------------|----------|------------------------------------------------------|
| `SW_SMTP_USER` | yes      | Gmail address that authenticates and sends           |
| `SW_SMTP_PASS` | yes      | The 16-character App Password (no spaces)             |
| `SW_SMTP_FROM` | no       | From address; defaults to `SW_SMTP_USER`             |
| `SW_SMTP_HOST` | no       | Defaults to `smtp.gmail.com`                          |
| `SW_SMTP_PORT` | no       | Defaults to `587` (TLS). Use `465` for SSL.          |

If `SW_SMTP_USER` or `SW_SMTP_PASS` is missing, `smtp.php` does nothing and
delivery is skipped. Never commit these values, in either form.

### Where the secret lives (and why it stays out of the zip)

The repo tracks only the theme (`serensweb-child`); `wp-config.php` and the rest
of WordPress are gitignored and are not part of the theme zip. That makes
`wp-config.php` the right home for the secret on a live host: it sits above the
theme, so it is never in the zip, never in git, and survives every theme
re-upload. You set it once on the server.

## Local (Docker stack)

Add the values to the repo-root `.env` file (not `.env.example`, which is
committed and stays blank):

```
SW_SMTP_USER=vandermerweseren@gmail.com
SW_SMTP_PASS=your16charapppassword
```

`compose.yml` already passes these into the PHP container. Recreate it so it
picks them up:

```
docker compose up -d serens-web-php-service
```

Send a test through the live form at https://serensweb.test/contact, or from
wp-cli inside the container:

```
docker compose exec serens-web-php-service wp eval \
  'var_dump( wp_mail( get_option("admin_email"), "SMTP test", "It works." ) );'
```

`bool(true)` means PHPMailer handed the message to Gmail. Check the inbox to
confirm delivery.

## Production on Hostinger (or any shared host)

Shared hosts usually cannot set arbitrary `getenv()` variables, so use
`wp-config.php` constants instead. This is the same place the database password
already lives.

1. In hPanel, open **File Manager** (or connect over SFTP) and edit
   `wp-config.php` in the site root (the folder that contains `wp-content`).
2. Add these lines above the `/* That's all, stop editing! */` comment:

   ```php
   define( 'SW_SMTP_USER', 'vandermerweseren@gmail.com' );
   define( 'SW_SMTP_PASS', 'your16charapppassword' );
   ```

   Add `SW_SMTP_FROM`, `SW_SMTP_HOST`, or `SW_SMTP_PORT` the same way only if you
   need to override the defaults.
3. Save. Because the constants live in `wp-config.php`, they are not in git and
   not in the theme zip, and they stay put when you re-upload the theme.
4. In WordPress, set **Settings, General, Administration Email Address** to the
   inbox that should receive enquiries, then send a test through
   `https://<your-domain>/contact` and confirm it arrives.

If port 587 is blocked by the host, set `define( 'SW_SMTP_PORT', 465 );` (the
transport switches to SSL automatically). Do not place the App Password in the
repository or the theme zip.

## Notes

- The recipient is the WordPress `admin_email` (Settings, General). Set that to
  the address that should receive enquiries.
- The visitor's own address is set as `Reply-To`, so replying from the inbox
  goes straight back to them.
- If mail stops sending, first check that the App Password is still valid
  (revoking it or changing the account password invalidates it) and that
  `SW_SMTP_PORT` is not blocked by the host firewall.
