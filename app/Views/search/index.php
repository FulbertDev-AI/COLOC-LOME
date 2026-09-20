<div class="search-layout">
    <header class="page-head">
        <div>
            <h1><?= count($listings) ?> logements vérifiés disponibles autour du Campus de Lomé</h1>
            <p class="chips">
                <span>FILTRES ACTIFS</span>
                <em>Agoè & Adidogomé</em>
                <em>≤ <?= money((int) ($filters['budget_max'] ?? 50000)) ?></em>
                <em>Baux contrôlés</em>
            </p>
        </div>
        <label class="sort">
            Trier par
            <select>
                <option>Score de compatibilité décroissant</option>
                <option>Loyer croissant</option>
            </select>
        </label>
    </header>

    <div class="split">
        <aside class="filters panel">
            <div class="filters__head">
                <strong>Critères de recherche</strong>
                <a href="<?= e(url('/recherche')) ?>">Réinitialiser</a>
            </div>
            <form method="get" action="<?= e(url('/recherche')) ?>">
                <fieldset>
                    <legend>Quartier (Lomé)</legend>
                    <?php foreach ($neighborhoods as $n): ?>
                        <label class="check">
                            <input type="checkbox" name="quartier[]" value="<?= (int) $n['id'] ?>"
                                <?= in_array((string) $n['id'], array_map('strval', $filters['neighborhoods'] ?? []), true) ? 'checked' : '' ?>>
                            <?= e($n['name']) ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
                <fieldset>
                    <legend>Budget mensuel max</legend>
                    <input type="range" name="budget_max" min="20000" max="80000" step="1000" value="<?= (int) ($filters['budget_max'] ?? 50000) ?>" data-range-output>
                    <div class="range-labels"><span>20 000</span><strong data-range-value><?= money((int) ($filters['budget_max'] ?? 50000)) ?></strong></div>
                    <input type="hidden" name="budget_min" value="20000">
                </fieldset>
                <fieldset>
                    <legend>Accessibilité campus</legend>
                    <label class="check"><input type="checkbox" name="walk" value="1" <?= !empty($filters['walk']) ? 'checked' : '' ?>> &lt; 10 min à pied (Zone direct UL)</label>
                    <label class="check"><input type="checkbox" checked> &lt; 15 min moto-zemidjan (≤ 300 F)</label>
                    <label class="check"><input type="checkbox"> Proche Arrêt Bus SOTRAL Ligne 2/4</label>
                </fieldset>
                <fieldset>
                    <legend>Commodités indispensables</legend>
                    <label class="check"><input type="checkbox" name="cash_power" value="1" <?= !empty($filters['cash_power']) ? 'checked' : '' ?>> Compteur Cash Power individuel</label>
                    <label class="check"><input type="checkbox" name="eau" value="1" <?= !empty($filters['water']) ? 'checked' : '' ?>> Forage ou Eau TdE permanente</label>
                    <label class="check"><input type="checkbox" name="fibre" value="1" <?= !empty($filters['fiber']) ? 'checked' : '' ?>> Fibre optique Togocom / Moov</label>
                </fieldset>
                <fieldset>
                    <legend>Cohabitation & profil</legend>
                    <label class="check"><input type="checkbox" checked> Non-fumeur impératif</label>
                    <label class="check"><input type="checkbox" checked> Environnement silencieux (révisions)</label>
                    <label class="check"><input type="checkbox"> Étudiant même faculté / cycle</label>
                    <label class="check"><input type="checkbox"> Colocation strictement féminine</label>
                </fieldset>
                <button class="btn btn-dark btn-block" type="submit">Appliquer les filtres</button>
                <p class="muted center"><?= count($listings) ?> logements correspondent à ces critères</p>
            </form>
        </aside>

        <div class="results">
            <div class="alert-banner">
                <strong>Règle de sécurité ColocLomé</strong>
                <span>Ne versez jamais d’acompte ni de caution par T-Money ou Moov Money avant la signature du contrat normalisé avec vérification physique des lieux.</span>
                <a href="#">Lire la charte</a>
            </div>

            <?php foreach ($listings as $item): ?>
                <article class="result-card">
                    <a class="result-card__media" href="<?= e(url('/logement/' . $item['id'])) ?>">
                        <img src="<?= e(asset('img/' . listing_photo($item['cover'] ?? null))) ?>" alt="">
                    </a>
                    <div class="result-card__body">
                        <p class="meta-row">
                            <span class="badge badge-ok">Certifié conforme CEET</span>
                            <span class="meta-loc"><?= strtoupper(e($item['neighborhood'])) ?></span>
                        </p>
                        <div class="result-card__title">
                            <h2><a href="<?= e(url('/logement/' . $item['id'])) ?>"><?= e($item['title']) ?></a></h2>
                            <strong><?= number_format((int) $item['rent'], 0, ',', ' ') ?> <span>FCFA/mois</span></strong>
                        </div>
                        <p class="pills">
                            <span><?= (int) $item['walk_minutes'] ?> min du Campus UL</span>
                            <span><?= $item['cash_power'] === 'individual' ? 'Cash Power individuel' : 'CEET partagée' ?></span>
                            <?php if (!empty($item['fiber'])): ?><span>Fibre Togocom inclus</span><?php endif; ?>
                            <?php if (!empty($item['water_included'])): ?><span>Eau / forage</span><?php endif; ?>
                        </p>
                        <p class="result-excerpt"><?= e(mb_strimwidth(strip_tags($item['description']), 0, 170, '…')) ?></p>
                        <div class="result-card__foot">
                            <div class="who">
                                <img src="<?= e(asset('img/' . $item['avatar'])) ?>" alt="">
                                <div>
                                    <strong><?= e($item['first_name'] . ' ' . mb_substr($item['last_name'], 0, 1) . '.') ?></strong>
                                    <small><?= e($item['program']) ?></small>
                                </div>
                            </div>
                            <div class="match"><?= (int) $item['match_score'] ?>% Match</div>
                            <a class="btn btn-dark" href="<?= e(url('/logement/' . $item['id'])) ?>">Voir l’annonce</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

            <div class="pager">
                <span>Affichage de 1 - <?= count($listings) ?> sur <?= count($listings) ?> logements</span>
                <div class="pager__btns">
                    <button type="button" class="is-active">1</button>
                </div>
            </div>
        </div>
    </div>
</div>
