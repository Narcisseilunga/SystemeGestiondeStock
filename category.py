from tkinter import *
from tkinter import ttk, messagebox
from utils import ecrire_dans_rapport
import customtkinter as ctk
import sqlite3
from PIL import Image, ImageTk
import sys
import os
import datetime

class Category:
    def __init__(self, root_win):
        self.root = root_win
        self.root.geometry("1100x600+220+100")
        self.root.iconbitmap(chemin_icone)
        self.root.title("Gestion des Catégories")
        self.root.config(bg="#ffffff")  # Couleur de fond blanche
        self.root.focus_force()

        # System Variables
        self.categ_id_var = StringVar()
        self.categ_name_var = StringVar()

        # Title
        title = Label(self.root, text="Gestion des Catégories", font=("Helvetica", 20, "bold"), bg="#4a90e2", fg="white", padx=20, pady=10)
        title.place(x=0, y=0, width=1100, height=50)

        # Content
        name_label = Label(self.root, text="Nom de Catégorie", font=("Helvetica", 14, "normal"), bg="#ffffff", fg="#333333")
        name_label.place(x=50, y=100)
        name_text = Entry(self.root, textvariable=self.categ_name_var, font=("Helvetica", 14, "normal"), bg="#f5f5f5", bd=1, relief=GROOVE)
        name_text.place(x=50, y=130, width=300, height=40)

        add_btn = Button(self.root, text="Ajouter", command=self.add_categ, font=("Helvetica", 12, "bold"), bg="#2ecc71", fg="white", bd=0, cursor="hand2", activebackground="#27ae60")
        add_btn.place(x=370, y=130, width=100, height=40)
        delete_btn = Button(self.root, text="Supprimer", command=self.delete_categ, font=("Helvetica", 12, "bold"), bg="#e74c3c", fg="white", bd=0, cursor="hand2", activebackground="#c0392b")
        delete_btn.place(x=480, y=130, width=100, height=40)

        # Category List
        categ_list_frame = Frame(self.root, bd=3, relief=RIDGE, bg="#ffffff")
        categ_list_frame.place(x=50, y=200, width=500, height=350)

        scroll_y = Scrollbar(categ_list_frame, orient=VERTICAL)
        scroll_x = Scrollbar(categ_list_frame, orient=HORIZONTAL)
        scroll_x.pack(side=BOTTOM, fill=X)
        scroll_y.pack(side=RIGHT, fill=Y)

        list_columns = ("id", "nom")
        self.categ_list_tabel = ttk.Treeview(categ_list_frame, columns=list_columns, yscrollcommand=scroll_y.set, xscrollcommand=scroll_x.set)
        self.categ_list_tabel.pack(fill=BOTH, expand=1)
        scroll_x.config(command=self.categ_list_tabel.xview)
        scroll_y.config(command=self.categ_list_tabel.yview)

        self.categ_list_tabel.heading("id", text="ID")
        self.categ_list_tabel.heading("nom", text="Nom")

        self.categ_list_tabel["show"] = "headings"

        self.categ_list_tabel.column("id", width=100)
        self.categ_list_tabel.column("nom", width=300)

        self.categ_list_tabel.bind("<ButtonRelease-1>", self.get_data)

        self.show_categ()

        # Image
        self.image = Image.open("images/stock_image.png")
        self.image = self.image.resize((500, 350))
        self.image = ImageTk.PhotoImage(self.image)
        self.image_label = Label(self.root, image=self.image, bg="#ffffff")
        self.image_label.place(x=600, y=200)
    def add_categ(self):
            con = sqlite3.connect("system.db")
            cur = con.cursor()
            try:
                if self.categ_name_var.get() == "":
                    messagebox.showerror("Erreur", "Nom de catégorie doit être saisie", parent=self.root)
                else:
                    cur.execute("SELECT * FROM category WHERE name=?", (self.categ_name_var.get(),))
                    row = cur.fetchone()
                    if row is not None:
                        messagebox.showerror("Erreur", "Catégorie deja existante, saisir un autre", parent=self.root)
                    else:
                        values_to_insert = (
                                            self.categ_name_var.get(),
                                            )
                        cur.execute("INSERT INTO category (name) VALUES (?)", values_to_insert)
                        con.commit()
                        messagebox.showinfo("Succès", "Catégorie est ajouté avec succès", parent=self.root)
                        ecrire_dans_rapport(date_str, "Ajout d'un nouveau catégorie de produit", self.categ_name_var.get())
                        self.show_categ()
            except Exception as ex:
                messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def show_categ(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT * FROM category")
            rows = cur.fetchall()
            self.categ_list_tabel.delete(*self.categ_list_tabel.get_children())
            for row in rows:
                self.categ_list_tabel.insert('', END, values=row)
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def get_data(self, ev):
        table_focus = self.categ_list_tabel.focus()
        table_content = (self.categ_list_tabel.item(table_focus))
        row = table_content["values"]
        print(row)

        self.categ_id_var.set(row[0])
        self.categ_name_var.set(row[1])

    def delete_categ(self):
        con = sqlite3.connect('system.db')
        cur = con.cursor()
        try:
            if self.categ_id_var.get() == "":
                messagebox.showerror("Erreur", "catégorie doit être selectionnée", parent=self.root)
            else:
                cur.execute("SELECT * FROM category WHERE id=?", (self.categ_id_var.get(),))
                row = cur.fetchone()
                self.category_name = row[0]
                if row is None:
                    messagebox.showerror("Erreur", "ID de Catégorie Invalid", parent=self.root)
                else:
                    user_confirm = messagebox.askyesno("Confirmation", "Confirmer la suppression?", parent=self.root)
                    if user_confirm:
                        cur.execute("DELETE FROM category WHERE id=?", (self.categ_id_var.get(),))
                        con.commit()
                        messagebox.showinfo("Succès", "Catégorie est supprimée avec succès", parent=self.root)
                        ecrire_dans_rapport(date_str, "Suppression d'une catégorie de produit",self.categ_name_var.get())

                        self.show_categ()
                        self.categ_id_var.set("")
                        self.categ_name_var.set("")

        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)



chemin_icone = "stock.ico"
date_aujourdhui = datetime.date.today()
# Convertir la date en chaîne de caractères
date_str = date_aujourdhui.strftime("%Y-%m-%d")
def fonction_destination(autorisation, *params):
        if autorisation == 'True':
            # L'utilisateur est déjà connecté
            print("Utilisateur déjà connecté.")
            if __name__ == "__main__":
                root = Tk()
                system = Category(root)
                root.mainloop()

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
    fenetre.iconbitmap(chemin_icone)
    fenetre.title = "Fenetre de redirection"
    fenetre.config(bg="black")
    messagebox.showwarning(fenetre, "Accès impossible \n Veuillez vous connecter en tant qu'administrateur pour effectuer cette opération pour accéder à la gestion des employées ")
    fenetre.destroy()
    os.system("python login.py")


