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
            <p class="eyebrow">Early demos are online</p>
            <h1 id="hero-title">Elonn</h1>
            <p class="hero__summary">
                Find things everywhere: places in the real world, information on the internet, and people, messages, and activity in your social world. We are building toward XR headsets, where that information can be with you wherever you are.
            </p>
            <div class="actions" aria-label="Primary actions">
                <a class="button button--primary" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">Try Web Demo</a>
                <?php if ($identity === null): ?>
                    <a class="button" href="/account/register" data-auth-open="register">Create account</a>
                    <a class="button" href="/account/login" data-auth-open="login">Log in</a>
                <?php endif; ?>
                <a class="button" href="/assets/downloads/elonn-world-ar-debug.apk" download>Download Android Demo</a>
            </div>
        </div>
    </section>

    <section class="story" id="world" aria-labelledby="world-title">
        <p class="eyebrow">Built for the XR future</p>
        <h2 id="world-title">The web and Android demos are steps toward information that lives around you.</h2>
        <p>
            The long-term plan is ubiquitous XR headsets: lightweight displays that can show useful information in the real world, when and where people need it. Today, we are proving the information layer with basic web and Android demos.
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
            <h2 id="runtime-status-title">These are working demos, not a finished product.</h2>
        </div>
        <ul>
            <li><strong>Web demo</strong><span>Try Elonn in a browser and explore the information we are connecting.</span></li>
            <li><strong>Android demo</strong><span>Use the Android build to see the same information in a more spatial view.</span></li>
            <li><strong>XR direction</strong><span>These demos are groundwork for headsets that can make real-world, internet, and social information visible around you.</span></li>
            <li><strong>Shared account</strong><span>Use one Elonn identity across the demos we are bringing online.</span></li>
        </ul>
    </section>

    <section class="app-preview" aria-labelledby="app-title">
        <div>
            <p class="eyebrow">XR direction</p>
            <h2 id="app-title">The goal is useful information in the world, not another app to manage.</h2>
        </div>
        <div>
            <p>
                Android lets us test the shape of that future now: the same places, internet information, and social activity shown in a more spatial way while XR headsets continue to mature.
            </p>
            <a class="button" href="/assets/downloads/elonn-world-ar-debug.apk" download>Download Android Demo</a>
        </div>
    </section>

    <section class="access" id="access" aria-labelledby="access-title">
        <div>
            <p class="eyebrow">Web demo</p>
            <h2 id="access-title">Start in the browser.</h2>
        </div>
        <div>
            <p>
                The web demo is the easiest way to try Elonn now. Create an account to use the same identity across the web and Android demos.
            </p>
            <a class="button button--primary" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">Try Web Demo</a>
        </div>
    </section>
</main>

<?php if ($identity === null): ?>
    <?php require __DIR__ . '/account/login.php'; ?>
    <?php require __DIR__ . '/account/register.php'; ?>
<?php endif; ?>
