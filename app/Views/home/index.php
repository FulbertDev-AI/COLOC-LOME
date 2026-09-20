<section class="hero">
    <div class="hero__banner" style="background-image: url('<?= e(asset('img/hero-lome.jpg')) ?>')">
        <div class="hero__content">
            <h1>Trouvez une colocation étudiante vérifiée à Lomé en toute sérénité.</h1>
            <p class="lede">Zéro frais d’agence informels. Contrats transparents, répartition des charges CEET claire et matching de compatibilité par campus universitaire.</p>
            <div class="hero__cta">
                <a class="btn btn-light" href="<?= e(url('/recherche')) ?>">Trouver une colocation</a>
                <a class="btn btn-ghost-light" href="<?= e(url('/publier')) ?>">Proposer une chambre</a>
            </div>
        </div>
    </div>

    <div class="hero__tools">
        <form class="search-hero" action="<?= e(url('/recherche')) ?>" method="get">
            <label>
                <span>Quartier ou Campus à Lomé</span>
                <select name="quartier[]">
                    <option value="">Tous les secteurs prioritaires</option>
                    <option value="1">Campus Universitaire (UL)</option>
                    <option value="2">Adidogomé</option>
                    <option value="3">Agoè-Nyivé</option>
                    <option value="4">Tokoin</option>
                </select>
            </label>
            <label>
                <span>Type d’hébergement</span>
                <select name="type">
                    <option>Tous les formats</option>
                    <option>Villa partagée</option>
                    <option>Chambre autonome</option>
                </select>
            </label>
            <label>
                <span>Budget max (FCFA / mois)</span>
                <input type="number" name="budget_max" value="50000" min="10000" step="1000">
            </label>
            <button class="btn btn-dark" type="submit">Rechercher</button>
        </form>

        <div class="stat-row">
            <article><strong>142</strong><span>Annonces actives Campus UL</span></article>
            <article><strong>0 FCFA</strong><span>Frais d’agence informels</span></article>
            <article><strong>100%</strong><span>Baux vérifiés par l’équipe</span></article>
            <article><strong>98,4%</strong><span>Taux de satisfaction locative</span></article>
        </div>
    </div>
</section>

<section class="protocol">
    <p class="section-kicker">PROTOCOLE DE RÉGULATION</p>
    <h2>Un cadre strict pour éradiquer l’opacité locative à Lomé</h2>
    <div class="card-3">
        <article class="panel protocol-card">
            <span class="protocol-no">01</span>
            <h3>Logements et Propriétaires Vérifiés</h3>
            <p>Chaque habitation fait l’objet d’un contrôle photos, surface, visibilité de l’installation électrique, compteur Cash Power (CEET) et d’un entretien confirmé des colocataires d’état des lieux.</p>
        </article>
        <article class="panel protocol-card">
            <span class="protocol-no">02</span>
            <h3>Zéro Commission Abusive</h3>
            <p>Suppression intégrale des « introducteurs » et des frais de visite informels. La relation contractuelle est directe, encadrée par un bail normalisé avec consigne de Cash Power et caution séquestrée.</p>
        </article>
        <article class="panel protocol-card">
            <span class="protocol-no">03</span>
            <h3>Matching Étudiant Compatible</h3>
            <p>Algorithme structuré autour de l’établissement universitaire fréquenté (UL, FSS/FDD, IAI, ESA, EAMAU), le campus académique, les horaires de révision et les règles de vie en colocation.</p>
        </article>
    </div>
</section>

<section class="featured">
    <div class="section-head">
        <div>
            <p class="section-kicker">INVENTAIRE DE PROXIMITÉ</p>
            <h2>Colocations récemment vérifiées à proximité des facultés</h2>
        </div>
        <a class="btn btn-ghost" href="<?= e(url('/recherche')) ?>">Consulter l’intégralité UL/FSS</a>
    </div>
    <div class="listing-cards">
        <?php foreach ($listings as $item): ?>
            <article class="listing-card">
                <a href="<?= e(url('/logement/' . $item['id'])) ?>">
                    <img src="<?= e(asset('img/' . listing_photo($item['cover'] ?? null))) ?>" alt="">
                </a>
                <div class="listing-card__body">
                    <p class="muted"><?= e($item['neighborhood']) ?> · <?= (int) $item['room_surface'] ?> m² · Bail certifié</p>
                    <h3><?= e(mb_strimwidth($item['title'], 0, 58, '…')) ?></h3>
                    <p class="price"><?= number_format((int) $item['rent'], 0, ',', ' ') ?> FCFA / mois</p>
                    <a class="btn btn-dark btn-block" href="<?= e(url('/logement/' . $item['id'])) ?>">Examiner le bail & visiter</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="map-block">
    <p class="section-kicker">IMPLANTATION & MOBILITÉ</p>
    <h2>Implantations stratégiques autour des pôles universitaires</h2>
    <div class="map-grid">
        <ul class="zones">
            <li><strong>Zone Nord — Campus Universitaire & Agoè</strong><span>Lignes Sotral, zemidjan et 12 min à pied du Campus Nord.</span></li>
            <li><strong>Zone Centre — Tokoin & Faculté de Santé</strong><span>Accès CHU Sylvanus Olympio et FSS.</span></li>
            <li><strong>Zone Ouest — Adidogomé & Écoles d’Ingénieurs</strong><span>IPNETP, EAMAU, villas étudiantes sécurisées.</span></li>
        </ul>
        <div class="map-frame">
            <iframe title="Carte Lomé" src="https://www.openstreetmap.org/export/embed.html?bbox=1.15,6.10,1.32,6.22&layer=mapnik" loading="lazy"></iframe>
        </div>
    </div>
</section>

<section class="cta-bar">
    <div>
        <h2>Vous libérez une chambre pour la rentrée ?</h2>
        <p>Publiez une colocation vérifiée en moins de 10 minutes, bail pré-rempli inclus, séquestration du dépôt de caution et recensement des cautions.</p>
    </div>
    <div class="cta-actions">
        <a class="btn btn-light" href="<?= e(url('/publier')) ?>">Déposer une annonce d’étudiant</a>
        <a class="btn btn-ghost-light" href="<?= e(url('/connexion')) ?>">Guide du bailleur Lomé</a>
    </div>
</section>
