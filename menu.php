<nav class="navbar navbar-expand-lg fixed-top">
    <a class="navbar-brand" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i> <!-- Icône hamburger -->
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
            <li class="nav-item"><a class="nav-link" href="liste_personnel.php">Personnel</a></li>
            <li class="nav-item"><a class="nav-link" href="pointage_personnel.php">Pointage</a></li>
            <li class="nav-item"><a class="nav-link" href="demandes_report.php">Report</a></li>
            <li class="nav-item"><a class="nav-link" href="liste_demande_avance.php">Avances</a></li>
            <li class="nav-item"><a class="nav-link" href="liste_demande_pret.php">Prêt</a></li>
            <li class="nav-item"><a class="nav-link" href="liste_demande_absence.php">Absence</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
        </ul>
        <div class="mode-toggle-container">
            <button id="modeToggle" class="btn btn-light" type="button" aria-label="Toggle mode">
                <i class="fas fa-adjust"></i>
                <span class="ml-2">Changer de mode</span>
            </button>
        </div>
    </div>
</nav>