document.addEventListener('DOMContentLoaded', function() {
    initializeSidebar();
    initializeSections();
    initializeCarousel();
    initializeChat();
    initializeCart();
});

// Gestion de la sidebar
function initializeSidebar() {
    const categories = document.querySelectorAll('.sidebar-category');
    if (!categories) return;
    
    categories.forEach(category => {
        category.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Supprimer la classe active de toutes les catégories
            categories.forEach(c => c.classList.remove('active'));
            
            // Ajouter la classe active à la catégorie cliquée
            category.classList.add('active');
            
            // Animer la transition
            category.classList.add('slide-enter');
            console.log('Animation de la catégorie:', category);
            setTimeout(() => category.classList.remove('slide-enter'), 600);  // Augmenter la durée si nécessaire
            
            // Charger les données de la catégorie
            const categoryId = category.dataset.categoryId;
            loadCategoryData(categoryId);
        });
    });
}

function loadCategoryData(categoryId) {
    fetch(`/api/categories/${categoryId}/prestations`)
        .then(response => response.json())
        .then(data => {
            updatePrestationDisplay(data);
        })
        .catch(error => console.error('Erreur lors du chargement des données:', error));
}

// Gestion des sections expandables
function initializeSections() {
    const sections = document.querySelectorAll('.section-header');
    if (!sections) return;
    
    sections.forEach(section => {
        section.addEventListener('click', () => {
            const content = section.nextElementSibling;
            const icon = section.querySelector('i');
            if (!content || !icon) return;
            
            content.classList.toggle('expanded');
            
            // Rotation de l'icône
            if (content.classList.contains('expanded')) {
                icon.classList.replace('fa-plus', 'fa-minus');
            } else {
                icon.classList.replace('fa-minus', 'fa-plus');
            }
        });
    });
}

// Gestion du carrousel d'images
function initializeCarousel() {
    const carousel = document.querySelector('.image-carousel');
    if (!carousel) return;

    const images = carousel.querySelectorAll('.carousel-image');
    const prevButton = carousel.querySelector('.carousel-button.prev');
    const nextButton = carousel.querySelector('.carousel-button.next');
    if (!images.length || !prevButton || !nextButton) return;

    let currentIndex = 0;
    let intervalId = null;

    function showImage(index) {
        images.forEach((image, i) => {
            image.classList.toggle('active', i === index);
        });
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    }

    // Démarrer le carrousel automatique
    function startCarousel() {
        intervalId = setInterval(nextImage, 5000);
    }

    // Arrêter le carrousel automatique
    function stopCarousel() {
        if (intervalId) {
            clearInterval(intervalId);
        }
    }

    // Event listeners
    prevButton.addEventListener('click', () => {
        stopCarousel();
        prevImage();
        startCarousel();
    });

    nextButton.addEventListener('click', () => {
        stopCarousel();
        nextImage();
        startCarousel();
    });

    carousel.addEventListener('mouseenter', stopCarousel);
    carousel.addEventListener('mouseleave', startCarousel);

    // Démarrer le carrousel
    startCarousel();
}

// Gestion du chat
function initializeChat() {
    const chatWidget = document.querySelector('.chat-widget');
    if (!chatWidget) return;

    const chatHeader = chatWidget.querySelector('.chat-header');
    const chatContent = chatWidget.querySelector('.chat-content');
    const messagesContainer = chatWidget.querySelector('.chat-messages');
    const messageInput = chatWidget.querySelector('.message-input');
    const sendButton = chatWidget.querySelector('.send-message');

    if (!chatHeader || !chatContent || !messagesContainer || !messageInput || !sendButton) return;

    // Toggle du chat
    chatHeader.addEventListener('click', () => {
        const isOpen = chatWidget.classList.contains('translate-y-0');
        chatWidget.classList.toggle('translate-y-0');
        chatWidget.classList.toggle('translate-y-[calc(100%-3rem)]');
        chatContent.classList.toggle('hidden');
        
        // Rotation de l'icône
        const icon = chatHeader.querySelector('i.fa-chevron-right');
        if (icon) {
            icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(90deg)';
        }
    });

    // Envoi de message
    function sendMessage() {
        const content = messageInput.value.trim();
        if (!content) return;

        addMessageToChat(content, false);

        fetch('/api/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ content }),
        })
        .then(response => response.json())
        .then(data => {
            setTimeout(() => {
                addMessageToChat("Message reçu. Notre équipe vous répondra bientôt.", true);
            }, 1000);
        })
        .catch(error => console.error('Erreur lors de l\'envoi du message:', error));

        messageInput.value = '';
    }

    function addMessageToChat(content, isAdmin) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', isAdmin ? 'admin' : 'user', 'fade-enter');
        messageElement.textContent = content;
        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
}

