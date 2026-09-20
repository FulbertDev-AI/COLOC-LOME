<section class="auth-card panel">
    <h1>Créer un compte</h1>
    <form method="post" action="<?= e(url('/inscription')) ?>">
        <?= csrf_field() ?>
        <label>Prénom<input name="first_name" required></label>
        <label>Nom<input name="last_name" required></label>
        <label>E-mail<input type="email" name="email" required></label>
        <label>Mot de passe<input type="password" name="password" required minlength="8"></label>
        <label>Établissement<input name="school" placeholder="Université de Lomé"></label>
        <label>Filière<input name="program" placeholder="FASEG"></label>
        <label>Vous êtes
            <select name="role">
                <option value="student">Étudiant en recherche</option>
                <option value="host">Hôte / bailleur étudiant</option>
            </select>
        </label>
        <button class="btn btn-dark btn-block" type="submit">S’inscrire</button>
    </form>
</section>
