from tkinter import *
from tkinter import ttk, messagebox,filedialog
import customtkinter as ctk
import sqlite3
import sys
import os
from utils import ecrire_dans_rapport
import datetime
import requests
import psycopg2
from psycopg2 import OperationalError
import shutil
class Product:
    def __init__(self, root_win):
        self.root = root_win
        self.root.geometry("1100x500+220+130")
        self.root.iconbitmap(chemin_icone)
        self.root.title("Gestion des Produits")
        self.root.config(bg="#f0f0f0")
        self.root.focus_force()

        # system variables
        self.searchOption_var = StringVar()
        self.searchText_var = StringVar()

        self.prod_id_var = StringVar()
        self.categ_var = StringVar()
        self.supp_var = StringVar()
        self.categ_list = []
        self.supp_list = []
        self.get_categ_supp()

        self.name_var = StringVar()
        self.price_var = StringVar()
        self.qty_var = StringVar()
        self.status_var = StringVar()
        self.image_name=""

        product_frame = Frame(self.root, bd=1, bg="white", relief=RIDGE)
        product_frame.grid(row=0, column=0, padx=10, pady=10, sticky="nsew")

        title = Label(product_frame, text="Information des Produits", font=("Arial", 18, "bold"), bg="#4CAF50", fg="white")
        title.grid(row=0, column=0, columnspan=2, sticky="ew")

        # labels
        categ_label = Label(product_frame, text="Catégorie", font=("Arial", 14), bg="white")
        categ_label.grid(row=1, column=0, padx=10, pady=10, sticky="w")
        supp_label = Label(product_frame, text="Fournisseur", font=("Arial", 14), bg="white")
        supp_label.grid(row=2, column=0, padx=10, pady=10, sticky="w")
        name_label = Label(product_frame, text="Nom", font=("Arial", 14), bg="white")
        name_label.grid(row=3, column=0, padx=10, pady=10, sticky="w")
        price_label = Label(product_frame, text="Prix", font=("Arial", 14), bg="white")
        price_label.grid(row=4, column=0, padx=10, pady=10, sticky="w")
        qty_label = Label(product_frame, text="Quantité", font=("Arial", 14), bg="white")
        qty_label.grid(row=5, column=0, padx=10, pady=10, sticky="w")
        status_label = Label(product_frame, text="Status", font=("Arial", 14), bg="white")
        status_label.grid(row=6, column=0, padx=10, pady=10, sticky="w")
        image_label = Label(product_frame, text="Image produit", font=("Arial", 14), bg="white")
        image_label.grid(row=7, column=0, padx=10, pady=10, sticky="w")

        # inputs
        categ_select = ttk.Combobox(product_frame, textvariable=self.categ_var, values=self.categ_list, state="readonly", justify=CENTER, font=("Arial", 14))
        categ_select.grid(row=1, column=1, padx=10, pady=10, sticky="ew")
        categ_select.current(0)
        supp_select = ttk.Combobox(product_frame, textvariable=self.supp_var, values=self.supp_list, state="readonly", justify=CENTER, font=("Arial", 14))
        supp_select.grid(row=2, column=1, padx=10, pady=10, sticky="ew")
        supp_select.current(0)

        name_txt = Entry(product_frame, textvariable=self.name_var, font=("Arial", 14), bg="#EEE6CE", bd=1)
        name_txt.grid(row=3, column=1, padx=10, pady=10, sticky="ew")
        price_txt = Entry(product_frame, textvariable=self.price_var, font=("Arial", 14), bg="#EEE6CE", bd=1)
        price_txt.grid(row=4, column=1, padx=10, pady=10, sticky="ew")
        qty_txt = Entry(product_frame, textvariable=self.qty_var, font=("Arial", 14), bg="#EEE6CE", bd=1)
        qty_txt.grid(row=5, column=1, padx=10, pady=10, sticky="ew")

        status_select = ttk.Combobox(product_frame, textvariable=self.status_var, values=("actif", "inactif"), state="readonly", justify=CENTER, font=("Arial", 14))
        status_select.grid(row=6, column=1, padx=10, pady=10, sticky="ew")
        status_select.current(0)

        image_select = Button(product_frame, text="Ajouter une image", command=self.select_image, font=("Arial", 12), bg="#FF5722", fg="white", bd=3, cursor="hand2")
        image_select.grid(row=7, column=1, padx=10, pady=10, sticky="ew")

        # buttons
        add_btn = Button(product_frame, text="Ajouter", command=self.add_product, font=("Arial", 12, "bold"), bg="#4CAF50", fg="white", bd=3, cursor="hand2")
        add_btn.grid(row=8, column=0, padx=10, pady=10, sticky="ew")
        update_btn = Button(product_frame, text="Modifier", command=self.update_product, font=("Arial", 12, "bold"), bg="#2196F3", fg="white", bd=3, cursor="hand2")
        update_btn.grid(row=8, column=1, padx=10, pady=10, sticky="ew")
        delete_btn = Button(product_frame, text="Supprimer", command=self.delete_product, font=("Arial", 12, "bold"), bg="#F44336", fg="white", bd=3, cursor="hand2")
        delete_btn.grid(row=9, column=0, padx=10, pady=10, sticky="ew")
        clear_btn = Button(product_frame, text="Effacer", command=self.clear, font=("Arial", 12, "bold"), bg="#9E9E9E", fg="white", bd=3, cursor="hand2")
        clear_btn.grid(row=9, column=1, padx=10, pady=10, sticky="ew")

        # search employee
        search_frame = LabelFrame(self.root, text="Chercher un Produit", font=("Arial", 12), bg="white", bd=2)
        search_frame.grid(row=0, column=1, padx=10, pady=10, sticky="nsew")

        # search options
        options_box = ttk.Combobox(search_frame, textvariable=self.searchOption_var, values=("Selectionner", "Catégorie", "Fournisseur", "Nom"), state="readonly", justify=CENTER, font=("Arial", 12))
        options_box.grid(row=0, column=0, padx=10, pady=10, sticky="ew")
        options_box.current(0)

        search_box = Entry(search_frame, textvariable=self.searchText_var, font=("Arial", 12), bg="#EEE6CE")
        search_box.grid(row=0, column=1, padx=10, pady=10, sticky="ew")
        search_btn = Button(search_frame, text="Chercher", command=self.search_product, font=("Arial", 12, "bold"), bg="#4CAF50", fg="white", bd=3, cursor="hand2")
        search_btn.grid(row=0, column=2, padx=10, pady=10, sticky="ew")

        # Products list
        product_list_frame = Frame(self.root, bd=3, relief=RIDGE)
        product_list_frame.grid(row=1, column=0, columnspan=2, padx=10, pady=10, sticky="nsew")

        scroll_y = Scrollbar(product_list_frame, orient=VERTICAL)
        scroll_x = Scrollbar(product_list_frame, orient=HORIZONTAL)
        scroll_x.pack(side=BOTTOM, fill=X)
        scroll_y.pack(side=RIGHT, fill=Y)

        list_columns = ("id", "Categorie", "Fournisseur", "Nom", "Prix", "Qte", "Status")
        self.product_list_table = ttk.Treeview(product_list_frame, columns=list_columns, yscrollcommand=scroll_y.set, xscrollcommand=scroll_x.set)
        self.product_list_table.pack(fill=BOTH, expand=1)
        scroll_x.config(command=self.product_list_table.xview)
        scroll_y.config(command=self.product_list_table.yview)

        self.product_list_table.heading("id", text="ID")
        self.product_list_table.heading("Categorie", text="Catégorie")
        self.product_list_table.heading("Fournisseur", text="Fournissuer")
        self.product_list_table.heading("Nom", text="Nom")
        self.product_list_table.heading("Prix", text="Prix")
        self.product_list_table.heading("Qte", text="Qté")
        self.product_list_table.heading("Status", text="Status")
        self.product_list_table["show"] = "headings"

        self.product_list_table.column("id", width=90)
        self.product_list_table.column("Categorie", width=100)
        self.product_list_table.column("Fournisseur", width=100)
        self.product_list_table.column("Nom", width=100)
        self.product_list_table.column("Prix", width=100)
        self.product_list_table.column("Qte", width=100)
        self.product_list_table.column("Status", width=100)

        self.product_list_table.bind("<ButtonRelease-1>", self.get_data)

        self.show_product()

        # Configure grid weights for responsiveness
        self.root.grid_rowconfigure(1, weight=1)
        self.root.grid_columnconfigure(1, weight=1)
        product_frame.grid_rowconfigure(8, weight=1)
        product_frame.grid_columnconfigure(1, weight=1)
        product_list_frame.grid_rowconfigure(0, weight=1)
        product_list_frame.grid_columnconfigure(0, weight=1)

        # product methods



    def select_image(self):
        filepath = filedialog.askopenfilename(filetypes=[("image files", "*.png *.jpg")])
        if filepath:
            # Récupérer le nom de l'image sélectionnée
            self.image_name = os.path.basename(filepath)
            destination_directory = "C:/laragon/www/gestion article/uploaded_img"  # Remplacez par le chemin de votre répertoire de destination
            destination_path = os.path.join(destination_directory, self.image_name)
            try:
                shutil.copy(filepath, destination_path)
                messagebox.showinfo("Message envoyé", "Image envoyée avec succès", parent=self.root)
            except Exception as e:
                messagebox.showerror("Erreur", f"Une erreur s'est produite: {str(e)}", parent=self.root)
    
    def get_categ_supp(self):
        self.categ_list.append("Vide")
        self.supp_list.append("Vide")
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT name FROM category")
            categs = cur.fetchall()

            if len(categs) > 0:
                del self.categ_list[:]
                self.categ_list.append("Select")
                for item in categs:
                    self.categ_list.append(item[0])

            cur.execute("SELECT name FROM supplier")
            suppls = cur.fetchall()

            if len(suppls) > 0:
                del self.supp_list[:]
                self.supp_list.append("Select")
                for item in suppls:
                    self.supp_list.append(item[0])
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def add_product(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            if self.categ_var.get() == "Vide" or self.supp_var.get() == "Vide" :
                messagebox.showerror("Erreur", "Vous devez remplir d'abord les catégories et fournisseurs", parent=self.root)
            elif self.categ_var.get() == "Select" or self.supp_var.get() == "Select" or self.name_var.get() == "":
                messagebox.showerror("Erreur", "Les champs catégorie, fournisseur, et Nom sont obligatoires", parent=self.root)
            else:
                cur.execute("SELECT * FROM product WHERE name=?", (self.name_var.get(),))
                row = cur.fetchone()
                if row is not None:
                    messagebox.showerror("Erreur", "Produit deja existant", parent=self.root)
                else:
                    values_to_insert = (self.categ_var.get(),
                                        self.supp_var.get(),
                                        self.name_var.get(),
                                        self.price_var.get(),
                                        self.qty_var.get(),
                                        self.status_var.get(),
                                        self.image_name,
                                        )
                    cur.execute("INSERT INTO product (category, supplier, name, price, qty, status,image_prod) VALUES (?,?,?,?,?,?,?)", values_to_insert)
                    con.commit()
                    messagebox.showinfo("Succès", "Produit ajouté avec succès", parent=self.root)
                    ecrire_dans_rapport(date_str, "Ajout d'un nouveau produit de vente", "|Nom : " + self.name_var.get() +"\n|Prix initiale: " +self.price_var.get() +"\n|Quantité : "+ self.qty_var.get()+"|")
                    self.show_product()
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def update_product(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            if self.prod_id_var.get() == "":
                messagebox.showerror("Erreur", "Vous devez selectionner un produit de la liste", parent=self.root)
            else:
                cur.execute("SELECT * FROM product WHERE id=?", (self.prod_id_var.get(),))
                row = cur.fetchone()
                if row is None:
                    messagebox.showerror("Erreur", "ID de Produit Invalid", parent=self.root)
                else:
                    values_to_insert = (
                                        self.categ_var.get(),
                                        self.supp_var.get(),
                                        self.name_var.get(),
                                        self.price_var.get(),
                                        self.qty_var.get(),
                                        self.status_var.get(),
                                        self.image_name,
                                        self.prod_id_var.get()

                                        )
                    cur.execute("UPDATE product set category=?, supplier=?, name=?, price=?, qty=?, status=?, image_prod=? WHERE id=?", values_to_insert)
                    con.commit()
                    messagebox.showinfo("Succès", "Produit modifié avec succès", parent=self.root)
                    ecrire_dans_rapport(date_str, "Modification du produit de vente", "|Nom : " + self.name_var.get() +"\n|Prix: " +self.price_var.get() +"\n|Quantité : "+ self.qty_var.get()+"|")

                    self.show_product()
                    con.close()
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def delete_product(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            if self.prod_id_var.get() == "":
                messagebox.showerror("Erreur", "Vous devez selectionner un produit de la liste", parent=self.root)
            else:
                cur.execute("SELECT * FROM product WHERE id=?", (self.prod_id_var.get(),))
                row = cur.fetchone()
                if row is None:
                    messagebox.showerror("Erreur", "ID du Produit Invalid", parent=self.root)
                else:
                    user_confirm = messagebox.askyesno("Confirmation", "Confirmer la suppression?", parent=self.root)
                    if user_confirm:
                        cur.execute("DELETE FROM product WHERE id=?", (self.prod_id_var.get(),))
                        con.commit()
                        messagebox.showinfo("Succès", "Produit supprimer avec succès", parent=self.root)
                        ecrire_dans_rapport(date_str, "Suppresion du produit de vente", "|Nom : " + self.name_var.get() +"\n|Prix: " +self.price_var.get() +"\n|Quantité : "+ self.qty_var.get()+"|")
                        self.show_product()
                        # self.clear()

        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def show_product(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT * FROM product")
            rows = cur.fetchall()
            self.product_list_table.delete(*self.product_list_table.get_children())
            for row in rows:
                self.product_list_table.insert('',END,values=row)
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def search_product(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            if self.searchOption_var.get() == "Selectionner":
                messagebox.showerror("Erreur", "Selectionner l'option de recherche", parent=self.root)
            elif self.searchText_var.get() == "":
                messagebox.showerror("Erreur", "Champ de recherche vide", parent=self.root)
            else:
                if self.searchOption_var.get() == "Nom":
                    self.searchOption_var.set("name")
                elif self.searchOption_var.get() == "Catégorie":
                    self.searchOption_var.set("category")
                elif self.searchOption_var.get() == "Fournisseur":
                    self.searchOption_var.set("supplier")
                cur.execute("SELECT * FROM product WHERE " + self.searchOption_var.get() + " LIKE '%" + self.searchText_var.get() + "%'")
                rows = cur.fetchall()
                if len(rows) != 0 :
                    self.product_list_table.delete(*self.product_list_table.get_children())
                    for row in rows:
                        self.product_list_table.insert('', END, values=row)
                else:
                    messagebox.showerror("Erreur", "Aucun Produit trouvé!", parent=self.root)
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)


    def get_data(self, ev):
        table_focus = self.product_list_table.focus()
        table_content = (self.product_list_table.item(table_focus))
        row = table_content["values"]
        print(row)

        self.prod_id_var.set(row[0])
        self.categ_var.set(row[1])
        self.supp_var.set(row[2])
        self.name_var.set(row[3])
        self.price_var.set(row[4])
        self.qty_var.set(row[5])
        self.status_var.set(row[6])

    def clear(self):
        self.prod_id_var.set("")
        self.categ_var.set("Select")
        self.supp_var.set("Select")
        self.name_var.set("")
        self.price_var.set("")
        self.qty_var.set("")
        self.status_var.set("")
        self.show_product()
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
                system = Product(root)
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
    messagebox.showwarning(fenetre, "Accès impossible \n Veuillez vous connecter en tant qu'administrateurs pour effectuer cette opération pour accéder à la gestion des produits ")
    fenetre.destroy()
    os.system("python login.py")


