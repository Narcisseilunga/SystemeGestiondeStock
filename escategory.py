import datetime

import customtkinter
from tkinter import messagebox
import sqlite3
import tkinter as tk
from PIL import Image, ImageTk
import os
from utils import ecrire_dans_rapport
import  subprocess
class LoginManager():
    def __init__(self, root_win):
        self.root = root_win
        self.root.geometry("400x550")
        self.root.title("Système de Gestion de Stock")
        self.root.config(bg="#2EB086")
        self.root.iconbitmap(chemin_icone)

        # variables
        self.user_id_var = customtkinter.StringVar()
        self.password_var = customtkinter.StringVar()

        # login frame
        self.login_frame = customtkinter.CTkFrame(self.root, fg_color="#FFFFFF", corner_radius=30)
        self.login_frame.place(relx=0.5, rely=0.5, anchor="center")

        # screen Title
        self.title = customtkinter.CTkLabel(self.login_frame, text="Se Connecter", font=("Montserrat", 22, "bold"), anchor="center")
        self.title.grid(row=0, column=0, columnspan=2, padx=50, pady=40)
        self.title_2 = customtkinter.CTkLabel(self.login_frame, text="Bienvenue au système de gestion de stock", font=("Montserrat", 14, "normal"), anchor="center")
        self.title_2.grid(row=1, column=0, columnspan=2, padx=50, pady=20)

        # username
        username_label = customtkinter.CTkLabel(self.login_frame, text="ID de l'utilisateur", font=("Montserrat", 14, "normal"), anchor="center")
        username_label.grid(row=3, column=0, padx=50, pady=(40, 10))
        username_text = customtkinter.CTkEntry(self.login_frame, textvariable=self.user_id_var, font=("Montserrat", 16, "normal"), corner_radius=8, width=200)
        username_text.grid(row=4, column=0, padx=50, pady=(10, 20))

        # password
        password_label = customtkinter.CTkLabel(self.login_frame, text="Mot de passe", font=("Montserrat", 14, "normal"), anchor="center")
        password_label.grid(row=5, column=0, padx=50, pady=(20, 10))
        password_text = customtkinter.CTkEntry(self.login_frame, textvariable=self.password_var, font=("Montserrat", 16, "normal"), corner_radius=8, show="*", width=200)
        password_text.grid(row=6, column=0, padx=50, pady=(10, 40))

        # button
        login_button = customtkinter.CTkButton(self.login_frame, text="Se connecter", command=self.login, font=("Montserrat", 16, "normal"), fg_color="#2EB086", hover_color="#FF00FF", width=150)
        login_button.grid(row=7, column=0, padx=50, pady=20)    # methods
    def login(self):
        con = sqlite3.connect(r'system.db')
        cur = con.cursor()
        autorisation = True

        if self.user_id_var.get() == "" or self.password_var.get() == "":
            messagebox.showerror("Erreur", "Veuillez saisir le nom d'utilisateur et le mot de passe.", parent=self.root)
        else:
            cur.execute("SELECT Name, type FROM employee WHERE id=? AND password=?",
                    (self.user_id_var.get(), self.password_var.get()))
            result = cur.fetchone()
            if result is None:
                messagebox.showerror("Erreur", "ID Utilisateur ou mot de passe invalide", parent=self.root)
            else:
                self.user_type = result[1]
                self.nom_type = result[0]
                user_types = self.user_type
                print(self.user_type)
                if self.user_type == "Admin" and autorisation == True:
                    self.root.destroy()
                    ecrire_dans_rapport(date_str, f"Connexion Etablie Par L'{self.user_type}",
                                        "|Nom : " + self.nom_type + "|")
                    subprocess.run(['python', 'initialisation.py', str(autorisation)])
                elif self.user_type == "Employé" and autorisation == True:
                    self.root.destroy()
                    ecrire_dans_rapport(date_str, f"Connexion Etablie Par L'{self.user_type}",
                                        "|Nom : " + self.nom_type + "|")
                    subprocess.run(['python', 'pos.py', str(autorisation)])
                elif self.user_type == "surveillant":
                    self.root.destroy()
                    ecrire_dans_rapport(date_str, f"Connexion Etablie Par Le {self.user_type}",
                                        "|Nom : " + self.nom_type + "|")
                    subprocess.run(['python', 'projetferme/surveillance.py', str(autorisation)])
                    subprocess.run(['python', 'projetferme/surveillance2.py', str(autorisation)])
                elif self.user_type == "autre":
                    self.root.destroy()
        """except Exception as ex:
            self.messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)"""

def show_app():
    root = customtkinter.CTk()
    system = LoginManager(root)
    root.mainloop()
    # Ajoutez ici le code de votre application
    root2.destroy()
# Fonction pour afficher le logo
def show_logo():
    logo_window = tk.Toplevel(root2)
    logo_window.title("Logo")

    # Personnaliser la fenêtre du logo
    logo_window.overrideredirect(True)  # Supprimer la barre de titre
    logo_window.resizable(False, False) # Désactiver le redimensionnement

    # Centrer la fenêtre du logo
    screen_width = root2.winfo_screenwidth()
    screen_height = root2.winfo_screenheight()
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

    # Après 2 secondes, fermer la fenêtre du logo et afficher l'application
    logo_window.after(5000, lambda: [logo_window.destroy(), show_app()])
    logo_window.mainloop()

# Créer la fenêtre principale

chemin_icone = "stock.ico"
date_aujourdhui = datetime.date.today()
# Convertir la date en chaîne de caractères
date_str = date_aujourdhui.strftime("%Y-%m-%d")
if __name__ == "__main__":
    root2 = tk.Tk()
    root2.withdraw()

    # Afficher le logo
    show_logo()