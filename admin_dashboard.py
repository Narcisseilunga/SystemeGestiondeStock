from tkinter import *
from tkinter import ttk, messagebox
import customtkinter as ctk
import subprocess
from utils import lecture_bonne
autorisation = True  # Variable d'autorisation (initialisée à False)
# Votre logique pour vérifier l'autorisation...
if autorisation:
    from employee import Employee
    from supplier import Supplier
    from category import Category
    from product import Product
    from sales import Sales
else:
    # L'utilisateur n'est pas autorisé, redirection vers login.py
    subprocess.run(['python', 'login.py'])
import sqlite3
import os
import threading
import  sys
class StockManager:
    def __init__(self, root_win):
        self.root = root_win
        self.root.geometry("1350x700+0+0")
        self.root.title("Système de Gestion de Stock")
        self.root.iconbitmap(chemin_icone)
        self.root.config(bg="#f0f0f0")  # Couleur de fond plus claire

        # screen Title
        title = Label(self.root, text="Tableau de Bord", font=("Helvetica", 26, "bold"), bg="#f0f0f0", fg="#343A40", anchor="w", padx=20)
        title.place(x=200, y=0, relwidth=1, height=70)

        # logout button
        btn_situation = Button(self.root, text="🗣️ Demande situation", command=self.situation, font=("Helvetica", 11, "bold"), bd=0, bg="#0000FF", fg="white")
        btn_situation.place(x=990, y=10, height=40, width=180)

        logout_btn = Button(self.root, text="Déconnexion", command=self.logout, font=("Helvetica", 11, "bold"), bd=0, bg="#FF5733", fg="white")
        logout_btn.place(x=1180, y=10, height=40, width=120)

        # Menu
        menu_frame = Frame(self.root, bd=0, bg="#2C3E50", relief=RIDGE)
        menu_frame.place(x=0, y=0, width=200, height=400, relheight=1)

        menu_label = Label(menu_frame, text="Menu", font=("Helvetica", 15, "bold"), fg="white", bg="#2980B9")
        menu_label.pack(side=TOP, fill=X)

        button_style = {"font": ("Helvetica", 14, "normal"), "bg": "#2C3E50", "fg": "white", "bd": 0, "cursor": "hand2"}
        employee_btn = Button(menu_frame, text="Employés", command=self.employee, **button_style)
        employee_btn.pack(side=TOP, fill=X)
        supplier_btn = Button(menu_frame, text="Fournisseurs", command=self.supplier, **button_style)
        supplier_btn.pack(side=TOP, fill=X)
        product_btn = Button(menu_frame, text="Produits", command=self.product, **button_style)
        product_btn.pack(side=TOP, fill=X)
        category_btn = Button(menu_frame, text="Catégories", command=self.category, **button_style)
        category_btn.pack(side=TOP, fill=X)
        sales_btn = Button(menu_frame, text="Ventes", command=self.sales, **button_style)
        sales_btn.pack(side=TOP, fill=X)
        sales_btn = Button(menu_frame, text="Rapport", command=self.rapport_mouv, **button_style)
        sales_btn.pack(side=TOP, fill=X)
        quit_btn = Button(menu_frame, text="Quitter", command=self.root.destroy, **button_style)
        quit_btn.pack(side=TOP, fill=X)

        # dashboard content
        label_style = {"font": ("Helvetica", 15, "bold"), "fg": "white", "bd": 5}
        self.employee_label = Label(self.root, text="Total des Employés\n0", bg="#E74C3C", **label_style)
        self.employee_label.place(x=300, y=80, width=300, height=100)
        self.supplier_label = Label(self.root, text="Total des Fournisseurs\n0", bg="#9B59B6", **label_style)
        self.supplier_label.place(x=650, y=80, width=300, height=100)
        self.product_label = Label(self.root, text="Total des Produits\n0", bg="#3498DB", **label_style)
        self.product_label.place(x=1000, y=80, width=300, height=100)
        self.category_label = Label(self.root, text="Total des Catégories\n0", bg="#F1C40F", **label_style)
        self.category_label.place(x=300, y=200, width=300, height=100)
        self.sales_label = Label(self.root, text="Total des Ventes\n0", bg="#1ABC9C", **label_style)
        self.sales_label.place(x=650, y=200, width=300, height=100)

        # sales list
        sales_list_frame = Frame(self.root, bd=3, relief=RIDGE)
        sales_list_frame.place(x=220, y=350, width=420, height=250)

        scroll_y = Scrollbar(sales_list_frame, orient=VERTICAL)
        scroll_x = Scrollbar(sales_list_frame, orient=HORIZONTAL)
        scroll_x.pack(side=BOTTOM, fill=X)
        scroll_y.pack(side=RIGHT, fill=Y)

        sales_list_columns = ("facture_no", "nom_client", "contact_client", "date")
        self.sales_list_table = ttk.Treeview(sales_list_frame, columns=sales_list_columns, yscrollcommand=scroll_y.set, xscrollcommand=scroll_x.set)
        self.sales_list_table.pack(fill=BOTH, expand=1)
        scroll_x.config(command=self.sales_list_table.xview)
        scroll_y.config(command=self.sales_list_table.yview)

        self.sales_list_table.heading("facture_no", text="Facture No.")
        self.sales_list_table.heading("nom_client", text="Nom Client")
        self.sales_list_table.heading("contact_client", text="Contact Client")
        self.sales_list_table.heading("date", text="Date")
        self.sales_list_table["show"] = "headings"

        self.sales_list_table.column("facture_no", width=100)
        self.sales_list_table.column("nom_client", width=100)
        self.sales_list_table.column("contact_client", width=100)
        self.sales_list_table.column("date", width=100)

        # line_sale list
        line_sale_list_columns = Frame(self.root, bd=3, relief=RIDGE)
        line_sale_list_columns.place(x=660, y=350, width=650, height=250)

        scroll_y = Scrollbar(line_sale_list_columns, orient=VERTICAL)
        scroll_x = Scrollbar(line_sale_list_columns, orient=HORIZONTAL)
        scroll_x.pack(side=BOTTOM, fill=X)
        scroll_y.pack(side=RIGHT, fill=Y)

        sales_list_columns = ("facture_no", "nom_prod", "prix", "qte")
        self.line_sale_list_table = ttk.Treeview(line_sale_list_columns, columns=sales_list_columns, yscrollcommand=scroll_y.set, xscrollcommand=scroll_x.set)
        self.line_sale_list_table.pack(fill=BOTH, expand=1)
        scroll_x.config(command=self.line_sale_list_table.xview)
        scroll_y.config(command=self.line_sale_list_table.yview)

        self.line_sale_list_table.heading("facture_no", text="Facture No.")
        self.line_sale_list_table.heading("nom_prod", text="Nom Produit")
        self.line_sale_list_table.heading("prix", text="Prix")
        self.line_sale_list_table.heading("qte", text="QTE")
        self.line_sale_list_table["show"] = "headings"

        self.line_sale_list_table.column("facture_no", width=100)
        self.line_sale_list_table.column("nom_prod", width=100)
        self.line_sale_list_table.column("prix", width=100)
        self.line_sale_list_table.column("qte", width=100)

        # footer
        footer = Label(self.root, text="PAR CASTERMAN, AYANA, NITA & CHALSY", font=("Helvetica", 15, "normal"), bg="#2EB086", fg="#313552")
        footer.pack(side=BOTTOM, fill=X)
        
        self.update_content()
        self.show_sales()
        self.show_line_sale()
    # =====================================================
    def employee(self):
        self.new_window = Toplevel(self.root)
        self.emp_manager = Employee(self.new_window)

    def supplier(self):
        self.new_window = Toplevel(self.root)
        self.supp_manager = Supplier(self.new_window)

    def category(self):
        self.new_window = Toplevel(self.root)
        self.category_manager = Category(self.new_window)

    def product(self):
        self.new_window = Toplevel(self.root)
        self.product_manager = Product(self.new_window)

    def sales(self):
        self.new_window = Toplevel(self.root)
        self.sales_manager = Sales(self.new_window)

    def update_content(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT COUNT(*) FROM employee")
            p = cur.fetchone()[0]
            self.employee_label.config(text=f"Total des Employés\n{p}")

            cur.execute("SELECT COUNT(*) FROM supplier")
            supp = cur.fetchone()[0]
            self.supplier_label.config(text=f"Total des Fournisseurs\n{supp}")

            cur.execute("SELECT COUNT(*) FROM product")
            prd = cur.fetchone()[0]
            self.product_label.config(text=f"Total des Produits\n{prd}")

            cur.execute("SELECT COUNT(*) FROM category")
            cat = cur.fetchone()[0]
            self.category_label.config(text=f"Total des Catégories\n{cat}")

            self.sales_label.config(text=f"Total des Ventes\n{str(len(os.listdir('bills')))}")

            threading.Timer(2.0, self.update_content).start()
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def logout(self):
        self.root.destroy()
        os.system("python login.py")

    def show_sales(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT * FROM sales")
            rows = cur.fetchall()
            self.sales_list_table.delete(*self.sales_list_table.get_children())
            for row in rows:
                self.sales_list_table.insert('',END,values=row)
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

    def rapport_mouv(self):
        from rapport import FenetrePrincipale

        self.new_window = Toplevel(self.root)
        self.rapport_manager = FenetrePrincipale(self.new_window)

    def situation(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT COUNT(*) FROM employee")
            p = cur.fetchone()[0]
            text1 = f"{p} Total des Employés\n"

            cur.execute("SELECT COUNT(*) FROM supplier")
            supp = cur.fetchone()[0]
            text2 = f"{supp} Total des Fournisseurs\n"

            cur.execute("SELECT COUNT(*) FROM product")
            prd = cur.fetchone()[0]
            text3 = f"{prd} Total des Produits\n"

            cur.execute("SELECT COUNT(*) FROM category")
            cat = cur.fetchone()[0]
            text4 = f"{cat} Total des Catégories\n"

            text5 = f"{str(len(os.listdir('bills')))} Total des Ventes\n"
            lecture_bonne("Dans le système de gestion de stock on a"+text1 + text2 + text3 + text4 + text5, "voici donc la situation")
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)
    def show_line_sale(self):
        con = sqlite3.connect("system.db")
        cur = con.cursor()
        try:
            cur.execute("SELECT ls.invoice, p.name, ls.price, ls.qty FROM line_sale ls JOIN product p ON ls.product_id=p.id")
            rows = cur.fetchall()
            self.line_sale_list_table.delete(*self.line_sale_list_table.get_children())
            for row in rows:
                self.line_sale_list_table.insert('',END,values=row)
        except Exception as ex:
            messagebox.showerror("Erreur", f"Erreur: {str(ex)}", parent=self.root)

chemin_icone = "stock.ico"
def fonction_destination(autorisation, *params):
    if autorisation == 'True':
        # L'utilisateur est déjà connecté
        print("Utilisateur déjà connecté.")
        if __name__ == "__main__":
            root = Tk()
            system = StockManager(root)
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
    fenetre.iconbitmap(chemin_icone)
    fenetre.geometry("500x120")
    fenetre.title = "Fenetre de redirection"
    fenetre.config(bg="black")
    messagebox.showwarning(fenetre,"Accès impossible \n Veuillez vous connecter en tant qu'administrateur pour effectuer cette opération pour accéder à la gestion d'administrateur' ")
    fenetre.destroy()
    os.system("python login.py")
