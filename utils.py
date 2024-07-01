import datetime
from datetime import date
import os
import random
import pygame
from gtts import gTTS
import time
# Initialiser Pygame et le module mixer
pygame.init()


def lecture_bonne(objet, information):
    pygame.mixer.init()
    # Providing the text
    reponse_options = ["Selon Mon derniers rapports, ", "D'après les analyses effectuées, ",
                       "Je suis heureux de vous informer que ", "Mes études ont révélé que ",
                       "J'ai le réel le plaisir de vous annoncer que ",
                       "Mes dernières données indiquent que ", "Mes recherches ont montré que ",
                       "Mes informations récentes confirment que ",
                       "Mon rapports récents démontrent que ", "j'ai évalué et j'ai conclu que ",
                       "Mes résultats ont mis en évidence que ", "Mes derniers suivis ont prouvé que "]
    reponse_choisie = random.choice(reponse_options)

    input_text = reponse_choisie + objet + information

    # Setting the language
    language = "fr"
    # Passing to gtts engine
    voice = gTTS(text=input_text, lang=language, slow=False)

    # Creating and saving the audio file
    audio_file = "demo.mp3"
    voice.save(audio_file)

    # jouer de la musique
    pygame.mixer.music.load(audio_file)
    pygame.mixer.music.play()

    # Attendre la fin de la lecture audio
    while pygame.mixer.music.get_busy():
        continue
    pygame.mixer.music.stop()
    pygame.mixer.quit()  # Fermeture du module pygame.mixer
    # Supprimer le fichier audio après utilisation
    os.remove(audio_file)

# Appeler la fonction avec des exemples d'arguments
def lecture_mauvaise(objet, information):
    pygame.mixer.init()
    reponse_options = ["Désolé, ", "Malheureusement, ", "Je regrette, ", "Apparemment, ", "À ce qu'il semble, ",
                       "Il semblerait que ", "D'après ce que je sais, ", "Selon mes informations, ",
                       "Il se pourrait que ", "À mon avis, ", "Comme vous pouvez le constater, ", "J'ai bien peur que "]
    reponse_choisie = random.choice(reponse_options)

    input_text = reponse_choisie + objet + information

    # Setting the language
    language = "fr"
    # Passing to gtts engine
    voice = gTTS(text=input_text, lang=language, slow=False)

    # Creating and saving the audio file
    audio_file = "demo.mp3"
    voice.save(audio_file)

    # jouer de la musique
    pygame.mixer.music.load(audio_file)
    pygame.mixer.music.play()
    while pygame.mixer.music.get_busy():
        continue
    pygame.mixer.music.stop()
    pygame.mixer.quit()  # Fermeture du module pygame.mixer
    os.remove(audio_file)
def lire_rapport():
    pygame.mixer.init()
    # Providing the text file
    f = open("rapport.txt", "r", encoding="utf-8")
    # reading the file
    input_text = f.read().replace("\n", " ")

    # Setting the language
    language = "fr"

    # Passing to gtts engine
    voice = gTTS(text=input_text, lang=language, slow=False)

    # Creating and saving the audio file
    audio_file = "rapport.mp3"
    voice.save(audio_file)

    # jouer de la musique
    pygame.mixer.music.load(audio_file)
    pygame.mixer.music.play()
    while pygame.mixer.music.get_busy():
        continue
    pygame.mixer.music.stop()
    pygame.mixer.quit()  # Fermeture du module pygame.mixer
    os.remove(audio_file)
def ecrire_dans_rapport(date, objet, information):
    """
    Écrit les informations dans le fichier 'rapport.txt'.

    Args:
    date (str): La date au format 'AAAA-MM-JJ'.
    objet (str): L'objet.
    information (str): Les informations à écrire.
    """
    # Formatage de la date
    date_obj = datetime.datetime.strptime(date, "%Y-%m-%d")
    date_formatee = date_obj.strftime("%d/%m/%Y")

    # Écriture dans le fichier rapport.txt avec l'encodage UTF-8
    with open("rapport.txt", "a", encoding="utf-8") as fichier:
        fichier.write(f"Date : {date_formatee}\n")
        fichier.write(f"Date : {heure}\n")
        fichier.write(f"Objet : {objet}\n")
        fichier.write(f"Informations : {information}\n")
        fichier.write("---\n")

    print("Les informations ont été enregistrées dans le fichier rapport.txt.")