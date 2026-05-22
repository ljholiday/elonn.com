<?php
/** @var array<string, mixed> $data */
$identity = $data['identity'] ?? null;
$worldUrl = (string) ($data['worldUrl'] ?? '#');
$error = $data['error'] ?? null;
?>
<main>
    <section class="hero" aria-labelledby="hero-title">
        <div class="hero__media" aria-hidden="true"></div>
        <div class="hero__content">
            <p class="eyebrow">The World runtime is online</p>
            <h1 id="hero-title">Elonn</h1>
            <p class="hero__summary">
                A spatial runtime for places, people, tools, and live digital surfaces arranged around you instead of buried in tabs.
            </p>
            <div class="actions" aria-label="Primary actions">
                <a class="button button--primary" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">Enter World</a>
                <?php if ($identity === null): ?>
                    <a class="button" href="/account/register" data-auth-open="register">Create account</a>
                    <a class="button" href="/account/login" data-auth-open="login">Log in</a>
                <?php else: ?>
                    <a class="button" href="/start">Start</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="story" id="world" aria-labelledby="world-title">
        <p class="eyebrow">A runtime becomes a place</p>
        <h2 id="world-title">World, Social, Maps, Find, Time, and Android now share the same frame.</h2>
        <p>
            Elonn World composes services into a field layer, carry windows, side rails, docks, and native Android surfaces. The same session now feeds the browser runtime and the AR preview.
        </p>
    </section>

    <section class="overview" aria-label="World principles">
        <article>
            <span>Field Layer</span>
            <h2>Places stay in the world.</h2>
            <p>Maps POIs render as field markers so restaurants, parks, transit, gas, and local points of interest have direction and context.</p>
        </article>
        <article>
            <span>Carry Layer</span>
            <h2>Tools stay with you.</h2>
            <p>Find, Social, Events, Calendar, Messages, and Settings open as carry windows while top and bottom docks stay centered on the runtime.</p>
        </article>
        <article>
            <span>Context Rails</span>
            <h2>The sides carry meaning.</h2>
            <p>World Contract and Social Menu rails now act as scrollable context and navigation surfaces instead of hiding behind generic controls.</p>
        </article>
    </section>

    <section class="runtime-status" aria-labelledby="runtime-status-title">
        <div>
            <p class="eyebrow">Current build</p>
            <h2 id="runtime-status-title">The product surface is taking shape.</h2>
        </div>
        <ul>
            <li><strong>Login</strong><span>Android signs into the shared Elonn runtime.</span></li>
            <li><strong>World session</strong><span>Panels, rails, docks, maps objects, and services come from World.</span></li>
            <li><strong>Messages</strong><span>Inbox, thread navigation, new messages, and replies are back in the Android carry window.</span></li>
            <li><strong>POIs</strong><span>Maps field objects are resiliently parsed for the AR field layer.</span></li>
        </ul>
    </section>

    <section class="app-preview" aria-labelledby="app-title">
        <div>
            <p class="eyebrow">Android AR runtime</p>
            <h2 id="app-title">Carry the world off the page and into the room.</h2>
        </div>
        <div>
            <p>
                The Android build now uses the live World session: AR field markers, centered docks, scrollable side rails, carry panels, Social, Messages, Find, Time, and the current APK download.
            </p>
            <a class="button" href="/assets/downloads/elonn-world-ar-debug.apk" download>Download Android APK</a>
        </div>
    </section>

    <section class="access" id="access" aria-labelledby="access-title">
        <div>
            <p class="eyebrow">Live demo</p>
            <h2 id="access-title">The first doorway is open.</h2>
        </div>
        <div>
            <p>
                The web runtime is live now as the first public doorway into Elonn World. Create an account to use the shared identity session across World, Social, Maps, and the Android preview.
            </p>
            <a class="button button--primary" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">Enter World</a>
        </div>
    </section>
</main>

<?php if ($identity === null): ?>
    <?php require __DIR__ . '/account/login.php'; ?>
    <?php require __DIR__ . '/account/register.php'; ?>
<?php endif; ?>
