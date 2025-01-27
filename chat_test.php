<?php 
session_start();
$nb_index = isset($_GET['nb_index']) ? intval($_GET['nb_index']) : 0;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #messages { border: 1px solid #ccc; height: 300px; overflow-y: scroll; }
        #message { width: 80%; }
    </style>
</head>
<body>
    <?php foreach ($_SESSION['panier_produit'] as $article) { ?>
    <h2>Discussion pour le produit : <?php echo htmlspecialchars($article['nom']); ?></h2>
    <form id="product-form">
        <label for="price">Prix:</label>
        <input type="number" id="price" value="<?php echo $article['prix']; ?>" required />
        
        <label for="quantity">Quantité:</label>
        <input type="number" id="quantity" value="<?php echo $article['quantite']; ?>" required />
        
        <button type="submit">Envoyer</button>
    </form>
    <?php }?>
    <div>
        <select id="recipient">
            <!-- Les utilisateurs seront chargés ici -->
        </select>
    </div>
    <div id="messages"></div>
    <input type="text" id="username" value="<?php echo $_SESSION['nom_utilisateur']; ?>" disabled />
    <input type="text" id="message" placeholder="Entrez votre message..." autocomplete="on" />
    <button id="send">Envoyer</button>
    <script>
        const messagesDiv = document.getElementById('messages');
        const messageInput = document.getElementById('message');
        const sendButton = document.getElementById('send');
        const usernameInput = document.getElementById('username');
        const recipientSelect = document.getElementById('recipient');
        const socket = new WebSocket('ws://localhost:8080');

        // Charger les utilisateurs
        fetch('get_users.php')
            .then(response => response.json())
            .then(users => {
                users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.prenom} ${user.nom}`;
                    recipientSelect.appendChild(option);
                });
            });

        // Fonction pour charger les messages pour un destinataire spécifique
        function loadMessages() {
            const recipientId = recipientSelect.value;
            fetch(`get_messages.php?recipient_id=${recipientId}`)
                .then(response => response.json())
                .then(messages => {
                    messagesDiv.innerHTML = '';
                    messages.forEach(messageData => {
                        const message = document.createElement('div');
                        message.textContent = `${messageData.name}: ${messageData.obj} (à ${messageData.temp})`;
                        messagesDiv.appendChild(message);
                    });
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                })
                .catch(error => console.error('Erreur lors de la récupération des messages:', error));
        }

        loadMessages();
        setInterval(loadMessages, 1000);

        socket.onmessage = function(event) {
            const messageData = JSON.parse(event.data);
            const recipientId = recipientSelect.value;

            if (messageData.recipient_id === recipientId) {
                const message = document.createElement('div');
                message.textContent = `${messageData.username}: ${messageData.message}`;
                messagesDiv.appendChild(message);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }
        };

        sendButton.onclick = function() {
            const message = messageInput.value;
            const username = usernameInput.value;
            const recipientId = recipientSelect.value;
            if (message && username) {
                const messageData = { sender_id: username, recipient_id: recipientId, message: message };
                socket.send(JSON.stringify(messageData));
                messageInput.value = '';
            }
        };

        recipientSelect.onchange = loadMessages;

        // Gérer l'envoi du formulaire
        document.getElementById('product-form').onsubmit = function(event) {
            event.preventDefault();
            const price = document.getElementById('price').value;
            const quantity = document.getElementById('quantity').value;
            const recipientId = recipientSelect.value;

            // Envoyer les données du produit au vendeur
            const formData = { recipient_id: recipientId, price: price, quantity: quantity };
            socket.send(JSON.stringify({ type: 'product_update', data: formData }));
            alert('Détails du produit envoyés au vendeur!');
        };
    </script>
</body>
</html>