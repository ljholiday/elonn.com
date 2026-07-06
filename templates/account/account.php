<?php
/** @var array<string, mixed> $data */
$identity = $data['identity'] ?? null;
$davTokens = is_array($data['davTokens'] ?? null) ? $data['davTokens'] : [];
$davToken = is_string($data['davToken'] ?? null) ? $data['davToken'] : null;
$davUrl = (string) ($data['davUrl'] ?? '');
$davUsername = (string) (($identity['username'] ?? null) ?: ($identity['email'] ?? ''));
$error = is_string($data['error'] ?? null) ? $data['error'] : null;
$notice = is_string($data['notice'] ?? null) ? $data['notice'] : null;
?>
<main class="account-page">
    <section class="account-panel" aria-labelledby="account-title">
        <p class="eyebrow">Elonn Account</p>
        <h1 id="account-title">Account</h1>
        <div class="account-details">
            <div><span>ID</span><strong><?= htmlspecialchars((string) ($identity['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong></div>
            <div><span>Email</span><strong><?= htmlspecialchars((string) ($identity['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong></div>
            <div><span>Display name</span><strong><?= htmlspecialchars((string) ($identity['display_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong></div>
        </div>
        <form class="account-actions" method="post" action="/account/logout">
            <button class="button" type="submit">Log out</button>
        </form>
    </section>
    <section class="account-panel account-panel--wide" id="caldav" aria-labelledby="caldav-title">
        <p class="eyebrow">Connected calendars</p>
        <h2 id="caldav-title">CalDAV</h2>
        <p class="account-copy">Create a separate CalDAV password for each calendar app.</p>
        <?php if ($error !== null): ?><p class="account-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <?php if ($notice !== null): ?><p class="account-notice"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <?php if ($davToken !== null): ?>
            <section class="caldav-secret" aria-labelledby="caldav-secret-title">
                <h3 id="caldav-secret-title">New CalDAV password</h3>
                <p>This password is shown only once.</p>
                <code><?= htmlspecialchars($davToken, ENT_QUOTES, 'UTF-8') ?></code>
            </section>
        <?php endif; ?>
        <dl class="caldav-settings">
            <div><dt>Server URL</dt><dd><code><?= htmlspecialchars($davUrl, ENT_QUOTES, 'UTF-8') ?></code></dd></div>
            <div><dt>Username</dt><dd><code><?= htmlspecialchars($davUsername, ENT_QUOTES, 'UTF-8') ?></code></dd></div>
            <div><dt>Password</dt><dd>Use a generated CalDAV password below, or your normal Elonn account password.</dd></div>
        </dl>
        <form class="account-form caldav-create" method="post" action="/account/caldav-tokens">
            <label>
                App or device name
                <input name="label" maxlength="255" placeholder="Personal iPhone" autocomplete="off">
            </label>
            <button class="button button--primary" type="submit">Create CalDAV password</button>
        </form>
        <section class="caldav-tokens" aria-labelledby="caldav-passwords-title">
            <h3 id="caldav-passwords-title">CalDAV passwords</h3>
            <?php if ($davTokens === []): ?>
                <p class="account-copy">No CalDAV passwords yet.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($davTokens as $token): ?>
                        <li>
                            <div>
                                <strong><?= htmlspecialchars((string) ($token['label'] ?? 'CalDAV client'), ENT_QUOTES, 'UTF-8') ?></strong>
                                <span>
                                    <?= htmlspecialchars((string) ($token['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    - Created <?= htmlspecialchars((string) ($token['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    <?php if (!empty($token['last_used_at'])): ?>
                                        - Last used <?= htmlspecialchars((string) $token['last_used_at'], ENT_QUOTES, 'UTF-8') ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php if (($token['status'] ?? '') === 'active'): ?>
                                <form method="post" action="/account/caldav-tokens/revoke">
                                    <input type="hidden" name="token_id" value="<?= (int) ($token['id'] ?? 0) ?>">
                                    <button class="button" type="submit">Revoke</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </section>
</main>
