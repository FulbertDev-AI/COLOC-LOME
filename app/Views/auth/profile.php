<section class="auth-card panel">
    <h1>Mon profil</h1>
    <div class="who who--lg">
        <img src="<?= e(asset('img/' . $profile['avatar'])) ?>" alt="">
        <div>
            <h2><?= e($profile['first_name'] . ' ' . $profile['last_name']) ?></h2>
            <p><?= e($profile['program']) ?> · <?= e($profile['school']) ?></p>
            <p class="muted"><?= e($profile['email']) ?></p>
            <?php if ($profile['verified']): ?><span class="badge badge-ok">Profil certifié</span><?php endif; ?>
        </div>
    </div>
    <p>
        <?php if (is_host()): ?>
            <a class="btn btn-dark" href="<?= e(url('/hote')) ?>">Tableau de bord hôte</a>
        <?php else: ?>
            <a class="btn btn-dark" href="<?= e(url('/candidatures')) ?>">Mes candidatures</a>
        <?php endif; ?>
    </p>
    <form method="post" action="<?= e(url('/deconnexion')) ?>">
        <?= csrf_field() ?>
        <button class="btn btn-ghost" type="submit">Se déconnecter</button>
    </form>
</section>
