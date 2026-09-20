<section class="workspace">
    <header class="workspace__head">
        <div>
            <p class="tiny">ESPACE GESTIONNAIRE ET CANDIDATURES · Session Ouverte</p>
            <h1>Tableau de bord Hôte & Gestion des Candidatures</h1>
            <p>Supervisez l’affectation des chambres, la solvabilité certifiée et les visites pour la Villa Harmonie.</p>
        </div>
        <div class="cta-actions">
            <a class="btn btn-ghost" href="<?= e(url('/publier')) ?>">Paramètres du Bail</a>
            <a class="btn btn-dark" href="<?= e(url('/publier')) ?>">Ajouter une chambre</a>
        </div>
    </header>

    <div class="stats-4">
        <article class="panel"><small>MES ANNONCES ACTIVES</small><strong><?= count($listings) ?></strong><span><?= e($listing['neighborhood'] ?? 'Adidogomé') ?> — 1 ch. libre</span></article>
        <article class="panel"><small>CANDIDATURES REÇUES</small><strong><?= count($applications) ?></strong><span>dossiers étudiants</span></article>
        <article class="panel"><small>VISITES PLANIFIÉES</small><strong><?= count($visits) ?></strong><span>sur place cette semaine</span></article>
        <article class="panel"><small>STATUT VÉRIFICATION</small><strong>Certifié UL-2024</strong><span>Bailleur Agréé Université Lomé</span></article>
    </div>

    <nav class="tabs">
        <a class="is-active" href="#candidatures">Candidatures Reçues (<?= count($applications) ?>)</a>
        <a href="<?= e(url('/publier')) ?>">Mon Annonce & Disponibilités</a>
        <a href="#">Baux & Paiements Sécurisés</a>
        <a href="#">Compteurs CEET & Charges</a>
    </nav>

    <div class="host-grid">
        <div>
            <?php foreach ($applications as $app): ?>
                <article class="panel candidate">
                    <div class="candidate__top">
                        <div class="who">
                            <img src="<?= e(asset('img/' . $app['avatar'])) ?>" alt="">
                            <div>
                                <h3><?= e($app['first_name'] . ' ' . $app['last_name']) ?> <span class="badge badge-ok">Vérifié</span></h3>
                                <p><?= e($app['program']) ?> · <?= e($app['school']) ?></p>
                                <p class="muted">Non-fumeur · Budget validé : <?= money((int) ($app['budget_max'] ?? 45000)) ?></p>
                            </div>
                        </div>
                        <div class="match"><?= (int) $app['match_score'] ?>% compatibilité</div>
                    </div>
                    <div class="candidate__actions">
                        <form method="post" action="<?= e(url('/hote/candidatures/' . $app['id'])) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="visit">
                            <button class="btn btn-dark" type="submit">Accepter pour visite</button>
                        </form>
                        <button class="btn btn-ghost" type="button">Dossier complet</button>
                        <button class="btn btn-ghost" type="button">Proposer créneau</button>
                        <form method="post" action="<?= e(url('/hote/candidatures/' . $app['id'])) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-ghost danger" type="submit">Décliner</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <aside>
            <?php if ($listing): ?>
                <article class="panel">
                    <p class="tiny">GESTION DU LOGEMENT · ACTIF</p>
                    <img class="side-photo" src="<?= e(asset('img/' . ($listing['cover'] ?? 'listing-1a.jpg'))) ?>" alt="">
                    <h3><?= e($listing['title']) ?></h3>
                    <ul class="kv">
                        <li><span>Loyer mensuel</span><strong><?= money((int) $listing['rent']) ?></strong></li>
                        <li><span>Colocataires en place</span><strong>2 étudiants UL</strong></li>
                    </ul>
                    <a class="btn btn-ghost btn-block" href="<?= e(url('/logement/' . $listing['id'])) ?>">Mettre à jour l’annonce</a>
                </article>
            <?php endif; ?>
            <article class="panel">
                <h3>Compteur Cash Power CEET Nº 042-8819</h3>
                <p class="ceet"><strong>142,5 kWh</strong> solde estimé restant</p>
                <p class="muted">Autonomie approx. 15 jours · Recharge récente : 15 000 FCFA</p>
            </article>
            <article class="panel">
                <h3>Prochaines visites</h3>
                <ul class="visit-list">
                    <?php foreach ($visits as $v): ?>
                        <li>
                            <strong><?= date('d M Y · H:i', strtotime($v['scheduled_at'])) ?></strong>
                            <span><?= e($v['first_name'] . ' ' . $v['last_name']) ?> — <?= e($v['program']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="map-frame">
                    <iframe title="Point de repère" src="https://www.openstreetmap.org/export/embed.html?bbox=1.16,6.16,1.25,6.20&layer=mapnik" loading="lazy"></iframe>
                </div>
            </article>
        </aside>
    </div>
</section>
