import customtkinter as ctk
import subprocess
import sys
from tkinter import messagebox
import os

def execute_selected_script():
    autorisation= True
    selected_script = script_dropdown.get()
    if selected_script == "Tableau de bord":
        app.destroy()
        subprocess.run(["python", "Admin_dashboard.py", str(autorisation)])

    elif selected_script == "Facture":
        app.destroy()
        subprocess.run(["python", "sales.py", str(autorisation)])

    elif selected_script == "Gestion du Fournisseur":
        app.destroy()
        subprocess.run(["python", "supplier.py", str(autorisation)])

    elif selected_script == "Poste de vente":
        app.destroy()
        subprocess.run(["python", "pos.py", str(autorisation)])

    elif selected_script == "Gestion des employées":
        app.destroy()
        subprocess.run(["python", "employee.py", str(autorisation)])

    elif selected_script == "Categories des produits":
        app.destroy()
        subprocess.run(["python", "category.py", str(autorisation)])


app = ctk.CTk()
app.title("Choisir le script à éxécuter")
app.geometry("300x150")
chemin_icone = "stock.ico"
app.iconbitmap(chemin_icone)
frame = ctk.CTkFrame(app)
frame.pack(pady=20)

label = ctk.CTkLabel(frame, text="Sélectionnez un script à exécuter:")
label.grid(row=0, column=0, padx=10, pady=5)

scripts = [
           "Tableau de bord",
           "Facture",
           "Gestion du Fournisseur",
           "Poste de vente",
           "Gestion des employées",
           "Categories des produits"
           ]  # Ajoutez ici les noms de script disponibles

script_dropdown = ctk.CTkComboBox(frame, values=scripts, state="readonly")
script_dropdown.grid(row=1, column=0, padx=10, pady=5)

execute_button = ctk.CTkButton(app, text="Exécuter", command=execute_selected_script)
execute_button.pack(pady=10)



def fonction_destination(autorisation, *params):
    if autorisation == 'True':
        # L'utilisateur est déjà connecté
        print("Utilisateur déjà connecté.")
        if __name__ == "__main__":
            app.mainloop()

    else:
        # L'utilisateur doit se reconnecter
        print("Veuillez vous reconnecter.")


if len(sys.argv) >= 2:
    # Création de la fenêtre principale
    # root = Tk()

    # Appel de la méthode __init__() de la classe POS avec les arguments fournis
    fonction_destination(sys.argv[1], *sys.argv[:])

    # Exécuter la boucle principale de la fenêtre
    # root.mainloop()
else:
    fenetre = ctk.CTk()
    fenetre.geometry("500x120")
    chemin_icone = "stock.ico"
    fenetre.iconbitmap(chemin_icone)

    fenetre.title = "Fenetre de redirection"
    fenetre.config(bg="black")
    messagebox.showwarning(fenetre,"Accès impossible \n Veuillez vous connecter en tant qu'administrateur pour effectuer cette opération afin d'accéder à la gestion d'administrateur' ")
    fenetre.destroy()
    os.system("python login.py")

