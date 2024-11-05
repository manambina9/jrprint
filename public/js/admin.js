document.addEventListener('DOMContentLoaded', function () {
    const categoryField = document.querySelector('select[name="Prestation[category]"]');
    const titleField = document.querySelector('select[name="Prestation[title]"]');

    const categories = {
        'Panneau': [
            'Panneau sucette – Format 2*1',
            'Panneau – Format 4x3',
            'Panneau – Format 8x3',
            'Panneau – Format 12x3',
            'Panneau – Format 9x3',
            'Panneau – Format 6x3',
        ],
        'Autres Services': [
            'Habillages cuves & Transtack',
            'Décorations évènementielles',
            'Silhouettes',
            'Habillage véhicules',
            'Décorations Plaque sur PVC',
            'Photobooth',
            'Habillages vitrines / Vitrophanie',
            'Branding 3',
            'Photocall',
            'Bâche tendue',
            'Stop trottoir',
            'Habillages comptoirs',
            'Totem',
            'Habillages boutiques',
        ],
    };

    categoryField.addEventListener('change', function () {
        console.log('Catégorie sélectionnée:', this.value); // Debugging line
        const selectedCategory = this.value;
        const titles = categories[selectedCategory] || [];

        titleField.innerHTML = ''; // Reset titles
        titles.forEach(function (title) {
            const option = document.createElement('option');
            option.value = title; // Value to use when submitting
            option.textContent = title; // What is displayed to the user
            titleField.appendChild(option);
        });

        // Add a default option if no titles are available
        if (titles.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Aucun titre disponible';
            titleField.appendChild(option);
        }
    });

    // Trigger the event on load if a category is already selected
    categoryField.dispatchEvent(new Event('change'));
});
