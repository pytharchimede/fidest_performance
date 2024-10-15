<nav class="navbar navbar-expand-lg fixed-top">
    <a data-intro="Bienvenue sur votre tableau de bord ! <?php echo $_SESSION['nom_personnel_tasks']; ?> 😊 À tout moment, retournez à l'accueil en cliquant sur ce bouton." class="navbar-brand" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i> <!-- Icône hamburger -->
    </button>

    <button data-intro="🆘 Je suis ton guide d'utilisation ! Je me lancerai chaque fois que tu cliqueras sur ce bouton." id="startIntro">Besoin d'aide ?</button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li data-intro="🏠 ...ou sur celui-ci pour revenir à l'accueil" class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
            <li data-intro="👥 Voici la liste du personnel et le rapport des présences." class="nav-item"><a class="nav-link" href="liste_personnel.php">Personnel</a></li>
            <li data-intro="📄 Cette fenêtre génère un fichier PDF pour le pointage journalier." class="nav-item"><a class="nav-link" href="pointage_personnel.php">Pointage</a></li>
            <li data-intro="📋 Accédez aux demandes de report des tâches ici." class="nav-item"><a class="nav-link" href="demandes_report.php">Report</a></li>
            <li data-intro="💰 Visualisez, imprimez, et gérez les demandes d'avance sur salaire ici." class="nav-item"><a class="nav-link" href="liste_demande_avance.php">Avances</a></li>
            <li data-intro="🤝 Gérez les demandes de prêt, tout comme les avances sur salaire." class="nav-item"><a class="nav-link" href="liste_demande_pret.php">Prêt</a></li>
            <li data-intro="🛑 Gérez les demandes d'absence ici, tout comme les autres." class="nav-item"><a class="nav-link" href="liste_demande_absence.php">Absence</a></li>
            <li data-intro="🔒 Déconnectez-vous à tout moment en cliquant sur ce bouton." class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
        </ul>
        <div class="mode-toggle-container">
            <button data-intro="🌗 Choisissez entre le mode sombre et le mode normal avec ce bouton." id="modeToggle" class="btn btn-light" type="button" aria-label="Toggle mode">
                <i class="fas fa-adjust"></i>
                <span class="ml-2">Changer de mode</span>
            </button>
        </div>
    </div>
</nav>