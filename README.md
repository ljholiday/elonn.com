# Elonn

Elonn is a multi-service platform built around shared identity and runtime composition.

This repository is `elonn.com` — the company's **public information pages only**: home, investors, contact, privacy, terms, and other marketing / business content. It links out to the Runtimes; it has **no** identity or member code — no login, register, account, logout, or CalDAV surface, and no `ApiClient`. Members sign in from a Runtime, which renders `api.elonn`'s login screen (`decision.member_surface_consolidation_20260908`). The one content carve-out is `docs.elonn.local`, the separate user-facing help site.

## Service map

- `api.elonn.local`: identity authority and member directory
- `web.elonn.local`: browser runtime implementation
- `world.elonn.local`: composition and presentation contract layer
- `mind.elonn.local`: dispatch layer between World and enabled Services
- `find.elonn.local`: search and findings service
- `maps.elonn.local`: canonical field dataset
- `messages.elonn.local`: open member-to-member message threads outside Social context
- `social.elonn.local`: social objects, circles, and Social DMs
- `time.elonn.local`: calendars and events
- `admin.elonn.local`: operator console

## What lives here

- home, investors, contact (with a working contact form), privacy, terms
- links out to the Runtimes (currently `web.elonn`)
- marketing, funding, and other public/business content (e.g. `entrepreneur/`)

## Routes

```text
GET /
GET /investors
GET /contact
POST /contact
GET /privacy
GET /terms
```

The pages must work without JavaScript. To sign in, a visitor opens a Runtime (the
"Open Elonn Web" link) and authenticates there.

## Configuration

Create `.env` from `.env.example` and keep it uncommitted.

Important keys:

```env
APP_ENV=
APP_DEBUG=
APP_URL=
ELONN_WEB_URL=
ELONN_CONTACT_EMAIL=
```

`ELONN_WEB_URL` is the browser Runtime the "Open Elonn Web" links point at.
`ELONN_CONTACT_EMAIL` is where the `/contact` form's submissions are sent (via PHP's `mail()`); defaults to `hello@elonn.com`.

`ELONN_SERVICES_URL` and the retired shared services host are no longer active. CalDAV should point at `time.elonn.com/dav/`.

## Next composition work

The highest-value platform work is to surface Time, Messages, and Social through the World/Mind composition path as canonical World content. Web must continue to call only API for runtime-owned authentication and World for runtime data; it must not add direct calls to Time, Messages, Social, Find, Maps, Paint, provider endpoints, or service loaders.

Local defaults point to `.local` hosts. Production defaults point to `.com` hosts.

`public/index.php` loads `vendor/autoload.php`, calls `Dotenv::createImmutable(BASE_PATH)->safeLoad()`, then loads `config/config.php`. `config/config.php` is the only place that reads deployment environment values and returns normalized config arrays.

## Migrations

No database migrations are required for this repo.

If the account or launch flow needs new persistent state, that state belongs in the owning service, not here.

## Layout

```text
config/
  config.php
.env
.env.example
composer.json
composer.lock
vendor/
public/
  index.php
  .htaccess
  assets/
src/
templates/
```

## Verification

```bash
find public src templates -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Local URLs

- `http://elonn.local/`
- `http://elonn.local/account/login`
- `http://elonn.local/account/register`
- `http://elonn.local/account`
- `http://elonn.local/start`
- `http://elonn.local/investors`
- `http://elonn.local/contact`
- `http://elonn.local/privacy`
- `http://elonn.local/terms`

## Production URLs

- `https://elonn.com/`
- `https://elonn.com/account/login`
- `https://elonn.com/account/register`
- `https://elonn.com/account`
- `https://elonn.com/start`
- `https://elonn.com/investors`
- `https://elonn.com/contact`
- `https://elonn.com/privacy`
- `https://elonn.com/terms`
