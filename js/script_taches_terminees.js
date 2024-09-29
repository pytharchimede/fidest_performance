$(document).ready(function() {


    $('#filter-form').on('submit', function(e) {
        e.preventDefault(); // Empêcher le rechargement de la page

        const date_debut = $('#date_debut').val();
        const date_fin = $('#date_fin').val();

        $.ajax({
            url: 'ajax/get_tasks_terminees.php',
            type: 'GET',
            data: {
                date_debut: date_debut,
                date_fin: date_fin
            },
            beforeSend: function() {
                $('#loading').show(); // Affiche l'indicateur de chargement
            },
            success: function(response) {
                $('#results-container').html(response);
            },
            complete: function() {
                $('#loading').hide(); // Cache l'indicateur de chargement après la requête
            },
            error: function() {
                $('#results-container').html('<p>Une erreur est survenue.</p>');
            }
        });
    });


    const date_debut = $('#date_debut').val();
    const date_fin = $('#date_fin').val();

    $.ajax({
        url: 'ajax/get_tasks_terminees.php',
        type: 'GET',
        data: {
            date_debut: date_debut,
            date_fin: date_fin
        },
        beforeSend: function() {
            $('#loading').show(); // Affiche l'indicateur de chargement
        },
        success: function(response) {
            $('#results-container').html(response);
        },
        complete: function() {
            $('#loading').hide(); // Cache l'indicateur de chargement après la requête
        },
        error: function() {
            $('#results-container').html('<p>Une erreur est survenue.</p>');
        }
    });


    // Fonction pour mettre à jour le lien d'exportation
    function updateExportLink(date_debut, date_fin) {
        let url = 'request/export_tasks_terminees_list.php';

        // Ajouter les paramètres de date à l'URL
        if (date_debut) {
            url += `?date_debut=${encodeURIComponent(date_debut)}`;
        }
        if (date_fin) {
            url += `&date_fin=${encodeURIComponent(date_fin)}`;
        }

        // Mettre à jour l'attribut href du lien
        $('#exportBtn').attr('href', url);
    }

    // Initialiser l'exportation avec les dates existantes
    const initialDateDebut = $('#date_debut').val();
    const initialDateFin = $('#date_fin').val();
    updateExportLink(initialDateDebut, initialDateFin);


});