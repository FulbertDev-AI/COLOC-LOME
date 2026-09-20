<header class="topbar">
    <div class="topbar__inner">
        <a class="brand" href="<?= e(url('/')) ?>">
            <span class="brand__mark" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-9.5Z" fill="#111827"/></svg>
            </span>
            <span>
                <strong>ColocLomé</strong>
                <small>PLATEFORME ÉTUDIANTE VÉRIFIÉE</small>
            </span>
        </a>

        <div class="topbar__actions">
            <a class="icon-btn" href="<?= e(url('/messages')) ?>" aria-label="Notifications">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2Zm8-6V11a8 8 0 1 0-16 0v5L2 18v1h20v-1l-2-2Z" fill="#111827"/></svg>
                <i class="dot"></i>
            </a>
            <a class="btn btn-dark topbar__publish" href="<?= e(url('/publier')) ?>">Publier une annonce</a>
            <?php if ($user): ?>
                <a class="avatar" href="<?= e(url(is_host() ? '/hote' : '/candidatures')) ?>">
                    <img src="<?= e(asset('img/' . ($user['avatar'] ?? 'koffi.jpg'))) ?>" alt="">
                </a>
            <?php else: ?>
                <a class="btn btn-ghost topbar__login" href="<?= e(url('/connexion')) ?>">Connexion</a>
            <?php endif; ?>
            <button class="menu-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="site-nav" data-menu-toggle>
                <span></span><span></span><span></span>
            </button>
        </div>

        <nav class="nav" id="site-nav">
            <a class="<?= active_nav('/recherche') || active_nav('/preferences') ? 'is-active' : '' ?>" href="<?= e(url('/recherche')) ?>">Rechercher</a>
            <a class="<?= active_nav('/publier') ?>" href="<?= e(url('/publier')) ?>">Proposer une colocation</a>
            <a class="<?= active_nav('/messages') ?>" href="<?= e(url('/messages')) ?>">Messages</a>
            <a class="<?= active_nav('/profil') ?>" href="<?= e(url('/profil')) ?>">Mon Profil</a>
            <?php if (is_host()): ?>
                <a class="<?= active_nav('/hote') ?>" href="<?= e(url('/hote')) ?>">Espace hôte</a>
            <?php elseif ($user): ?>
                <a class="<?= active_nav('/candidatures') ?>" href="<?= e(url('/candidatures')) ?>">Mes candidatures</a>
            <?php endif; ?>
            <a class="btn btn-dark nav__mobile-cta" href="<?= e(url('/publier')) ?>">Publier une annonce</a>
            <?php if (!$user): ?>
                <a class="btn btn-ghost nav__mobile-cta" href="<?= e(url('/connexion')) ?>">Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="nav-backdrop" data-menu-close hidden></div>
</header>
