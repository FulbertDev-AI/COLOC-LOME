<?php
$photos = $listing['photos'] ?: [];
$fallbacks = ['listing-1a.jpg', 'listing-1b.jpg', 'listing-1c.jpg', 'listing-2.jpg', 'listing-5.jpg'];
$i = 0;
while (count($photos) < 3) {
    $photos[] = ['filename' => $fallbacks[$i % count($fallbacks)], 'alt' => 'Vue du logement'];
    $i++;
}
$ref = 'UL-' . strtoupper(substr((string) ($listing['neighborhood_slug'] ?? 'LOM'), 0, 3)) . '-2024-' . str_pad((string) $listing['id'], 3, '0', STR_PAD_LEFT);
$walk = (int) $listing['walk_minutes'];
$power = $listing['cash_power'] === 'individual' ? 'Charges CEET Cash Power individuelles' : 'Recharge CEET partagée entre colocataires';
?>
<nav class="crumbs">
    <a href="<?= e(url('/')) ?>">Accueil</a>
    <span>/</span>
    <a href="<?= e(url('/recherche')) ?>">Rechercher Lomé</a>
    <span>/</span>
    <a href="<?= e(url('/recherche?quartier[]=' . (int) $listing['neighborhood_id'])) ?>"><?= e($listing['neighborhood']) ?></a>
    <span>/</span>
    <em><?= e(mb_strimwidth($listing['title'], 0, 42, '…')) ?></em>
</nav>

<section class="detail-head">
    <div>
        <p class="badge badge-ok">Bail Normalisé · REF <?= e($ref) ?></p>
        <h1><?= e($listing['title']) ?></h1>
        <p class="muted detail-loc">
            <?= e($listing['neighborhood']) ?>
            · À <?= $walk ?> minutes du Campus UL
            · <?= $listing['water_included'] ? 'Eau de forage & TdE' : 'Eau non incluse' ?>
        </p>
    </div>
    <div class="detail-price">
        <strong><?= number_format((int) $listing['rent'], 0, ',', ' ') ?> <span>FCFA / mois</span></strong>
        <small><?= e($power) ?></small>
    </div>
</section>

<div class="gallery">
    <img class="gallery__main" src="<?= e(asset('img/' . listing_photo($photos[0]['filename']))) ?>" alt="<?= e($photos[0]['alt'] ?? '') ?>">
    <div class="gallery__side">
        <img src="<?= e(asset('img/' . listing_photo($photos[1]['filename']))) ?>" alt="">
        <div class="gallery__more">
            <img src="<?= e(asset('img/' . listing_photo($photos[2]['filename']))) ?>" alt="">
            <span><?= max(count($listing['photos']), 3) ?> photos</span>
        </div>
    </div>
</div>

