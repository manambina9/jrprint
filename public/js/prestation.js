document.addEventListener('DOMContentLoaded', function() {
    const filters = document.querySelectorAll('.portfolio-flters li');
    const items = document.querySelectorAll('.portfolio-item');

    filters.forEach(filter => {
      filter.addEventListener('click', function() {
        // Retirer la classe 'filter-active' des autres filtres
        filters.forEach(f => f.classList.remove('filter-active'));
        // Ajouter la classe 'filter-active' au filtre cliqué
        this.classList.add('filter-active');
        
        const filterValue = this.getAttribute('data-filter');

        items.forEach(item => {
          if (filterValue === '*' || item.classList.contains(filterValue.replace('.', ''))) {
            item.style.display = 'block';  // Afficher l'élément correspondant
          } else {
            item.style.display = 'none';  // Masquer les autres
          }
        });
      });
    });
  });

  
