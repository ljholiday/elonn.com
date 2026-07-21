<?php
/** @var array<string, mixed> $data */
$notice = $data['notice'] ?? null;
$error = $data['error'] ?? null;
$topic = (string) ($data['topic'] ?? 'general');
$old = is_array($data['old'] ?? null) ? $data['old'] : [];
?>
<main class="account-page">
    <section class="account-panel account-panel--wide" aria-labelledby="contact-title">
        <p class="eyebrow">Elonn</p>
        <h1 id="contact-title">Get in touch</h1>
        <p class="account-copy">
            Whether you're an investor, a developer who wants to get involved, press, or just have a question — tell us a bit about it and we'll follow up.
        </p>

        <?php if ($notice !== null): ?>
            <p class="account-notice"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if ($error !== null): ?>
            <p class="account-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if ($notice === null): ?>
            <form class="account-form" method="post" action="/contact">
                <label for="contact-name">Name
                    <input id="contact-name" name="name" type="text" required value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </label>
                <label for="contact-email">Email
                    <input id="contact-email" name="email" type="email" required value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </label>
                <label for="contact-topic">What's this about?
                    <select id="contact-topic" name="topic">
                        <option value="general" <?= $topic === 'general' ? 'selected' : '' ?>>General</option>
                        <option value="investing" <?= $topic === 'investing' ? 'selected' : '' ?>>Investing</option>
                        <option value="volunteering" <?= $topic === 'volunteering' ? 'selected' : '' ?>>Volunteering / contributing</option>
                        <option value="press" <?= $topic === 'press' ? 'selected' : '' ?>>Press</option>
                    </select>
                </label>
                <label for="contact-message">Message
                    <textarea id="contact-message" name="message" rows="6" required><?= htmlspecialchars($old['message'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </label>
                <div aria-hidden="true" style="position:absolute;left:-9999px;">
                    <label for="contact-company">Company</label>
                    <input id="contact-company" name="company" type="text" tabindex="-1" autocomplete="off">
                </div>
                <button class="button button--primary" type="submit">Send</button>
            </form>
        <?php endif; ?>
    </section>
</main>
