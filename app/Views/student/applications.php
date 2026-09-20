<?php
$open = 0; $visits = 0;
foreach ($applications as $a) {
    if (in_array($a['status'], ['pending', 'accepted', 'visit'], true)) $open++;
    if ($a['status'] === 'visit') $visits++;
}
?>
<section class="workspace">
    <header class="workspace__head">
        <div>
            <p class="tiny">PLATEFORME CENTRALE UL · ESPACE ÉTUDIANT · POSTULATIONS ACTIVES</p>
            <h1>Mes Candidatures & Postulations</h1>
            <p>Suivez l’état d’avancement en temps réel de vos demandes de colocation, vos invitations officielles aux visites de terrain et vos baux certifiés à Lomé.</p>
        </div>
        <div class="cta-actions">
            <a class="btn btn-ghost" href="<?= e(url('/recherche')) ?>">Exporter l’historique</a>
            <a class="btn btn-dark" href="<?= e(url('/preferences')) ?>">Nouvelle recherche</a>
        </div>
    </header>

    <div class="stats-4">
        <article class="panel"><small>Candidatures ouvertes</small><strong><?= $open ?></strong></article>
        <article class="panel"><small>Visites confirmées</small><strong><?= $visits ?></strong></article>
        <article class="panel"><small>Taux d’acceptation</small><strong>75%</strong></article>
        <article class="panel"><small>Caution séquestrée</small><strong>80 000 FCFA</strong></article>
    </div>

    <nav class="tabs">
        <a class="is-active" href="#">Toutes mes candidatures</a>
        <a href="#">Acceptées pour visite</a>
        <a href="#">En cours d’entretien</a>
        <a href="#">Non retenues</a>
    </nav>

    <?php foreach ($applications as $app):
        $statusMap = [
            'visit' => ['ACCEPTÉ POUR VISITE', 'ok'],
            'accepted' => ['CRÉNEAU PROPOSÉ', 'ok'],
            'pending' => ['EN COURS D’INSTRUCTION COLOCATAIRE', 'wait'],
            'rejected' => ['NON RETENU / FILE COLLECTIVE', 'off'],
        ];
        $st = $statusMap[$app['status']] ?? $statusMap['pending'];
        ?>
        <article class="panel app-card">
            <img src="<?= e(asset('img/' . ($app['cover'] ?? 'listing-1a.jpg'))) ?>" alt="">
            <div>
                <p class="badge badge-<?= $st[1] ?>"><?= $st[0] ?> · RÉF CL-2024-<?= str_pad((string) $app['id'], 4, '0', STR_PAD_LEFT) ?></p>
                <h2><?= e($app['title']) ?></h2>
                <p class="muted"><?= e($app['neighborhood']) ?> · <?= money((int) $app['rent']) ?> / mois · Compatibilité <?= (int) $app['match_score'] ?>%</p>
                <p>Référent : <?= e($app['first_name'] . ' ' . $app['last_name']) ?> · <?= e($app['program']) ?></p>
                <?php if (!empty($app['visit'])): ?>
                    <div class="soft">
                        Visite : <strong><?= date('d/m/Y H:i', strtotime($app['visit']['scheduled_at'])) ?></strong>
                        — <?= e($app['visit']['location']) ?>
                    </div>
                    <form method="post" action="<?= e(url('/visites/' . $app['visit']['id'] . '/confirmer')) ?>">
                        <?= csrf_field() ?>
                        <button class="btn btn-dark" type="submit">Confirmer ma présence</button>
                    </form>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</section>
