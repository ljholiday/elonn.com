<?php
/** @var array<string, mixed> $data */
$identity = $data['identity'] ?? null;
$davUrl = (string) ($data['davUrl'] ?? '');
$davUsername = (string) (($identity['username'] ?? null) ?: ($identity['email'] ?? ''));
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
        <p class="account-copy">Connect Thunderbird or another CalDAV client with your existing Elonn account.</p>
        <dl class="caldav-settings">
            <div><dt>Server URL</dt><dd><code><?= htmlspecialchars($davUrl, ENT_QUOTES, 'UTF-8') ?></code></dd></div>
            <div><dt>Username</dt><dd><code><?= htmlspecialchars($davUsername, ENT_QUOTES, 'UTF-8') ?></code></dd></div>
            <div><dt>Password</dt><dd>Your normal Elonn account password</dd></div>
        </dl>
    </section>
</main>
