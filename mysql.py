import mysql.connector
from mysql.connector import Error

def create_connection(host_name, user_name, user_password, db_name):
    connection = None
    try:
        connection = mysql.connector.connect(
            host=host_name,
            user=user_name,
            passwd=user_password,
            database=db_name
        )
        print("Connexion à la base de données MySQL réussie")
    except Error as e:
        print(f"Erreur lors de la connexion à MySQL: {e}")

    return connection

# Remplacez les valeurs ci-dessous par vos informations de connexion
host_name = "votre_hote"
user_name = "votre_nom_utilisateur"
user_password = "votre_mot_de_passe"
db_name = "votre_nom_de_base_de_donnees"

connection = create_connection(host_name, user_name, user_password, db_name)