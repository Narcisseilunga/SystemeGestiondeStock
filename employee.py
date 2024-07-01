from tkinter import *
import customtkinter as ctk
from tkinter import ttk, messagebox
from utils import ecrire_dans_rapport
import sqlite3
import sys
import os
import datetime
class Employee:
    def __init__(self, root_win):
        self.root = root_win
        self.root.geometry("1100x500+220+130")
        self.root.title("Gestion des Employés")
        self.root.iconbitmap(chemin_icone)
        self.root.config(bg="white")
        self.root.focus_force()

        # system variables
        self.searchOption_var = StringVar()
        self.searchText_var = StringVar()
        self.emp_id_var = StringVar()
        self.name_var = StringVar()
        self.contact_var = StringVar()
        self.gender_var = StringVar()
        self.dob_var = StringVar()
        self.doj_var = StringVar()
        self.email_var = StringVar()
        self.password_var = StringVar()
        self.usertype_var = StringVar()
        self.salary_var = StringVar()
        self.statut_var = StringVar()

        # title
        title = Label(self.root, text="Gestion des employées", font=("Helvetica", 18, "bold"), bg="#2EB086", fg="white")
        title.pack(side=TOP, fill=X, pady=10)

        # content
        content_frame = Frame(self.root, bg="white")
        content_frame.pack(fill=BOTH, expand=True, padx=20, pady=20)

        # first row
        emp_id_label = Label(content_frame, text="Emp ID", font=("Helvetica", 14), bg="white")
        emp_id_label.grid(row=0, column=0, padx=10, pady=5, sticky=W)
        emp_id_txt = Entry(content_frame, textvariable=self.emp_id_var, font=("Helvetica", 14), bg="#EEE6CE", bd=1)
        emp_id_txt.grid(row=0, column=1, padx=10, pady=5, sticky=W)

        gender_label = Label(content_frame, text="Sexe", font=("Helvetica", 14), bg="white")
        gender_label.grid(row=0, column=2, padx=10, pady=5, sticky=W)
        gender_opt = ttk.Combobox(content_frame, textvariable=self.gender_var, values=("Select", "Homme", "Femme"), state="readonly", justify=CENTER, font=("Helvetica", 14))
        gender_opt.grid(row=0, column=3, padx=10, pady=5, sticky=W)
        gender_opt.current(0)

        contact_label = Label(content_frame, text="Contact", font=("Helvetica", 14), bg="white")
        contact_label.grid(row=0, column=4, padx=10, pady=5, sticky=W)
        contact_txt = Entry(content_frame, textvariable=self.contact_var, font=("Helvetica", 14), bd=1, bg="#EEE6CE")
        contact_txt.grid(row=0, column=5, padx=10, pady=5, sticky=W)

        # second row
        name_label = Label(content_frame, text="Nom", font=("Helvetica", 14), bg="white")
        name_label.grid(row=1, column=0, padx=10, pady=5, sticky=W)
        name_txt = Entry(content_frame, textvariable=self.name_var, font=("Helvetica", 14), bg="#EEE6CE", bd=1)
        name_txt.grid(row=1, column=1, padx=10, pady=5, sticky=W)

        dob_label = Label(content_frame, text="Date Naiss", font=("Helvetica", 14), bg="white")
        dob_label.grid(row=1, column=2, padx=10, pady=5, sticky=W)
        dob_txt = Entry(content_frame, textvariable=self.dob_var, font=("Helvetica", 14), bg="#EEE6CE", bd=1)
        dob_txt.grid(row=1, column=3, padx=10, pady=5, sticky=W)

        doj_label = Label(content_frame, text="Date Adh", font=("Helvetica", 14), bg="white")
        doj_label.grid(row=1, column=4, padx=10, pady=5, sticky=W)
        doj_txt = Entry(content_frame, textvariable=self.doj_var, font=("Helvetica", 14), bd=1, bg="#EEE6CE")
        doj_txt.grid(row=1, column=5, padx=10, pady=5, sticky=W)

        # third row
        email_label = Label(content_frame, text="Email", font=("Helvetica", 14), bg="white")
        email_label.grid(row=2, column=0, padx=10, pady=5, sticky=W)
        email_txt = Entry(content_frame, textvariable=self.email_var, font=("Helvetica", 14), bg="#EEE6CE", bd=1)
        email_txt.grid(row=2, column=1, padx=10, pady=5, sticky=W)

        password_label = Label(content_frame, text="Password", font=("Helvetica", 14), bg="white")
        password_label.grid(row=2, column=2, padx=10, pady=5, sticky=W)
        password_txt = Entry(content_frame, textvariable=self.password_var, font=("Helvetica", 14), bg="#EEE6CE", bd=1)
        password_txt.grid(row=2, column=3, padx=10, pady=5, sticky=W)

        user_type_label = Label(content_frame, text="Type", font=("Helvetica", 14), bg="white")
        user_type_label.grid(row=2, column=4, padx=10, pady=5, sticky=W)
        user_type_opt = ttk.Combobox(content_frame, textvariable=self.usertype_var, values=("Admin", "Employé"), state="readonly", justify=CENTER, font=("Helvetica", 14))
        user_type_opt.grid(row=2, column=5, padx=10, pady=5, sticky=W)
        user_type_opt.current(0)

        # fourth row
        address_label = Label(content_frame, text="Adresse", font=("Helvetica", 14), bg="white")
        address_label.grid(row=3, column=0, padx=10, pady=5, sticky=W)
        self.address_txt = Text(content_frame, font=("Helvetica", 14), bg="#EEE6CE", bd=1, height=3, width=30)
        self.address_txt.grid(row=3, column=1, padx=10, pady=5, sticky=W, columnspan=2)

        salary_label = Label(content_frame, text="Salaire", font=("Helvetica", 14), bg="white")
        salary_label.grid(row=3, column=3, padx=10, pady=5, sticky=W)
        salary_txt = Entry(content_frame, textvariable=self.salary_var, font=("Helvetica", 14), bg="#EEE6CE", bd=1)
        salary_txt.grid(row=3, column=4, padx=10, pady=5, sticky=W)

        statut_label = Label(content_frame, text="Statut", font=("Helvetica", 14), bg="white")
        statut_label.grid(row=3, column=5, padx=10, pady=5, sticky=W)
        statut_opt = ttk.Combobox(content_frame, textvariable=self.statut_var, values=("Actif", "Inactif", "Suspendue"), state="readonly", justify=CENTER, font=("Helvetica", 14))
        statut_opt.grid(row=3, column=6, padx=10, pady=5, sticky=W)

        # buttons
        button_frame = Frame(content_frame, bg="white")
        button_frame.grid(row=4, column=0, columnspan=7, pady=20)

        add_btn = Button(button_frame, text="Ajouter", command=self.add_emp, font=("Helvetica", 11, "bold"), bg="#2EB086", fg="white", bd=3, cursor="hand2")
        add_btn.grid(row=0, column=0, padx=10)

        update_btn = Button(button_frame, text="Modifier", command=self.update_emp, font=("Helvetica", 11, "bold"), bg="#0AA1DD", fg="white", bd=3, cursor="hand2")
        update_btn.grid(row=0, column=1, padx=10)

        delete_btn = Button(button_frame, text="Supprimer", command=self.delete_emp, font=("Helvetica", 11, "bold"), bg="#B8405E", fg="white", bd=3, cursor="hand2")
        delete_btn.grid(row=0, column=2, padx=10)

        clear_btn = Button(button_frame, text="Effacer", command=self.clear, font=("Helvetica", 11, "bold"), bg="#313552", fg="white", bd=3, cursor="hand2")
        clear_btn.grid(row=0, column=3, padx=10)

        # search employee
        search_frame = LabelFrame(self.root, text="Chercher un Employé", font=("Helvetica", 11), bg="white", bd=2)
        search_frame.pack(fill=X, padx=20, pady=10)

        options_box = ttk.Combobox(search_frame, textvariable=self.searchOption_var, values=("Selectionner", "Email", "Nom", "Contact"), state="readonly", justify=CENTER, font=("Helvetica", 11))
        options_box.grid(row=0, column=0, padx=10, pady=10)
        options_box.current(0)

        search_box = Entry(search_frame, textvariable=self.searchText_var, font=("Helvetica", 11), bg="#EEE6CE")
        search_box.grid(row=0, column=1, padx=10, pady=10)

        search_btn = Button(search_frame, text="Chercher", command=self.search_emp, font=("Helvetica", 11, "bold"), bg="#2EB086", fg="white", bd=3, cursor="hand2")
        search_btn.grid(row=0, column=2, padx=10, pady=10)

        # employees list
        emp_list_frame = Frame(self.root, bd=3, relief=RIDGE)
        emp_list_frame.pack(fill=BOTH, expand=True, padx=20, pady=10)

        scroll_y = Scrollbar(emp_list_frame, orient=VERTICAL)
        scroll_x = Scrollbar(emp_list_frame, orient=HORIZONTAL)
        scroll_x.pack(side=BOTTOM, fill=X)
        scroll_y.pack(side=RIGHT, fill=Y)

        list_columns = ("id", "nom", "email", "sexe", "contact", "date.naiss", "date.adh", "password", "type", "adresse", "salaire", "statut")
        self.emp_list_table = ttk.Treeview(emp_list_frame, columns=list_columns, yscrollcommand=scroll_y.set, xscrollcommand=scroll_x.set)
        self.emp_list_table.pack(fill=BOTH, expand=True)
        scroll_x.config(command=self.emp_list_table.xview)
        scroll_y.config(command=self.emp_list_table.yview)

        self.emp_list_table.heading("id", text="ID")
        self.emp_list_table.heading("nom", text="Nom")
        self.emp_list_table.heading("email", text="Email")
        self.emp_list_table.heading("sexe", text="Sexe")
        self.emp_list_table.heading("contact", text="Contact")
        self.emp_list_table.heading("date.naiss", text="Date.Naiss")
        self.emp_list_table.heading("date.adh", text="Date.Adh")
        self.emp_list_table.heading("password", text="Password")
        self.emp_list_table.heading("type", text="Type")
        self.emp_list_table.heading("adresse", text="Adresse")
        self.emp_list_table.heading("salaire", text="Salaire")
        self.emp_list_table.heading("statut", text="Statut")
        self.emp_list_table["show"] = "headings"

        self.emp_list_table.column("id", width=90)
        self.emp_list_table.column("nom", width=100)
        self.emp_list_table.column("email", width=100)
        self.emp_list_table.column("sexe", width=100)
        self.emp_list_table.column("contact", width=100)
        self.emp_list_table.column("date.naiss", width=100)
        self.emp_list_table.column("date.adh", width=100)
        self.emp_list_table.column("password", width=100)
        self.emp_list_table.column("type", width=100)
        self.emp_list_table.column("adresse", width=100)
        self.emp_list_table.column("salaire", width=100)
        self.emp_list_table.column("statut", width=100)

        self.emp_list_table.bind("<ButtonRelease-1>", self.get_data)

        self.show_emp()
    # employee methods
    def add_emp(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            if self.emp_id_var.get() == "":
                messagebox.showerror("Erreur", "ID de l'employé doit être saisie", parent=self.root)
            else:
                cur.execute("SELECT * FROM employee WHERE id=?", (self.emp_id_var.get(),))
                row = cur.fetchone()
                if row is not None:
                    messagebox.showerror("Erreur", "ID deja existant, saisir un autre", parent=self.root)
                else:
                    values_to_insert = (self.emp_id_var.get(),
                                        self.name_var.get(),
                                        self.email_var.get(),
                                        self.gender_var.get(),
                                        self.contact_var.get(),
                                        self.dob_var.get(),
                                        self.doj_var.get(),
                                        self.password_var.get(),
                                        self.usertype_var.get(),
                                        self.address_txt.get('1.0', END),
                                        self.salary_var.get(),
                                        self.statut_var.get()

                                        )
                    cur.execute("INSERT INTO employee (id, name, email, gender, contact, dob, doj, password, type, address, salary,statut_emp) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)", values_to_insert)
                    con.commit()
                    messagebox.showinfo("Succès", "Employé est ajouté avec succès", parent=self.root)
                    ecrire_dans_rapport(date_str, "Ajout d'un nouveau catégorie de produit", self.name_var.get())

                    self.show_emp()
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def show_emp(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT * FROM employee")
            rows = cur.fetchall()
            self.emp_list_table.delete(*self.emp_list_table.get_children())
            for row in rows:
                self.emp_list_table.insert('',END,values=row)
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def get_data(self, ev):
        table_focus = self.emp_list_table.focus()
        table_content = (self.emp_list_table.item(table_focus))
        row = table_content["values"]
        print(row)

        self.emp_id_var.set(row[0])
        self.name_var.set(row[1])
        self.email_var.set(row[2])
        self.gender_var.set(row[3])
        self.contact_var.set(row[4])
        self.dob_var.set(row[5])
        self.doj_var.set(row[6])
        self.password_var.set(row[7])
        self.usertype_var.set(row[8])
        self.address_txt.delete('1.0', END)
        self.address_txt.insert(END, row[9])
        self.salary_var.set(row[10])
        self.statut_var.set(row[11])

    def update_emp(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            if self.emp_id_var.get() == "":
                messagebox.showerror("Erreur", "ID de l'employé doit être saisi", parent=self.root)
            else:
                cur.execute("SELECT * FROM employee WHERE id=?", (self.emp_id_var.get(),))
                row = cur.fetchone()
                if row is None:
                    messagebox.showerror("Erreur", "ID de l'Employé Invalid", parent=self.root)
                else:
                    values_to_insert = (
                                        self.name_var.get(),
                                        self.email_var.get(),
                                        self.gender_var.get(),
                                        self.contact_var.get(),
                                        self.dob_var.get(),
                                        self.doj_var.get(),
                                        self.password_var.get(),
                                        self.usertype_var.get(),
                                        self.address_txt.get('1.0', END),
                                        self.salary_var.get(),
                                        self.emp_id_var.get(),
                                        self.statut_var.get(),
                                        )
                    cur.execute("UPDATE employee set name=?, email=?, gender=?, contact=?, dob=?, doj=?, password=?, type=?, address=?, salary=?, statut_emp where id=?", values_to_insert)
                    con.commit()
                    messagebox.showinfo("Succès", "Employé est modifié avec succès", parent=self.root)
                    ecrire_dans_rapport(date_str, "Modification d'un employé", self.name_var.get())
                    self.show_emp()
                    con.close()
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def delete_emp(self):
        con = sqlite3.connect('system.db')
        cur = con.cursor()
        try:
            if self.emp_id_var.get() == "":
                messagebox.showerror("Erreur", "ID de l'employé doit être saisi", parent=self.root)
            else:
                cur.execute("SELECT * FROM employee WHERE id=?", (self.emp_id_var.get(),))
                row = cur.fetchone()
                if row is None:
                    messagebox.showerror("Erreur", "ID de l'Employé Invalid", parent=self.root)
                else:
                    user_confirm = messagebox.askyesno("Confirmation", "Confirmer la suppression?", parent=self.root)
                    if user_confirm:
                        cur.execute("DELETE FROM employee WHERE id=?", (self.emp_id_var.get(),))
                        con.commit()
                        messagebox.showinfo("Succès", "Employé est supprimer avec succès", parent=self.root)
                        ecrire_dans_rapport(date_str, "Suppresion d'un  employée", self.name_var.get())

                        self.show_emp()
                        self.clear()

        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def clear(self):
        self.emp_id_var.set("")
        self.name_var.set("")
        self.email_var.set("")
        self.gender_var.set("Select")
        self.contact_var.set("")
        self.dob_var.set("")
        self.doj_var.set("")
        self.password_var.set("")
        self.usertype_var.set("Admin")
        self.address_txt.delete('1.0', END)
        self.salary_var.set("")
        self.searchText_var.set("")
        self.searchOption_var.set("Selectionner")
        self.show_emp()
        self.statut_var.set()

    def search_emp(self):
        con = sqlite3.connect('system.db')
        cur = con.cursor()
        try:
            if self.searchOption_var.get() == "Selectionner":
                messagebox.showerror("Erreur", "Selectionner l'option de recherche", parent=self.root)
            elif self.searchText_var.get() == "":
                messagebox.showerror("Erreur", "Champ de recherche vide", parent=self.root)
            else:
                if self.searchOption_var.get() == "Nom":
                    self.searchOption_var.set("name")
                cur.execute("SELECT * FROM employee WHERE " + self.searchOption_var.get() + " LIKE '%" + self.searchText_var.get() + "%'")
                rows = cur.fetchall()
                if len(rows) != 0 :
                    self.emp_list_table.delete(*self.emp_list_table.get_children())
                    for row in rows:
                        self.emp_list_table.insert('', END, values=row)
                else:
                    messagebox.showerror("Erreur", "Aucun employé trouvé!", parent=self.root)
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
                system = Employee(root)
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


