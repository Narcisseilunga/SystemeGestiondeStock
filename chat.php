<?php 
session_start();
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
        const recipientId = recipientSelect.value; // Obtenir l'ID du destinataire sélectionné
        fetch(`get_messages.php?recipient_id=${recipientId}`) // Passer l'ID dans la requête
            .then(response => response.json())
            .then(messages => {
                // Effacer les messages précédents
                messagesDiv.innerHTML = '';

                messages.forEach(messageData => {
                    const message = document.createElement('div');
                    message.textContent = `${messageData.name}: ${messageData.obj} (à ${messageData.temp})`;
                    messagesDiv.appendChild(message);
                });
                messagesDiv.scrollTop = messagesDiv.scrollHeight; // Faire défiler vers le bas
            })
            .catch(error => console.error('Erreur lors de la récupération des messages:', error));
    }

    // Charger les messages pour la première fois
    loadMessages();

    // Actualiser les messages toutes les 5 secondes
    setInterval(loadMessages, 1000);

    socket.onmessage = function(event) {
        const messageData = JSON.parse(event.data);
        const recipientId = recipientSelect.value; // ID du destinataire sélectionné

        // Afficher le message seulement si le destinataire correspond
        if (messageData.recipient_id === recipientId) {
            const message = document.createElement('div');
            message.textContent = `${messageData.username}: ${messageData.message}`;
            messagesDiv.appendChild(message);
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // Faire défiler vers le bas
        }
    };

    sendButton.onclick = function() {
        const message = messageInput.value;
        const username = usernameInput.value;
        const recipientId = recipientSelect.value;
        if (message && username) {
            const messageData = { sender_id: username, recipient_id: recipientId, message: message };
            socket.send(JSON.stringify(messageData));
            messageInput.value = ''; // Effacer l'input
        }
    };

    // Écouter le changement de sélection pour charger les messages du destinataire sélectionné
    recipientSelect.onchange = loadMessages; // Recharger les messages lorsque le destinataire change
</script>
</body>
</html>