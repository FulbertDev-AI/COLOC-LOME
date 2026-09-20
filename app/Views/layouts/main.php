<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ColocLomé') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>?v=5">
</head>
<body>
    <?php require dirname(__DIR__) . '/partials/header.php'; ?>
    <main class="page">
        <?php if ($msg = flash('success')): ?>
            <div class="flash flash-ok"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="flash flash-err"><?= e($msg) ?></div>
        <?php endif; ?>
        <?= $content ?>
    </main>
    <?php require dirname(__DIR__) . '/partials/footer.php'; ?>
    <script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
