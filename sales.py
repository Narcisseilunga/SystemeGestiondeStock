from tkinter import *
from tkinter import ttk, messagebox
from PIL import Image, ImageTk
import customtkinter as ctk
import sqlite3
import os
import sys
class Sales:
    def __init__(self, root_win):
        self.root = root_win
        self.root.geometry("1100x500+220+130")
        self.root.iconbitmap("stock.ico")
        self.root.title("Gestion des Ventes")
        self.root.config(bg="white")
        self.root.focus_force()
        
        # system variables
        self.invoice_var = StringVar()
        self.bills_list = []

        # title
        title = Label(self.root, text="Factures des Ventes", font=("Helvetica", 16, "bold"), bg="#2EB086", fg="white")
        title.pack(pady=10, padx=10, fill=X)

        invoice_frame = Frame(self.root, bg="white")
        invoice_frame.pack(pady=10, padx=10, fill=X)

        invoice_label = Label(invoice_frame, text="Facture No.", font=("Helvetica", 14), bg="white")
        invoice_label.pack(side=LEFT, padx=10)
        invoice_txt = Entry(invoice_frame, textvariable=self.invoice_var, font=("Helvetica", 14), bg="white")
        invoice_txt.pack(side=LEFT, padx=10, fill=X, expand=True)

        search_btn = Button(invoice_frame, text="Chercher", command=self.search, font=("Helvetica", 14), bg="#2EB086", fg="white", bd=0, cursor="hand2")
        search_btn.pack(side=LEFT, padx=10)
        clear_btn = Button(invoice_frame, text="Effacer", command=self.clear, font=("Helvetica", 14), bg="#313552", fg="white", bd=0, cursor="hand2")
        clear_btn.pack(side=LEFT, padx=10)

        # sales list
        sales_frame = Frame(self.root, bd=2, bg="white")
        sales_frame.pack(pady=10, padx=10, fill=BOTH, expand=True, side=LEFT)

        scroll_y = Scrollbar(sales_frame, orient=VERTICAL)
        scroll_x = Scrollbar(sales_frame, orient=HORIZONTAL)
        scroll_x.pack(side=BOTTOM, fill=X)
        scroll_y.pack(side=RIGHT, fill=Y)

        self.sales_list = Listbox(sales_frame, font=("Helvetica", 14), bg="white", yscrollcommand=scroll_y.set, xscrollcommand=scroll_x.set)
        self.sales_list.pack(fill=BOTH, expand=True)
        scroll_x.config(command=self.sales_list.xview)
        scroll_y.config(command=self.sales_list.yview)
        self.sales_list.bind("<ButtonRelease-1>", self.get_data)

        # bills show area
        bill_frame = Frame(self.root, bd=2, bg="white")
        bill_frame.pack(pady=10, padx=10, fill=BOTH, expand=True, side=LEFT)

        title_bill = Label(bill_frame, text="Affichage Facture", font=("Helvetica", 14, "bold"), bg="#2EB086", fg="white")
        title_bill.pack(side=TOP, fill=X)
        scroll_bill_y = Scrollbar(bill_frame, orient=VERTICAL)
        scroll_bill_y.pack(side=RIGHT, fill=Y)
        self.bill_area = Text(bill_frame, font=("Helvetica", 12), bg="white", yscrollcommand=scroll_bill_y.set)
        self.bill_area.pack(fill=BOTH, expand=True)
        scroll_bill_y.config(command=self.bill_area.yview)

        # image
        self.bill_img = Image.open("images/sales_img.png")
        self.bill_img = self.bill_img.resize((400, 250))
        self.bill_img = ImageTk.PhotoImage(self.bill_img)

        img_label = Label(self.root, image=self.bill_img, bd=0, bg="white")
        img_label.pack(pady=10, padx=10, side=RIGHT)

        self.show()


        # sales methods

    def show(self):
        del self.bills_list[:]
        self.sales_list.delete(0, END)
        for i in os.listdir("bills"):
            if i.split(".")[-1] == "txt":
                self.sales_list.insert(END, i)
                self.bills_list.append(i.split(".")[0])

    def get_data(self, ev):
        row_index = self.sales_list.curselection()
        file_name = self.sales_list.get(row_index,)
        print(file_name)
        self.bill_area.delete('1.0', END)
        with open(f'bills/{file_name}', 'r') as file:
            for i in file:
                self.bill_area.insert(END, i)

    def search(self):
        if self.invoice_var.get() == "":
            messagebox.showerror("Erreur", "Facture No. obligatoire!", parent=self.root)
        else:
            if self.invoice_var.get() in self.bills_list:
                with open(f'bills/{self.invoice_var.get()}.txt', 'r') as file:
                    self.bill_area.delete("1.0", END)
                    for i in file:
                        self.bill_area.insert(END, i)
            else:
                messagebox.showerror("Erreur", "Facture non existante!", parent=self.root)

    def clear(self):
        self.show()
        self.bill_area.delete("1.0", END)
        self.invoice_var.set("")

chemin_icone = "stock.ico"

def fonction_destination(autorisation, *params):
        if autorisation == 'True':
            # L'utilisateur est déjà connecté
            print("Utilisateur déjà connecté.")
            if __name__ == "__main__":
                root = Tk()
                system = Sales(root)
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
    messagebox.showwarning(fenetre, "Accès impossible \n Veuillez vous connecter en tant qu'administrateur pour effectuer cette opération pour accéder à la gestion des factures ")
    fenetre.destroy()
    os.system("python login.py")