// Gestion du panier
function initializeCart() {
    const addToCartButton = document.querySelector('.add-to-cart');
    if (!addToCartButton) return;
    
    addToCartButton.addEventListener('click', () => {
        addToCartButton.classList.add('scale-95');
        setTimeout(() => addToCartButton.classList.remove('scale-95'), 100);
        
        const prestationId = addToCartButton.dataset.prestationId;
        addPrestationToCart(prestationId);
    });
}

function addPrestationToCart(prestationId) {
    fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ prestationId }),
    })
    .then(response => response.json())
    .then(data => {
        updateCartInterface(data);
    })
    .catch(error => console.error('Erreur lors de l\'ajout au panier:', error));
}

function updateCartInterface(cartData) {
    // Mise à jour de l'interface du panier (à adapter selon vos besoins)
}
// Gestion de la sidebar
function initializeSidebar() {
    const categories = document.querySelectorAll('.sidebar-category');
    if (!categories) return;
    
    categories.forEach(category => {
        category.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Supprimer la classe active de toutes les catégories
            categories.forEach(c => c.classList.remove('active', 'scale-up', 'fade-in'));
            
            // Ajouter la classe active à la catégorie cliquée avec animation
            category.classList.add('active', 'scale-up');
            category.classList.add('fade-in');
            
            // Charger les données de la catégorie
            const categoryId = category.dataset.categoryId;
            loadCategoryData(categoryId);
        });
    });
}

// Gestion du carrousel d'images
function initializeCarousel() {
    const carousel = document.querySelector('.image-carousel');
    if (!carousel) return;

    const images = carousel.querySelectorAll('.carousel-image');
    const prevButton = carousel.querySelector('.carousel-button.prev');
    const nextButton = carousel.querySelector('.carousel-button.next');
    if (!images.length || !prevButton || !nextButton) return;

    let currentIndex = 0;
    let intervalId = null;

    function showImage(index) {
        images.forEach((image, i) => {
            if (i === index) {
                image.classList.add('fade-in');
                image.classList.remove('fade-out');
            } else {
                image.classList.add('fade-out');
                image.classList.remove('fade-in');
            }
        });
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    }

    // Démarrer le carrousel automatique
    function startCarousel() {
        intervalId = setInterval(nextImage, 5000);
    }

    // Arrêter le carrousel automatique
    function stopCarousel() {
        if (intervalId) {
            clearInterval(intervalId);
        }
    }

    // Event listeners
    prevButton.addEventListener('click', () => {
        stopCarousel();
        prevImage();
        startCarousel();
    });

    nextButton.addEventListener('click', () => {
        stopCarousel();
        nextImage();
        startCarousel();
    });

    carousel.addEventListener('mouseenter', stopCarousel);
    carousel.addEventListener('mouseleave', startCarousel);

    // Démarrer le carrousel
    startCarousel();
}

// Gestion du chat
function initializeChat() {
    const chatWidget = document.querySelector('.chat-widget');
    if (!chatWidget) return;

    const chatHeader = chatWidget.querySelector('.chat-header');
    const chatContent = chatWidget.querySelector('.chat-content');
    const messagesContainer = chatWidget.querySelector('.chat-messages');
    const messageInput = chatWidget.querySelector('.message-input');
    const sendButton = chatWidget.querySelector('.send-message');

    if (!chatHeader || !chatContent || !messagesContainer || !messageInput || !sendButton) return;

    // Toggle du chat avec animation de glissement
    chatHeader.addEventListener('click', () => {
        const isOpen = chatWidget.classList.contains('translate-y-0');
        chatWidget.classList.toggle('translate-y-0');
        chatWidget.classList.toggle('translate-y-[calc(100%-3rem)]');
        chatContent.classList.toggle('hidden');
        
        // Animation de rotation de l'icône
        const icon = chatHeader.querySelector('i.fa-chevron-right');
        if (icon) {
            icon.style.transition = 'transform 0.3s ease';
            icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(90deg)';
        }
    });
}
// Gestion du panier
function initializeCart() {
    const addToCartButton = document.querySelector('.add-to-cart');
    if (!addToCartButton) return;
    
    addToCartButton.addEventListener('click', () => {
        // Animation d'agrandissement rapide du bouton
        addToCartButton.classList.add('scale-95');
        setTimeout(() => addToCartButton.classList.remove('scale-95'), 100);
        
        const prestationId = addToCartButton.dataset.prestationId;
        addPrestationToCart(prestationId);
    });
}
