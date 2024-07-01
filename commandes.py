from PyQt5.QtWidgets import QApplication, QWidget, QVBoxLayout, QListWidget, QListWidgetItem

# Simuler des données de commande
commandes = [
    {"id": 1, "client": "Client A", "article": "Article 1", "quantité": 2, "statut": "Livré"},
    {"id": 2, "client": "Client B", "article": "Article 2", "quantité": 1, "statut": "En cours"},
    # Ajoutez d'autres commandes ici
]

# Créer l'application et la fenêtre principale
app = QApplication([])
fenetre = QWidget()
fenetre.setWindowTitle('Liste des Commandes')

# Créer le QVBoxLayout qui contiendra le QListWidget
layout = QVBoxLayout()

# Créer le QListWidget
list_widget = QListWidget()

# Ajouter les données dans le QListWidget
for commande in commandes:
    item = QListWidgetItem(f"ID: {commande['id']} - Client: {commande['client']} - Article: {commande['article']} - Quantité: {commande['quantité']} - Statut: {commande['statut']}")
    list_widget.addItem(item)

# Ajouter le QListWidget au layout
layout.addWidget(list_widget)

# Configurer la fenêtre pour utiliser le layout
fenetre.setLayout(layout)

# Afficher la fenêtre
fenetre.show()

# Exécuter l'application
app.exec_()
