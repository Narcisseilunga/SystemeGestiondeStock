from PyQt5.QtGui import QPixmap, QPalette, QBrush, QTextDocument
from PyQt5.QtPrintSupport import QPrinter, QPrintDialog
from PyQt5.QtWidgets import QApplication, QMainWindow, QTextEdit, QPushButton, QVBoxLayout, QWidget, QFileDialog
from utils import lire_rapport

class FenetrePrincipale(QMainWindow):
    def __init__(self):
        super().__init__()
        self.initialiserUI()

    def initialiserUI(self):
        self.setWindowTitle("Affichage du contenu d'un fichier texte")
        self.setGeometry(100, 100, 480, 320)

        # Charger l'image et l'ajuster à la taille de la fenêtre
        pixmap = QPixmap('rapport.jpg')
        palette = QPalette()
        palette.setBrush(QPalette.Window, QBrush(pixmap))
        self.setPalette(palette)
        # Créer un QTextEdit pour afficher le contenu du fichier
        self.zone_texte = QTextEdit(self)

        # Créer un QPushButton pour déclencher la lecture du fichier
        self.bouton_lire = QPushButton("Lire le fichier", self)
        self.bouton_lire.setStyleSheet('QPushButton {background-color: #FF5733; color: white;}')
        self.bouton_imprimer = QPushButton("imprimer le rapport",self)
        self.bouton_imprimer.setStyleSheet('QPushButton {background-color: #FF5733; color: white;}')

        self.bouton_ecoute = QPushButton("Ecouter le rapport", self)
        self.bouton_ecoute.setStyleSheet('QPushButton {background-color: #FF5733; color: white;}')

        self.bouton_lire.clicked.connect(self.afficher_contenu)
        self.bouton_ecoute.clicked.connect(lire_rapport)
        self.bouton_imprimer.clicked.connect(self.imprimer_document_texte)
        # Créer un QVBoxLayout et ajouter les widgets
        layout = QVBoxLayout()
        layout.addWidget(self.zone_texte)
        layout.addWidget(self.bouton_lire)
        layout.addWidget(self.bouton_imprimer)
        layout.addWidget(self.bouton_ecoute)
        # Créer un QWidget pour contenir le layout
        container = QWidget()
        container.setLayout(layout)

        # Définir le QWidget comme le widget central de QMainWindow
        self.setCentralWidget(container)

    def afficher_contenu(self):
        try:
            with open('rapport.txt', 'r', encoding='utf-8') as fichier:
                contenu = fichier.read()
                self.zone_texte.setText(contenu)
        except Exception as e:
            self.zone_texte.setText(f"Erreur lors de la lecture du fichier : {e}")
    def imprimer_document_texte(self):
        # Spécifier le chemin du fichier texte ici
        chemin_fichier = 'rapport.txt'
        # Créer un objet QTextDocument
        document = QTextDocument()
        with open(chemin_fichier, 'r', encoding='utf-8') as fichier:
            document.setPlainText(fichier.read())

        # Créer un objet QPrinter
        imprimante = QPrinter(QPrinter.HighResolution)

        # Créer un objet QPrintDialog
        dialogue_impression = QPrintDialog(imprimante, self)
        if dialogue_impression.exec_() == QPrintDialog.Accepted:
            document.print_(imprimante)
# Créer l'application et la fenêtre principale
app = QApplication([])
fenetre = FenetrePrincipale()
fenetre.show()

# Exécuter l'application
app.exec_()
