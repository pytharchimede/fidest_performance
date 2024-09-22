$(document).ready(function() {
    $('.select2').select2();

    // Drag and Drop
    const dropZone = $('#dropZone');
    const fileInput = $('#fileInput');
    const imagePreview = $('#imagePreview');

    dropZone.on('click', function() {
        fileInput.click();
    });

    dropZone.on('dragover', function(event) {
        event.preventDefault();
        dropZone.addClass('dragover');
    });

    dropZone.on('dragleave', function() {
        dropZone.removeClass('dragover');
    });

    dropZone.on('drop', function(event) {
        event.preventDefault();
        dropZone.removeClass('dragover');
        handleFiles(event.originalEvent.dataTransfer.files);
    });

    fileInput.on('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageItem = $('<div class="preview-item"><img src="' + e.target.result + '" alt="Image Preview"/><button class="remove-img">&times;</button></div>');
                imageItem.find('.remove-img').on('click', function() {
                    $(this).parent().remove();
                });
                imagePreview.append(imageItem);
            };
            reader.readAsDataURL(file);
        });
    }
});


$(document).ready(function() {
    $('.select2').select2();

    // Drag and Drop
    const dropZone = $('#dropZone');
    const fileInput = $('#fileInput');
    const imagePreview = $('#imagePreview');

    dropZone.on('click', function() {
        fileInput.click();
    });

    dropZone.on('dragover', function(event) {
        event.preventDefault();
        dropZone.addClass('dragover');
    });

    dropZone.on('dragleave', function() {
        dropZone.removeClass('dragover');
    });

    dropZone.on('drop', function(event) {
        event.preventDefault();
        dropZone.removeClass('dragover');
        handleFiles(event.originalEvent.dataTransfer.files);
    });

    fileInput.on('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageItem = $('<div class="preview-item"><img src="' + e.target.result + '" alt="Image Preview"/><button class="remove-img">&times;</button></div>');
                imageItem.find('.remove-img').on('click', function() {
                    $(this).parent().remove();
                });
                imagePreview.append(imageItem);
            };
            reader.readAsDataURL(file);
        });
    }

    // Form Submission
    $('form').on('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        console.log('FormData:', formData);

        $.ajax({
            url: 'request/insert_task.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                //alert('Tâche ajoutée avec succès !');
                // Réinitialisez le formulaire ou effectuez d'autres actions
                console.log('Réponse serveur:', response);
                $(location).attr('href', 'index.php');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Erreur lors de l\'insertion de la tâche :', textStatus, errorThrown);
            }
        });
    });
});