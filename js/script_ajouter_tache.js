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

});