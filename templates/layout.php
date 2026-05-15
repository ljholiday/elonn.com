<?php
/** @var string $title */
/** @var string $template */
/** @var array<string, mixed> $data */
/** @var array{id: string, email: string, display_name: string|null}|null $identity */
/** @var string $worldUrl */
$currentTemplate = $template;
$pageDescription = 'Elonn is the new front page for a spatial internet: a world layer for places, tools, and persistent digital presence.';
$pageUrl = 'https://elonn.com/';
$shareImage = 'https://elonn.com/assets/img/elonn-logo.png';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:site_name" content="Elonn">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($shareImage, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image:secure_url" content="<?= htmlspecialchars($shareImage, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1024">
    <meta property="og:image:height" content="1024">
    <meta property="og:image:alt" content="Elonn logo">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LNJE3CGYKC"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-LNJE3CGYKC');
    </script>
    <link rel="stylesheet" href="/assets/css/elonn.css">
</head>
<body>
    <header class="site-header" aria-label="Primary">
        <a class="brand" href="/">
            <img src="/assets/img/elonn-logo.png" alt="" width="42" height="42">
            <span>Elonn</span>
        </a>
        <nav class="nav" aria-label="Primary navigation">
            <?php if ($identity === null): ?>
                <a href="/account/login" data-auth-open="login">Log in</a>
                <a href="/account/register" data-auth-open="register">Create account</a>
            <?php else: ?>
                <a href="/start">Start</a>
                <a href="/account">Account</a>
            <?php endif; ?>
            <a class="nav__cta" href="<?= htmlspecialchars($worldUrl, ENT_QUOTES, 'UTF-8') ?>">Enter World</a>
        </nav>
    </header>

    <?php require __DIR__ . '/' . $template; ?>

    <footer class="site-footer">
        <div>
            <strong>Elonn</strong>
            <span>Spatial internet services and identity.</span>
        </div>
        <nav aria-label="Footer">
            <a href="/start">Start</a>
            <a href="/account">Account</a>
            <a href="mailto:hello@elonn.com">Contact</a>
            <a href="/privacy">Privacy</a>
            <a href="/terms">Terms</a>
        </nav>
    </footer>

    <script src="/assets/js/elonn.js" defer></script>
</body>
</html>
