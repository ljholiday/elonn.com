<?php
/** @var array<string, mixed> $data */
$identity = is_array($data['identity'] ?? null) ? $data['identity'] : [];
$displayUser = (string) (($identity['display_name'] ?? null) ?: ($identity['email'] ?? ''));
$worldUrl = (string) ($data['worldUrl'] ?? '#');
$timeUrl = (string) ($data['timeUrl'] ?? '#');
?>
<main class="start-page">
    <div class="start-shell">
        <section class="start-header" aria-labelledby="start-title">
            <div>
                <p class="eyebrow">Elonn Start</p>
                <h1 id="start-title">Services</h1>
            </div>
            <div class="start-user">
                <span>Signed in</span>
                <strong><?= htmlspecialchars($displayUser, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
        </section>

        <section class="start-layout" aria-label="Elonn destinations">
            <div class="service-list">
                <a class="service-row" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">
                    <span class="service-row__status">Live</span>
                    <div>
                        <strong>World</strong>
                        <p>Spatial web entry point for rooms, places, and persistent surfaces.</p>
                    </div>
                    <span class="service-row__action">Open</span>
                </a>
                <a class="service-row" href="<?= htmlspecialchars($timeUrl, ENT_QUOTES, 'UTF-8') ?>">
                    <span class="service-row__status">Live</span>
                    <div>
                        <strong>Time</strong>
                        <p>Calendars and events connected to your Elonn identity.</p>
                    </div>
                    <span class="service-row__action">Open</span>
                </a>
                <article class="service-row service-row--disabled">
                    <span class="service-row__status">Soon</span>
                    <div>
                        <strong>Places</strong>
                        <p>Saved rooms, local destinations, and world directory shortcuts.</p>
                    </div>
                    <span class="service-row__action">Pending</span>
                </article>
                <article class="service-row service-row--disabled">
                    <span class="service-row__status">Soon</span>
                    <div>
                        <strong>Carry</strong>
                        <p>Personal tools and surfaces that follow you across the world layer.</p>
                    </div>
                    <span class="service-row__action">Pending</span>
                </article>
            </div>

            <aside class="start-panel" aria-label="Account">
                <p class="eyebrow">Account</p>
                <dl>
                    <div>
                        <dt>Email</dt>
                        <dd><?= htmlspecialchars((string) ($identity['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                    <div>
                        <dt>Identity ID</dt>
                        <dd><?= htmlspecialchars((string) ($identity['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                </dl>
                <a class="button" href="/account">Manage account</a>
            </aside>
        </section>
    </div>
</main>
