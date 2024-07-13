import tkinter as tk
from PIL import Image, ImageTk

# Fonction pour afficher le logo
def show_logo():
    logo_window = tk.Toplevel(root)
    logo_window.title("Logo")

    # Personnaliser la fenêtre du logo
    logo_window.overrideredirect(True)  # Supprimer la barre de titre
    logo_window.resizable(False, False) # Désactiver le redimensionnement

    # Centrer la fenêtre du logo
    screen_width = root.winfo_screenwidth()
    screen_height = root.winfo_screenheight()
    logo_window_width = 521  # Définir la largeur de la fenêtre
    logo_window_height = 521  # Définir la hauteur de la fenêtre
    logo_window_x = (screen_width - logo_window_width) // 2
    logo_window_y = (screen_height - logo_window_height) // 2
    logo_window.geometry(f"{logo_window_width}x{logo_window_height}+{logo_window_x}+{logo_window_y}")

    # Chargez et afficher l'image du logo
    logo = Image.open("stock.png")
    logo_photo = ImageTk.PhotoImage(logo)
    logo_label = tk.Label(logo_window, image=logo_photo)
    logo_label.pack()

    # Après 5 secondes, fermer la fenêtre du logo et quitter le programme
    logo_window.after(5000, lambda: [logo_window.destroy(), root.quit()])
    logo_window.mainloop()

# Créer la fenêtre principale
root = tk.Tk()
root.withdraw()

# Afficher le logo
show_logo()