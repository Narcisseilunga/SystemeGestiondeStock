import tkinter as tk
import pygame

# Initialiser Pygame pour la lecture audio
pygame.init()

# Charger le fichier audio
music_file = "demo.mp3"
pygame.mixer.music.load(music_file)

# Fonction pour contrôler la lecture de la musique
def toggle_music():
    if pygame.mixer.music.get_busy():
        pygame.mixer.music.pause()
        music_button.config(text="▶ Jouer")
    else:
        pygame.mixer.music.unpause()
        music_button.config(text="⏸ Arrêter")

# Créer la fenêtre Tkinter
window = tk.Tk()
window.title("Lecteur de musique")

# Créer le bouton pour contrôler la lecture de la musique
music_button = tk.Button(window, text="▶ Jouer", command=toggle_music)
music_button.pack(pady=20)

# Démarrer la boucle principale de Tkinter
window.mainloop()