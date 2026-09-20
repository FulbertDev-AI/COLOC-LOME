<?php
$currentId = (int) ($conversation['id'] ?? 0);
$formatter = static function (?string $dt): string {
    if (!$dt) return '';
    return date('H:i', strtotime($dt));
};
?>
<section class="workspace">
    <header class="workspace__head">
        <div>
            <h1>Espace Étudiant & Candidatures</h1>
            <span class="badge">Session Active</span>
        </div>
        <p class="muted">Matricule <?= e($user['matricule'] ?? 'UL-2024-8841') ?> · Profil Certifié</p>
    </header>
    <nav class="tabs">
        <a class="is-active" href="<?= e(url('/messages')) ?>">Messages & Demandes <em>2</em></a>
        <a href="<?= e(url('/candidatures')) ?>">Mes Visites Programmées <em>1</em></a>
        <a href="<?= e(url('/candidatures')) ?>">Candidatures Envoyées</a>
        <a href="<?= e(url('/recherche')) ?>">Annonces Sauvegardées</a>
    </nav>

    <div class="msg-layout">
        <aside class="inbox panel">
            <p class="tiny">BOÎTE DE RÉCEPTION</p>
            <input type="search" placeholder="Rechercher un colocataire, quartier…" class="search-mini">
            <div class="seg">
                <button type="button" class="is-on">Tous (<?= count($inbox) ?>)</button>
                <button type="button">À traiter</button>
                <button type="button">Visites</button>
            </div>
            <div class="threads">
                <?php foreach ($inbox as $thread):
                    $isStudent = is_student();
                    $name = $isStudent
                        ? $thread['host_first'] . ' ' . $thread['host_last']
                        : $thread['student_first'] . ' ' . $thread['student_last'];
                    $avatar = $isStudent ? $thread['host_avatar'] : $thread['student_avatar'];
                    ?>
                    <a class="thread <?= (int) $thread['id'] === $currentId ? 'is-on' : '' ?>" href="<?= e(url('/messages/' . $thread['id'])) ?>">
                        <img src="<?= e(asset('img/' . $avatar)) ?>" alt="">
                        <div>
                            <div class="thread__top">
                                <strong><?= e($name) ?></strong>
                                <time><?= $formatter($thread['last_at'] ?? null) ?></time>
                            </div>
                            <small><?= e($thread['neighborhood']) ?></small>
                            <p><?= e(mb_strimwidth($thread['last_body'] ?? '', 0, 70, '…')) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
                <?php if (!$inbox): ?>
                    <p class="muted">Aucun message pour le moment.</p>
                <?php endif; ?>
            </div>
            <p class="legal-note">Vos échanges sur ColocLomé ont valeur précontractuelle selon la charte togolaise d’hébergement étudiant.</p>
        </aside>

        <section class="chat">
            <?php if ($conversation && $peer): ?>
                <header class="chat__head panel">
                    <div class="who">
                        <div>
                            <h2><?= e($peer['first_name'] . ' ' . $peer['last_name']) ?></h2>
                            <p class="muted"><?= e($conversation['title']) ?> · <?= money((int) $conversation['rent']) ?>/mois charges comprises</p>
                        </div>
                    </div>
                    <div class="chat__actions">
                        <a class="btn btn-ghost" href="<?= e(url('/logement/' . $conversation['listing_id'])) ?>">Voir l’annonce</a>
                        <button class="btn btn-ghost" type="button">Modifier la visite</button>
                    </div>
                </header>

                <?php if ($visit): ?>
                    <div class="visit-banner">
                        <div>
                            <p class="tiny">VISITE CONFIRMÉE PAR LE RÉFÉRENT · Code #<?= e($visit['code']) ?></p>
                            <strong><?= date('l d F Y \à H:i', strtotime($visit['scheduled_at'])) ?></strong>
                            <p><?= e($visit['location']) ?></p>
                        </div>
                        <form method="post" action="<?= e(url('/visites/' . $visit['id'] . '/confirmer')) ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-dark" type="submit">Confirmer ma présence</button>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="bubbles">
                    <?php foreach ($messages as $msg):
                        $mine = (int) $msg['sender_id'] === (int) $user['id'];
                        ?>
                        <article class="bubble <?= $mine ? 'mine' : '' ?>">
                            <?php if (!$mine): ?>
                                <img src="<?= e(asset('img/' . $msg['avatar'])) ?>" alt="">
                            <?php endif; ?>
                            <div>
                                <small><?= e($msg['first_name']) ?> · <?= $formatter($msg['created_at']) ?></small>
                                <p><?= nl2br(e($msg['body'])) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <div class="notice">
                        <strong>AVIS DE PROTECTION ÉTUDIANTE · RÉPUBLIQUE TOGOLAISE</strong>
                        <p>Rappel ColocLomé : les visites doivent impérativement s’effectuer pendant les heures diurnes. Aucun transfert d’argent mobile (T-Money, Mixx) n’est autorisé sans émission préalable d’un bail normalisé vérifié par la plateforme.</p>
                    </div>
                </div>

                <form class="composer" method="post" action="<?= e(url('/messages/' . $conversation['id'])) ?>">
                    <?= csrf_field() ?>
                    <textarea name="body" rows="2" placeholder="Écrire un message à <?= e($peer['first_name']) ?>…" required></textarea>
                    <div class="composer__bar">
                        <small>PDF, JPEG autorisés (max 5 Mo) · Chiffré de bout en bout</small>
                        <button class="btn btn-dark" type="submit">Envoyer</button>
                    </div>
                </form>

                <div class="kpi-row">
                    <article><small>LOYER MENSUEL</small><strong><?= money((int) $conversation['rent']) ?></strong><span>Eau de forage & ordures inclus</span></article>
                    <article><small>CAUTION RENTRÉE</small><strong><?= (int) $conversation['deposit_months'] ?> Mois</strong><span>Dépôt séquestré ColocLomé</span></article>
                    <article><small>DISTANCE CAMPUS</small><strong><?= (int) $conversation['walk_minutes'] ?> min</strong><span>Ligne directe Taxi-moto / Bus UL</span></article>
                </div>
            <?php else: ?>
                <div class="panel empty">Sélectionnez une conversation.</div>
            <?php endif; ?>
        </section>
    </div>
</section>
