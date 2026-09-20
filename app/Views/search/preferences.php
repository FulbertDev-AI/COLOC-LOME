<section class="pref">
    <p class="kicker">CONFIGURATION EXPRESS · ÉTAPE 1 SUR 1 — VOS PRÉFÉRENCES DE LOGEMENT</p>
    <h1>Trouvons votre colocataire idéal et votre logement à Lomé</h1>
    <p class="lede">Précisez votre budget et votre secteur universitaire pour afficher les correspondances vérifiées.</p>

    <form class="pref-form" method="post" action="<?= e(url('/preferences')) ?>">
        <?= csrf_field() ?>
        <article class="panel">
            <header class="panel__head">
                <h2>1. Dans quel quartier ou zone d’études cherchez-vous ?</h2>
                <span>Sélection multiple possible</span>
            </header>
            <div class="choice-grid">
                <?php foreach ($neighborhoods as $i => $n): ?>
                    <label class="choice <?= $i === 0 ? 'is-on' : '' ?>">
                        <input type="checkbox" name="quartier[]" value="<?= (int) $n['id'] ?>" <?= $i === 0 ? 'checked' : '' ?>>
                        <strong><?= e($n['name']) ?></strong>
                        <small><?= e($n['details']) ?></small>
                    </label>
                <?php endforeach; ?>
            </div>
        </article>

        <article class="panel">
            <header class="panel__head">
                <h2>2. Quel est votre budget mensuel maximum pour le loyer ?</h2>
                <span>Charges non incluses</span>
            </header>
            <div class="choice-grid choice-grid--4">
                <?php
                $budgets = ['lt25' => ['< 25 000', 'FCFA / MOIS'], '25-40' => ['25 000 - 40 000', 'FCFA / MOIS'], '40-60' => ['40 000 - 60 000', 'FCFA / MOIS'], 'gt60' => ['> 60 000', 'FCFA / MOIS']];
                foreach ($budgets as $val => $label): ?>
                    <label class="choice choice--radio <?= $val === '25-40' ? 'is-on' : '' ?>">
                        <input type="radio" name="budget" value="<?= $val ?>" <?= $val === '25-40' ? 'checked' : '' ?>>
                        <strong><?= $label[0] ?></strong>
                        <small><?= $label[1] ?></small>
                    </label>
                <?php endforeach; ?>
            </div>
        </article>

        <article class="panel">
            <header class="panel__head">
                <h2>3. Votre rythme de vie universitaire</h2>
                <span>Pour affiner la compatibilité</span>
            </header>
            <div class="choice-grid">
                <label class="choice is-on">
                    <input type="radio" name="lifestyle" value="calm" checked>
                    <strong>Priorité calme & révisions</strong>
                    <small>Horaires réguliers, révisions nocturnes silencieuses en période d’examens.</small>
                </label>
                <label class="choice">
                    <input type="radio" name="lifestyle" value="social">
                    <strong>Sociable & partage</strong>
                    <small>Repas pris ensemble, esprit d’entraide et moments d’échanges fréquents.</small>
                </label>
                <label class="choice">
                    <input type="radio" name="lifestyle" value="party">
                    <strong>Soirs et week-ends</strong>
                    <small>Présence réduite en journée en raison des cours magistraux ou d’un job étudiant.</small>
                </label>
            </div>
        </article>

        <article class="panel">
            <header class="panel__head">
                <h2>4. Établissement ou faculté d’affectation</h2>
            </header>
            <label class="field">
                <span>Permet de regrouper les trajets en taxi-moto ou à pied vers vos salles de cours.</span>
                <input type="text" name="school" value="Université de Lomé (UL) - FASEG">
            </label>
        </article>

        <button class="btn btn-dark btn-xl" type="submit">Voir mes 14 colocations compatibles →</button>
        <p class="center muted">Vos données sont strictement réservées à la mise en relation sécurisée entre étudiants.</p>
    </form>

    <div class="cert-bar">
        <div>
            <strong>Processus de vérification institutionnelle</strong>
            <p>Chaque profil hébergeant ou postulant fait l’objet d’une vérification de carte d’étudiant.</p>
        </div>
        <span class="chip">CERTIFICATION LOMÉ 2024</span>
    </div>
</section>
