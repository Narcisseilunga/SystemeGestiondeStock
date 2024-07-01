import time
import random
import os

class Ferme:
    def __init__(self):
        # Simuler une base de données des animaux avec RFID
        self.animaux_avec_rfid = {1: 'Vache', 2: 'Mouton', 3: 'Chèvre', 4: 'Poulet'}
        # Liste des animaux attendus dans la ferme
        self.animaux_attendus = set(self.animaux_avec_rfid.keys())
        # Emplacements des lecteurs RFID
        self.lecteurs_rfid = {'Nord': (0, 10), 'Sud': (0, -10), 'Est': (10, 0), 'Ouest': (-10, 0)}

    def lire_rfid(self):
        # Simuler la lecture d'un tag RFID
        return random.choice(list(self.animaux_avec_rfid.keys()))

    def localiser_animal(self, id_animal):
        # Simuler la triangulation pour localiser un animal
        lecteurs = random.sample(list(self.lecteurs_rfid.keys()), 3)
        print(f"L'animal numéro {id_animal} a été détecté par les lecteurs {lecteurs}.")
        # Retourner une position simulée
        return (random.uniform(-10, 10), random.uniform(-10, 10))

    def verifier_animaux_manquants(self, comptage_animaux):
        # Vérifier si des animaux sont manquants
        animaux_detectes = set(comptage_animaux.keys())
        return self.animaux_attendus - animaux_detectes

    def jouer_alarme(self):
        # Jouer un son d'alarme
        print("ALERT! Un animal est manquant!")
        # Implémenter la fonctionnalité pour jouer un son d'alarme selon le système

    def suivre_animaux(self):
        comptage_animaux = {}
        localisations_animaux = {}
        for _ in range(100):  # Simuler 100 lectures de RFID
            id_animal = self.lire_rfid()
            comptage_animaux[id_animal] = comptage_animaux.get(id_animal, 0) + 1
            localisations_animaux[id_animal] = self.localiser_animal(id_animal)
            time.sleep(1)  # Attendre 1 seconde entre chaque lecture

        # Vérifier les animaux manquants et jouer l'alarme si nécessaire
        animaux_manquants = self.verifier_animaux_manquants(comptage_animaux)
        if animaux_manquants:
            self.jouer_alarme()
            for id_animal in animaux_manquants:
                print(f"Animal manquant: {self.animaux_avec_rfid[id_animal]} numéro {id_animal}")

        # Afficher les localisations
        for id_animal, position in localisations_animaux.items():
            print(f"Animal numéro {id_animal} localisé à la position {position}.")

# Créer une instance de la ferme et commencer le suivi
ma_ferme = Ferme()
ma_ferme.suivre_animaux()
