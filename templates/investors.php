<?php
/** @var array<string, mixed> $data */
$identity = $data['identity'] ?? null;
$worldUrl = (string) ($data['worldUrl'] ?? '#');
?>
<main>
    <section class="hero" aria-labelledby="hero-title">
        <div class="hero__media" aria-hidden="true"></div>
        <div class="hero__content">
            <p class="eyebrow">For investors</p>
            <h1 id="hero-title">The end of app-switching.</h1>
            <p class="hero__summary">
                Every generation of computing has organized itself around applications: separate interfaces, separate data, separate context, and the growing effort of finding, launching, and coordinating them. Elonn starts from a different assumption — people should express what they want to do, and the platform should assemble the information and capabilities required, instead of making them choose an app first.
            </p>
            <div class="actions" aria-label="Primary actions">
                <a class="button button--primary" href="/contact?topic=investing">Get in touch</a>
                <a class="button" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">Try Web Demo</a>
            </div>
        </div>
    </section>

    <section class="story" aria-labelledby="model-title">
        <p class="eyebrow">The model</p>
        <h2 id="model-title">One persistent world, not a folder of apps.</h2>
        <p>
            Elonn represents a member's world &mdash; the people, places, conversations, and information relevant to them &mdash; as a single canonical model, independent of any device or service. Every runtime, whether a phone screen today or an XR headset tomorrow, renders the same underlying world according to its own capabilities. Adding a new capability means adding a service behind the platform, not shipping a new app for members to discover and manage.
        </p>
    </section>

    <section class="overview" aria-label="What's built">
        <article>
            <span>Architecture</span>
            <h2>A canonical platform, not a demo held together with glue.</h2>
            <p>A Runtime submits a request to World; World delegates to Mind; Mind draws on independent Services (search, social, messaging, calendar, maps) and returns a canonical dataset the Runtime renders. Every boundary is a defined, versioned contract &mdash; not implementation details leaking between teams.</p>
        </article>
        <article>
            <span>Multi-runtime today</span>
            <h2>A working web demo, with more runtimes underway.</h2>
            <p>The same account and the same world already work in a browser demo, with Android, iPad, Cardboard, and XREAL glasses runtimes in active development against the same contract &mdash; proving the model scales to spatial and headset form factors, not just phone screens.</p>
        </article>
        <article>
            <span>Why now</span>
            <h2>Spatial computing is arriving; the information layer for it doesn't exist yet.</h2>
            <p>Headsets are becoming lighter and more capable every year, but the software model underneath them is still app-shaped. Elonn is building the layer that lets useful information simply be present in the world, the same layer of thinking that has to exist before ambient, headset-native computing can work at all.</p>
        </article>
    </section>

    <section class="access" aria-labelledby="contact-title">
        <div>
            <p class="eyebrow">Let's talk</p>
            <h2 id="contact-title">We're raising to grow the platform and the runtimes it powers.</h2>
        </div>
        <div>
            <p>
                If this thesis is interesting to you, we'd like to talk about where Elonn is headed and how to be part of it.
            </p>
            <a class="button button--primary" href="/contact?topic=investing">Contact us</a>
        </div>
    </section>
</main>
<!--
  Draft for review. Left deliberately unspecified: round stage, amount, and
  any traction/team specifics — fill in with real numbers before this goes
  live, rather than have Claude invent them.
-->
