<section class="auth-card panel">
    <h1>Connexion à ColocLomé</h1>
    <p>Comptes démo : <code>etudiant@coloclome.tg</code> ou <code>koffi.mensah@coloclome.tg</code> — mot de passe <code>password</code></p>
    <form method="post" action="<?= e(url('/connexion')) ?>">
        <?= csrf_field() ?>
        <label>E-mail<input type="email" name="email" required value="etudiant@coloclome.tg"></label>
        <label>Mot de passe<input type="password" name="password" required value="password"></label>
        <button class="btn btn-dark btn-block" type="submit">Se connecter</button>
    </form>
    <p><a href="<?= e(url('/inscription')) ?>">Créer un compte étudiant ou hôte</a></p>
</section>
