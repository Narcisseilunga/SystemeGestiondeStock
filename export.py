import sqlite3

# Chemin vers votre base de données
db_path = 'C:/Users/EMM/Documents/Application-de-Gestion-de-Stock/Stock_Management_System/system.db'
backup_path = 'backup_base_de_donnees.sql'

# Connexion à la base de données
conn = sqlite3.connect(db_path)
with open(backup_path, 'w') as f:
    for line in conn.iterdump():
        f.write('%s\n' % line)

# Fermeture de la connexion
conn.close()
