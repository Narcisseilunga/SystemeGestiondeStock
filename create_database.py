import sqlite3

def create_db():
    con = sqlite3.connect(r'system.db')
    cur = con.cursor()

    # Création des tables
    cur.execute("CREATE TABLE IF NOT EXISTS employee(id INTEGER PRIMARY KEY AUTOINCREMENT, name text, email text, gender text, contact text, dob text, doj text, password text, type text, address text, salary text)")
    con.commit()

    cur.execute("CREATE TABLE IF NOT EXISTS supplier(invoice INTEGER PRIMARY KEY AUTOINCREMENT, name text, contact text, desc text)")
    con.commit()

    cur.execute("CREATE TABLE IF NOT EXISTS category(id INTEGER PRIMARY KEY AUTOINCREMENT, name text)")
    con.commit()

    cur.execute("CREATE TABLE IF NOT EXISTS product(id INTEGER PRIMARY KEY AUTOINCREMENT, category text, supplier text, name text, price text, qty text, status text, image_prod text)")
    con.commit()

    # Ajout de la nouvelle colonne image_prod à la table product
    """try:
        cur.execute("ALTER TABLE product ADD COLUMN image_prod TEXT")
        con.commit()
    except sqlite3.Error as e:
        print(f"Erreur lors de l'ajout de la colonne : {e}")"""

    cur.execute("CREATE TABLE IF NOT EXISTS sales(invoice INTEGER PRIMARY KEY AUTOINCREMENT, cl_name text, cl_contact text, date text)")
    con.commit()

    cur.execute("CREATE TABLE IF NOT EXISTS line_sale(invoice INTEGER, product_id INTEGER, price text, qty text, PRIMARY KEY (invoice, product_id), FOREIGN KEY (invoice) REFERENCES sales(invoice), FOREIGN KEY (product_id) REFERENCES product(id))")
    con.commit()

    cur.execute("CREATE TABLE IF NOT EXISTS message(id INTEGER PRIMARY KEY AUTOINCREMENT, name text, obj text,temp text)")
    con.commit()

    cur.execute("CREATE TABLE IF NOT EXISTS commandes(id INTEGER PRIMARY KEY AUTOINCREMENT,nom_produit text, prix text, quantite text, nom_client text, adresse_client text, numero_client text, temp text)")
    con.commit()

    cur.execute("CREATE TABLE IF NOT EXISTS compte(id INTEGER PRIMARY KEY AUTOINCREMENT,nom text,postnom text, prenom text, email text, contact text, adresse text, numero text, temp text)")
    con.commit()
    con.close()

# Uncomment the following lines to create the database and print the product table
create_db()

conn = sqlite3.connect(r'system.db')
cur = conn.cursor()
# Obtenir les informations sur les colonnes de la table product  {PRAGMA table_info(product)}
cur.execute("SELECT * FROM employee")
columns = cur.fetchall()

# Afficher les informations sur les colonnes
for column in columns:
    print(column)

conn.close()