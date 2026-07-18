# Universal Agent Guidelines
- Prefer clarity and pragmatism over cleverness.

## Terminology
- `../docs.elonn.local/content/terminology/glossary.xml` is the canonical machine-readable terminology reference.
- Do not use terms, identifiers, route names, payload keys, or UI copy that contradict it.
- The `<distinctions>` block lists the most common term confusions — read it before naming anything.

## Scope
- These instructions apply across repositories unless a repo-local file explicitly overrides them.

## HTML
- Use semantic, minimal markup.
- Keep structure document-first and avoid presentational HTML.
- Ensure accessibility basics are covered (labels, alt text, heading order).

## CSS
- Prefer a single global stylesheet unless a repo explicitly defines another approach.
- Avoid inline CSS.
- Keep class names descriptive and stable.
- Favor layouts that read well as documents; responsiveness is important but secondary to readability.

## JavaScript
- JavaScript is optional; the product must remain usable without it.
- Use JS to enhance usability or automate boilerplate, not to duplicate server state.
- Avoid heavy client-side state or frameworks unless the repo explicitly adopts them.

## PHP
- Keep routing, controllers, and rendering separated.
- Keep business logic out of templates.
- Keep view logic focused on presentation only.

## PHP Services
- Standardize service layout on root `.env`, root `.env.example`, committed `config/config.php`, Composer autoloading, committed `vendor/`, `public/` as the web entry point, `src/`, `storage/`, and `templates/` where applicable.
- `.env` is deployment state and must stay ignored. `.env.example` is committed and must contain the full key contract with no real secrets.
- `config/config.php` is committed. It is the only layer that reads deployment environment values from `$_ENV`, `$_SERVER`, or dotenv-loaded values, and it must return normalized config arrays for the application.
- Application, database, service, controller, and template code should consume normalized config arrays or app config helpers. Do not read deployment config directly with `$_ENV`, `$_SERVER`, `getenv()`, custom env parsers, or path-based `fromEnv()` helpers outside `config/config.php`.
- Bootstrap early from the entry point or CLI script: require `vendor/autoload.php`, call `Dotenv::createImmutable(BASE_PATH)->safeLoad()`, then require `config/config.php`.
- Use `vlucas/phpdotenv` for local/shared-hosting environment loading. Use `safeLoad()` so production can still run if real environment variables are injected later.
- Commit `composer.json`, `composer.lock`, and `vendor/` for these shared-hosting deployments. Production should receive a complete runnable app and should not need to run Composer.
- Do not commit `composer.phar`, `.env`, generated local salts, runtime logs, uploads, caches, or other deployment/runtime state.
- Prefer standard env key names across services: `APP_ENV`, `APP_DEBUG`, `APP_URL`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_CHARSET`, and service-specific keys such as `ELONN_API_BASE_URL`.
- Keep legacy compatibility only where needed, but do not introduce new `config/.env`, custom `Env::load`, `loadEnv`, or `Database::fromEnv` patterns.

## Git
- Use short, imperative, sentence-case commit messages.
- Keep commits focused on a single intent and user-visible outcome.
- Avoid mixing formatting-only edits with functional changes when possible.
- Do not include unrelated files in a commit.
- Do not leave uncommitted files after a commit. Use a separate commit if necessary.

## Comments
- Use comments to explain intent, constraints, or non-obvious choices.
- Avoid redundant comments that restate code.
- Track TODOs centrally when possible; avoid TODO sprawl.

## File Headers
- Add a clear, informative header when the file is non-trivial and the format allows comments.
- Do not add comment headers to formats where comments are inappropriate (for example, `.json`).
- Headers should explain purpose, scope, and ownership of intent.
- Do not include edit history, authorship, dates, or per-file version numbers.
- Keep headers short, stable, and aligned with the file’s actual role.
- Use format-appropriate conventions when tooling exists.

## Summaries
- When summarizing work, include in the "What changed" section a list of repos committed.