<div class="detail-grid">
    <div class="detail-main">
        <section class="panel">
            <header class="panel__head">
                <h2>Fiche Technique de l’Hébergement</h2>
                <span>AUDIT TECHNIQUE 2024</span>
            </header>
            <div class="spec-grid spec-grid--4">
                <div class="spec">
                    <small>Surface privative</small>
                    <strong><?= rtrim(rtrim(number_format((float) $listing['room_surface'], 1, ',', ' '), '0'), ',') ?> m²</strong>
                </div>
                <div class="spec">
                    <small>Typologie</small>
                    <strong><?= e($listing['housing_type']) ?></strong>
                </div>
                <div class="spec">
                    <small>Pièces du logement</small>
                    <strong><?= (int) $listing['rooms_total'] ?> chambres</strong>
                </div>
                <div class="spec">
                    <small>Électricité CEET</small>
                    <strong><?= $listing['cash_power'] === 'individual' ? 'Cash Power individuel' : 'Compteur partagé' ?></strong>
                </div>
            </div>
            <h3>Équipements et espaces accessibles</h3>
            <ul class="equip">
                <li>Terrasse / balcon ventilé</li>
                <li>Réfrigérateur commun</li>
                <li>Wifi Togocom / Moov</li>
                <li>Bureau d’études fourni</li>
                <li><?= $listing['fiber'] ? 'Fibre optique inclusive' : 'Connexion 4G partagée' ?></li>
                <li><?= $listing['water_included'] ? 'Eau forage / TdE' : 'Eau à la charge du locataire' ?></li>
            </ul>
        </section>

        <section class="panel">
            <h2>Description & Environnement d’Étude</h2>
            <p class="prose"><?= nl2br(e($listing['description'])) ?></p>
        </section>

        <section class="panel">
            <header class="panel__head">
                <h2>Répartition des Charges & Dispositif CEET</h2>
            </header>
            <div class="split-2">
                <div class="soft">
                    <h3>Inclus dans le loyer (fixe)</h3>
                    <ul class="ticks">
                        <li>Eau de forage et ordures</li>
                        <li>Gardiennage / clôture</li>
                        <li>Entretien des parties communes</li>
                    </ul>
                </div>
                <div class="soft">
                    <h3>Compteur Cash Power (variable)</h3>
                    <ul class="ticks">
                        <li><?= $listing['cash_power'] === 'individual' ? 'Sous-compteur chambre individuelle' : 'Partage mensuel T-Money / Mixx by Yas' ?></li>
                        <li>Recharge mobile money autorisée après bail</li>
                        <li>Partage équitable des communs</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="panel">
            <h2>Critères d’Admission & Cohabitation</h2>
            <div class="criteria">
                <article>
                    <strong>Calme & révisions</strong>
                    <p><?= $listing['lifestyle'] === 'calm' ? 'Horaires réguliers, silence en période d’examens.' : 'Ambiance conviviale acceptée hors périodes de partiels.' ?></p>
                </article>
                <article>
                    <strong>Entretien collectif</strong>
                    <p>Cuisine, terrasse et sanitaires communs nettoyés selon un roulement hebdomadaire.</p>
                </article>
                <article>
                    <strong>Logement non-fumeur</strong>
                    <p><?= empty($listing['smoker']) ? 'Tabac interdit à l’intérieur de la villa.' : 'Fumeur accepté en extérieur uniquement.' ?></p>
                </article>
                <article>
                    <strong>Statut universitaire requis</strong>
                    <p>Carte d’étudiant UL / FSS / FDD / IAI à jour, vérifiée par ColocLomé.</p>
                </article>
            </div>
        </section>

        <section class="panel">
            <header class="panel__head">
                <h2>Localisation & Mobilité Universitaire</h2>
                <span><?= $walk ?> min campus</span>
            </header>
            <div class="map-frame map-frame--wide">
                <iframe title="Carte Lomé" src="https://www.openstreetmap.org/export/embed.html?bbox=1.16,6.12,1.28,6.21&layer=mapnik" loading="lazy"></iframe>
            </div>
        </section>
    </div>

    <aside class="sticky-col">
        <article class="panel host-card">
            <div class="who who--lg">
                <img src="<?= e(asset('img/' . $listing['avatar'])) ?>" alt="">
                <div>
                    <strong><?= e($listing['first_name'] . ' ' . $listing['last_name']) ?></strong>
                    <small><?= e($listing['program']) ?></small>
                    <?php if ($listing['host_verified']): ?>
                        <span class="badge badge-ok">Identité UL 2024-2025 certifiée</span>
                    <?php endif; ?>
                </div>
            </div>
            <p class="avail">Chambre disponible dès le <strong><?= $listing['available_from'] ? date('d/m/Y', strtotime((string) $listing['available_from'])) : '01/11/2024' ?></strong></p>
            <div class="match-lg">
                <span>Indice de compatibilité</span>
                <strong><?= (int) $listing['match_score'] ?>%</strong>
            </div>
            <ul class="ticks ticks--compact">
                <li>Même rythme de révision</li>
                <li>Quartier campus prioritaire</li>
                <li>Budget dans la fourchette UL</li>
            </ul>
            <form method="post" action="<?= e(url('/candidatures')) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="listing_id" value="<?= (int) $listing['id'] ?>">
                <button class="btn btn-dark btn-block" type="submit">Contacter l’hôte / Demander visite</button>
            </form>
            <a class="btn btn-ghost btn-block" href="<?= e(url('/messages')) ?>">Ouvrir la messagerie sécurisée</a>
        </article>
        <article class="panel">
            <h3>Synthèse financière 12 mois</h3>
            <ul class="kv">
                <li><span>Loyer 12 mois</span><strong><?= money((int) $listing['rent'] * 12) ?></strong></li>
                <li><span>Caution <?= (int) $listing['deposit_months'] ?> mois</span><strong><?= money((int) $listing['rent'] * (int) $listing['deposit_months']) ?></strong></li>
                <li class="total"><span>Total engagement estimé</span><strong><?= money((int) $listing['rent'] * (12 + (int) $listing['deposit_months'])) ?></strong></li>
            </ul>
        </article>
    </aside>
</div>
