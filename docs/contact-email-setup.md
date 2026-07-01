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

## Environment variables

`includes/smtp.php` reads these from the environment (same pattern as the DB
credentials in `wp-config.php`):

| Variable       | Required | Purpose                                              |
|----------------|----------|------------------------------------------------------|
| `SW_SMTP_USER` | yes      | Gmail address that authenticates and sends           |
| `SW_SMTP_PASS` | yes      | The 16-character App Password (no spaces)             |
| `SW_SMTP_FROM` | no       | From address; defaults to `SW_SMTP_USER`             |
| `SW_SMTP_HOST` | no       | Defaults to `smtp.gmail.com`                          |
| `SW_SMTP_PORT` | no       | Defaults to `587` (TLS). Use `465` for SSL.          |

If `SW_SMTP_USER` or `SW_SMTP_PASS` is missing, `smtp.php` does nothing and
delivery is skipped. Never commit these values. `.env` is gitignored and the
secret must not go in the theme zip.

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

## Production

Set the same environment variables however the host injects environment into
PHP (a panel setting, the service unit, or the container environment). The
theme code is identical across environments; only the environment differs. Do
not place the App Password in the repository or the theme zip.

## Notes

- The recipient is the WordPress `admin_email` (Settings, General). Set that to
  the address that should receive enquiries.
- The visitor's own address is set as `Reply-To`, so replying from the inbox
  goes straight back to them.
- If mail stops sending, first check that the App Password is still valid
  (revoking it or changing the account password invalidates it) and that
  `SW_SMTP_PORT` is not blocked by the host firewall.
