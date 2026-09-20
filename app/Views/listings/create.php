<nav class="crumbs">Accueil / Espace Bailleur & Colocataire / Déposer une annonce de colocation</nav>
<div class="publish-head">
    <div>
        <p class="kicker">DOSSIER DE PUBLICATION NORMALISÉ</p>
        <h1>Déposer une annonce de colocation</h1>
    </div>
    <ol class="steps">
        <li class="is-on">1 Caractéristiques</li>
        <li>2 Profil & Règles</li>
        <li>3 Certification CEET</li>
    </ol>
</div>

<form class="publish" method="post" action="<?= e(url('/publier')) ?>" data-publish>
    <?= csrf_field() ?>
    <div class="publish__main">
        <section class="panel">
            <h2>1. Localisation précise à Lomé</h2>
            <div class="form-grid">
                <label>Quartier certifié
                    <select name="neighborhood_id">
                        <?php foreach ($neighborhoods as $n): ?>
                            <option value="<?= (int) $n['id'] ?>"><?= e($n['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Temps de trajet vers Campus UL
                    <input type="number" name="walk_minutes" value="12" min="3" max="60">
                </label>
                <label class="full">Repère géographique public (Pas d’adresse privée complète)
                    <input type="text" name="landmark" placeholder="Ex. À 150m de la Pharmacie Nation, face Collège Protestant">
                </label>
            </div>
        </section>

        <section class="panel">
            <h2>2. Spécifications du bien et de la chambre</h2>
            <div class="form-grid">
                <label>Typologie d’habitation
                    <select name="housing_type">
                        <option>Villa partagée</option>
                        <option>Duplex</option>
                        <option>Appartement</option>
                    </select>
                </label>
                <label>Surface chambre (m²)
                    <input type="number" name="room_surface" value="16" step="0.5">
                </label>
                <label>Sanitaire & Eau
                    <select name="water_included">
                        <option value="1">Forage & Eau TdE interne commun</option>
                        <option value="0">Eau non incluse</option>
                    </select>
                </label>
            </div>
            <p>Équipements et meubles contractuels inclus</p>
            <div class="checks-inline">
                <label><input type="checkbox" checked> Lit + matelas 2 places</label>
                <label><input type="checkbox" checked> Bureau d’études + chaise</label>
                <label><input type="checkbox"> Armoire / Penderie</label>
                <label><input type="checkbox" name="fiber" value="1" checked> Climatiseur inverter</label>
                <label><input type="checkbox"> Brasseur d’air plafonnier</label>
                <label><input type="checkbox"> Serrure individuelle certifiée</label>
            </div>
        </section>

        <section class="panel">
            <h2>3. Énergie, Eau & Connectivité togolaise</h2>
            <div class="form-grid">
                <label>Compteur électrique CEET
                    <select name="cash_power">
                        <option value="individual">Cash Power individuel / sous-compteur</option>
                        <option value="shared">Compteur collectif partagé</option>
                    </select>
                </label>
                <label>Accès Internet étudiant
                    <select>
                        <option>Fibre Moov Africa / Wifi 4G</option>
                        <option>Togocom Fibre</option>
                    </select>
                </label>
            </div>
        </section>

        <section class="panel">
            <h2>4. Fixation du Loyer & Cadre Légal de Caution</h2>
            <div class="form-grid">
                <label>Loyer mensuel individuel (FCFA)
                    <input type="number" name="rent" value="35000" min="10000" data-rent>
                </label>
                <label>Dépôt de garantie légal
                    <select name="deposit_months">
                        <option value="2">2 mois de loyer (70 000 XOF)</option>
                        <option value="1">1 mois de loyer</option>
                    </select>
                </label>
                <label>Prise d’effet du bail
                    <input type="date" name="available_from" value="<?= date('Y-m-d') ?>">
                </label>
                <label>Titre de l’annonce
                    <input type="text" name="title" value="Chambre étudiante vérifiée — Lomé">
                </label>
                <label class="full">Description
                    <textarea name="description" rows="4">Chambre calme proche campus, bail ColocLomé, caution séquestrée.</textarea>
                </label>
            </div>
        </section>

        <section class="panel">
            <h2>5. Critères de colocation & Rythme de travail</h2>
            <div class="form-grid">
                <label>Filière d’études prioritaire
                    <select name="lifestyle">
                        <option value="calm">Priorité calme & révisions</option>
                        <option value="social">Ambiance conviviale / sociale</option>
                    </select>
                </label>
                <label>Nombre de chambres
                    <input type="number" name="rooms_total" value="3" min="1">
                </label>
            </div>
        </section>

        <section class="panel">
            <h2>6. Documentation visuelle & Titre d’occupation</h2>
            <div class="dropzone" data-dropzone>
                <p><strong>Glissez-déposez vos photos</strong> ou parcourez vos fichiers<br><small>JPEG, PNG, WebP jusqu’à 5 Mo. Cadrage 4:3 pour rester lisible sur mobile.</small></p>
                <div class="thumbs">
                    <img src="<?= e(asset('img/listing-1a.jpg')) ?>" alt="">
                    <img src="<?= e(asset('img/listing-1b.jpg')) ?>" alt="">
                    <button type="button" class="thumb-add">Ajouter une photo</button>
                </div>
            </div>
        </section>

        <div class="publish-actions">
            <label class="check"><input type="checkbox" checked> Brouillon synchronisé automatiquement</label>
            <button class="btn btn-ghost" type="button">Enregistrer le brouillon</button>
            <button class="btn btn-dark" type="submit">Soumettre pour vérification technique</button>
        </div>
    </div>

    <aside class="sticky-col">
        <article class="panel">
            <header class="panel__head"><h3>Synthèse financière</h3><span>Division XOF</span></header>
            <ul class="kv" data-finance>
                <li><span>Loyer de base unitaire</span><strong data-base>35 000 FCFA</strong></li>
                <li><span>Caution consignée (×2)</span><strong data-caution>70 000 FCFA</strong></li>
                <li class="total"><span>Engagement initial rentrée 2024</span><strong data-total>105 000 FCFA</strong></li>
            </ul>
        </article>
        <article class="panel soft">
            <h3>Charte d’Annonceur ColocLomé</h3>
            <p>En publiant, vous attestez que le logement est réellement disponible, que le compteur CEET est en bon état et que le loyer n’inclut aucune commission occulte.</p>
        </article>
        <article class="panel">
            <h3>Recommandations d’attribution</h3>
            <ul class="bullets">
                <li>Présentez le régime électrique : parts des étudiants et sous-compteurs.</li>
                <li>Photos du bureau d’étude, cuisine et cour.</li>
                <li>Délais de visitation : créneaux 16h-18h en semaine.</li>
            </ul>
        </article>
    </aside>
</form>
