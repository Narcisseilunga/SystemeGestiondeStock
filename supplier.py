import datetime
from tkinter import *
from tkinter import ttk, messagebox
from utils import ecrire_dans_rapport
import sqlite3
import sys
import customtkinter as ctk
import os
class Supplier:
    def __init__(self, root_win):
        self.root = root_win
        self.root.geometry("1100x500+220+130")
        self.root.iconbitmap("stock.ico")
        self.root.title("Gestion des Employés")
        self.root.config(bg="#E8E8E8")  # Couleur de fond plus douce
        self.root.focus_force()

        # system variables
        self.searchOption_var = StringVar()
        self.searchText_var = StringVar()
        self.supp_invoice_var = StringVar()
        self.name_var = StringVar()
        self.contact_var = StringVar()

        # search employee
        search_frame = LabelFrame(self.root, text="Chercher un Fournisseur", font=("Arial", 12, "bold"), bg="#E8E8E8", bd=2)
        search_frame.place(x=250, y=260, width=600, height=70)

        # search options
        search_label = Label(search_frame, text="Recherche par Facture No.", font=("Arial", 12, "normal"), bg="#E8E8E8")
        search_label.place(x=10, y=10)

        search_box = Entry(search_frame, textvariable=self.searchText_var, font=("Arial", 12, "normal"), bg="#D3D3D3")
        search_box.place(x=200, y=10, width=200, height=25)
        search_btn = Button(search_frame, text="Chercher", command=self.search_supp, font=("Arial", 12, "bold"), bg="#4CAF50", fg="white", bd=3, cursor="hand2")
        search_btn.place(x=410, y=10, width=150, height=25)

        # title
        title = Label(self.root, text="Information du Fournisseur", font=("Arial", 16, "bold"), bg="#4CAF50", fg="white")
        title.place(x=50, y=20, width=1000)

        # content
        # -----first row-----
        supp_invoice_label = Label(self.root, text="Facture No.", font=("Arial", 12, "normal"), bg="#E8E8E8")
        supp_invoice_label.place(x=50, y=70)
        supp_invoice_txt = Entry(self.root, textvariable=self.supp_invoice_var, font=("Arial", 12, "normal"), bg="#D3D3D3", bd=1)
        supp_invoice_txt.place(x=170, y=70, width=180)

        # -----second row-----
        name_label = Label(self.root, text="Nom", font=("Arial", 12, "normal"), bg="#E8E8E8")
        name_label.place(x=50, y=110)
        name_txt = Entry(self.root, textvariable=self.name_var, font=("Arial", 12, "normal"), bg="#D3D3D3", bd=1)
        name_txt.place(x=170, y=110, width=180)

        # -----third row-----
        contact_label = Label(self.root, text="Contact", font=("Arial", 12, "normal"), bg="#E8E8E8")
        contact_label.place(x=50, y=150)
        contact_txt = Entry(self.root, textvariable=self.contact_var, font=("Arial", 12, "normal"), bg="#D3D3D3", bd=1)
        contact_txt.place(x=170, y=150, width=180)

        # -----fourth row-----
        address_desc = Label(self.root, text="Description", font=("Arial", 12, "normal"), bg="#E8E8E8")
        address_desc.place(x=50, y=190)
        self.desc_txt = Text(self.root, font=("Arial", 12, "normal"), bg="#D3D3D3", bd=1)
        self.desc_txt.place(x=170, y=190, width=300, height=60)

        # buttons
        add_btn = Button(self.root, text="Ajouter", command=self.add_supp, font=("Arial", 12, "bold"), bg="#4CAF50", fg="white", bd=3, cursor="hand2")
        add_btn.place(x=500, y=225, width=110, height=25)
        update_btn = Button(self.root, text="Modifier", command=self.update_supp, font=("Arial", 12, "bold"), bg="#2196F3", fg="white", bd=3, cursor="hand2")
        update_btn.place(x=620, y=225, width=110, height=25)
        delete_btn = Button(self.root, text="Supprimer", command=self.delete_supp, font=("Arial", 12, "bold"), bg="#F44336", fg="white", bd=3, cursor="hand2")
        delete_btn.place(x=740, y=225, width=110, height=25)
        clear_btn = Button(self.root, text="Effacer", command=self.clear, font=("Arial", 12, "bold"), bg="#9E9E9E", fg="white", bd=3, cursor="hand2")
        clear_btn.place(x=860, y=225, width=110, height=25)

        # supplier list
        supp_list_frame = Frame(self.root, bd=3, relief=RIDGE)
        supp_list_frame.place(x=0, y=350, relwidth=1, height=150)

        scroll_y = Scrollbar(supp_list_frame, orient=VERTICAL)
        scroll_x = Scrollbar(supp_list_frame, orient=HORIZONTAL)
        scroll_x.pack(side=BOTTOM, fill=X)
        scroll_y.pack(side=RIGHT, fill=Y)

        list_columns = ("facture", "nom", "contact", "desc")
        self.supp_list_table = ttk.Treeview(supp_list_frame, columns=list_columns, yscrollcommand=scroll_y.set, xscrollcommand=scroll_x.set)
        self.supp_list_table.pack(fill=BOTH, expand=1)
        scroll_x.config(command=self.supp_list_table.xview)
        scroll_y.config(command=self.supp_list_table.yview)

        self.supp_list_table.heading("facture", text="Facture No.")
        self.supp_list_table.heading("nom", text="Nom")
        self.supp_list_table.heading("contact", text="Contact")
        self.supp_list_table.heading("desc", text="Description")
        self.supp_list_table["show"] = "headings"

        self.supp_list_table.column("facture", width=90)
        self.supp_list_table.column("nom", width=100)
        self.supp_list_table.column("contact", width=100)
        self.supp_list_table.column("desc", width=100)

        self.supp_list_table.bind("<ButtonRelease-1>", self.get_data)

        self.show_supp()    # supplier methods
    def add_supp(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            if self.supp_invoice_var.get() == "":
                messagebox.showerror("Erreur", "Facture no. du fournissuer doit être saisie", parent=self.root)
            else:
                cur.execute("SELECT * FROM supplier WHERE invoice=?", (self.supp_invoice_var.get(),))
                row = cur.fetchone()
                if row is not None:
                    messagebox.showerror("Erreur", "Facture no. deja existant, saisir un autre", parent=self.root)
                else:
                    values_to_insert = (self.supp_invoice_var.get(),
                                        self.name_var.get(),
                                        self.contact_var.get(),
                                        self.desc_txt.get('1.0', END),
                                        )
                    cur.execute("INSERT INTO supplier (invoice, name, contact, desc) VALUES (?,?,?,?)", values_to_insert)
                    con.commit()
                    messagebox.showinfo("Succès", "Fournisseur est ajouté avec succès", parent=self.root)
                    ecrire_dans_rapport(date_str, "Ajout d'un nouveau Fournisseur de produit", self.name_var.get())
                    self.show_supp()
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def show_supp(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT * FROM supplier")
            rows = cur.fetchall()
            self.supp_list_table.delete(*self.supp_list_table.get_children())
            for row in rows:
                self.supp_list_table.insert('',END,values=row)
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def get_data(self, ev):
        table_focus = self.supp_list_table.focus()
        table_content = (self.supp_list_table.item(table_focus))
        row = table_content["values"]
        # print(row)

        self.supp_invoice_var.set(row[0])
        self.name_var.set(row[1])
        self.contact_var.set(row[2])
        self.desc_txt.delete('1.0', END)
        self.desc_txt.insert(END, row[3])

    def update_supp(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            if self.supp_invoice_var.get() == "":
                messagebox.showerror("Erreur", "Facture no. du fournisseur doit être saisi", parent=self.root)
            else:
                cur.execute("SELECT * FROM supplier WHERE invoice=?", (self.supp_invoice_var.get(),))
                row = cur.fetchone()
                if row is None:
                    messagebox.showerror("Erreur", "Facture no. Invalid", parent=self.root)
                else:
                    values_to_insert = (
                                        self.name_var.get(),
                                        self.contact_var.get(),
                                        self.desc_txt.get('1.0', END),
                                        self.supp_invoice_var.get(),
                                        )
                    cur.execute("UPDATE supplier set name=?, contact=?, desc=? where invoice=?", values_to_insert)
                    con.commit()
                    messagebox.showinfo("Succès", "Fournisseur modifié avec succès", parent=self.root)
                    ecrire_dans_rapport(date_str, "Modification du fournisseur de produit", self.name_var.get())
                    self.show_supp()
                    con.close()
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def delete_supp(self):
        con = sqlite3.connect('system.db')
        cur = con.cursor()
        try:
            if self.supp_invoice_var.get() == "":
                messagebox.showerror("Erreur", "Facture no. doit être saisi", parent=self.root)
            else:
                cur.execute("SELECT * FROM supplier WHERE invoice=?", (self.supp_invoice_var.get(),))
                row = cur.fetchone()
                if row is None:
                    messagebox.showerror("Erreur", "Facture no. Invalid", parent=self.root)
                else:
                    user_confirm = messagebox.askyesno("Confirmation", "Confirmer la suppression?", parent=self.root)
                    if user_confirm:
                        cur.execute("DELETE FROM supplier WHERE invoice=?", (self.supp_invoice_var.get(),))
                        con.commit()
                        messagebox.showinfo("Succès", "Fournisseur supprimé avec succès", parent=self.root)
                        ecrire_dans_rapport(date_str, "Suppression du Fournisseur de produit", self.name_var.get())
                        self.show_supp()
                        self.clear()

        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def clear(self):
        self.supp_invoice_var.set("")
        self.name_var.set("")
        self.contact_var.set("")
        self.desc_txt.delete('1.0', END)
        self.searchText_var.set("")
        self.show_supp()

    def search_supp(self):
        con = sqlite3.connect('system.db')
        cur = con.cursor()
        try:
            if self.searchText_var.get() == "":
                messagebox.showerror("Erreur", "Champ de recherche vide", parent=self.root)
            else:
                cur.execute("SELECT * FROM supplier WHERE invoice=?", (self.searchText_var.get(),))
                row = cur.fetchone()
                if row is not None:
                    self.supp_list_table.delete(*self.supp_list_table.get_children())
                    self.supp_list_table.insert('', END, values=row)
                else:
                    messagebox.showerror("Erreur", "Aucun fournisseur trouvé!", parent=self.root)
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
                system = Supplier(root)
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
    messagebox.showwarning(fenetre,
                           "Accès impossible \n Veuillez vous connecter en tant qu'administrateur pour effectuer cette opération pour accéder à la gestion de fournisseurs ")
    fenetre.destroy()
    os.system("python login.py")
