<?php
/** @var array<string, mixed> $data */
$heading = (string) ($data['heading'] ?? 'Information');
$body = is_array($data['body'] ?? null) ? $data['body'] : [];
?>
<main class="legal-page">
    <section class="legal-panel" aria-labelledby="legal-title">
        <p class="eyebrow">Elonn</p>
        <h1 id="legal-title"><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></h1>
        <?php foreach ($body as $paragraph): ?>
            <?php if (is_string($paragraph)): ?>
                <p><?= htmlspecialchars($paragraph, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
</main>
