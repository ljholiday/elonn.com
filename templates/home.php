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
            <p class="eyebrow">Live now — Web</p>
            <h1 id="hero-title">One place to find everything around you.</h1>
            <p class="hero__summary">
                Real-world places, the web, and your social world &mdash; in a single experience, not a folder of separate apps. Elonn is built to run wherever you are today, and to carry straight through to the lightweight XR headsets coming next. Try it now in your browser.
            </p>
            <div class="actions" aria-label="Primary actions">
                <a class="button button--primary" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">Try Web Demo</a>
                <?php if ($identity === null): ?>
                    <a class="button" href="/account/register" data-auth-open="register">Create account</a>
                    <a class="button" href="/account/login" data-auth-open="login">Log in</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="story" id="world" aria-labelledby="world-title">
        <p class="eyebrow">Built for the XR future</p>
        <h2 id="world-title">The first proof of a much bigger idea: information that lives around you, not inside an app.</h2>
        <p>
            Lightweight XR headsets are coming, and they'll need software that shows useful information exactly when and where you need it &mdash; not another app icon to tap. That's what we're building. You can already try the platform underneath it today, on the web.
        </p>
    </section>

    <section class="overview" aria-label="What Elonn helps you find">
        <article>
            <span>Real world</span>
            <h2>Find places around you.</h2>
            <p>Look for restaurants, parks, transit, gas, local destinations, and other nearby points of interest.</p>
        </article>
        <article>
            <span>Internet</span>
            <h2>Find information online.</h2>
            <p>Search across web information without losing the places, people, and context you are already looking at.</p>
        </article>
        <article>
            <span>Social</span>
            <h2>Find people and activity.</h2>
            <p>Follow messages, conversations, events, and social activity alongside the real-world and internet information you are exploring.</p>
        </article>
    </section>

    <section class="runtime-status" aria-labelledby="runtime-status-title">
        <div>
            <p class="eyebrow">Available now</p>
            <h2 id="runtime-status-title">Real software you can use today, with a lot more on the way.</h2>
        </div>
        <ul>
            <li><strong>Web demo</strong><span>Try Elonn in a browser and explore the information we're connecting.</span></li>
            <li><strong>XR direction</strong><span>Every runtime we build proves out the same platform underneath &mdash; the foundation for headsets that make information visible in the world around you.</span></li>
            <li><strong>Shared account</strong><span>One Elonn identity will work across every runtime we bring online.</span></li>
        </ul>
    </section>

    <section class="access" id="access" aria-labelledby="access-title">
        <div>
            <p class="eyebrow">Web demo</p>
            <h2 id="access-title">Start in the browser.</h2>
        </div>
        <div>
            <p>
                The web demo is the easiest way to try Elonn now. Create an account to get started &mdash; the same identity will carry across every runtime as we bring them online.
            </p>
            <a class="button button--primary" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">Try Web Demo</a>
        </div>
    </section>

    <section class="story" aria-labelledby="investors-title">
        <p class="eyebrow">Backing Elonn</p>
        <h2 id="investors-title">Building toward the end of app-switching.</h2>
        <p>
            If the thesis behind Elonn is interesting to you, <a href="/investors">read more and get in touch</a>.
        </p>
    </section>
</main>

<?php if ($identity === null): ?>
    <?php require __DIR__ . '/account/login.php'; ?>
    <?php require __DIR__ . '/account/register.php'; ?>
<?php endif; ?>
