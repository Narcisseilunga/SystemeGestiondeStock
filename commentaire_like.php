<script>
   // Partie déjà existante pour aimer un produit
document.querySelectorAll('.like-button').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const productName = this.getAttribute('data-product-name');
        const email = this.getAttribute('data-email');
        if (email) {
            fetch('jaime_et_commentaire.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email, action: 'j\'aime', productName: productName })
            }).then(response => {
                if (response.ok) {
                    location.reload(); // Rechargez la page pour mettre à jour le nombre de "J'aime"
                }
            });
        }
    });
});

// Commenter un produit
document.querySelectorAll('.comment-button').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const productName = this.getAttribute('data-product-name');
        const email = this.getAttribute('data-email');
        const comment = prompt("Entrez votre commentaire:");
        if (email && comment) {
            fetch('jaime_et_commentaire.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email, action: 'commenter', comment: comment, productName: productName })
            }).then(response => {
                if (response.ok) {
                    location.reload(); // Rechargez la page pour mettre à jour les commentaires
                }
            });
        }
    });
});

// Aimer un commentaire
document.querySelectorAll('.like-comment-button').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const commentId = this.getAttribute('data-comment-id');
        const email = this.getAttribute('data-email');
        if (email) {
            fetch('jaime_et_commentaire.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email, action: 'j\'aime', commentId: commentId })
            }).then(response => {
                if (response.ok) {
                    location.reload(); // Rechargez la page pour mettre à jour le nombre de "J'aime" sur le commentaire
                }
            });
        }
    });
});

// Commenter un commentaire
document.querySelectorAll('.reply-button').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const commentId = this.getAttribute('data-comment-id');
        const email = this.getAttribute('data-email');
        const replyComment = prompt("Entrez votre réponse:");
        if (email && replyComment) {
            fetch('jaime_et_commentaire.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email, action: 'commenter', comment: replyComment, commentId: commentId })
            }).then(response => {
                if (response.ok) {
                    location.reload(); // Rechargez la page pour mettre à jour les réponses
                }
            });
        }
    });
});
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-comments');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien
                const commentsContainer = this.closest('.post-footer').nextElementSibling; // Sélectionner le conteneur des commentaires

                // Basculez l'affichage des commentaires
                if (commentsContainer.style.display === 'none') {
                    commentsContainer.style.display = 'block'; // Affiche les commentaires
                    this.textContent = 'Masquer les commentaires'; // Change le texte du bouton
                } else {
                    commentsContainer.style.display = 'none'; // Masque les commentaires
                    this.textContent = 'Afficher les commentaires'; // Change le texte du bouton
                }
            });
        });
    });
</script>