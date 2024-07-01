import sys
def fonction_destination(autorisation, *params):
    if autorisation == 'True':
        # L'utilisateur est déjà connecté
        print("Utilisateur déjà connecté.")
    else:
        # L'utilisateur doit se reconnecter
        print("Veuillez vous reconnecter.")


# Vérification des arguments en ligne de commande
if len(sys.argv) >= 2:
    # Appel de la fonction de destination avec les arguments fournis
    fonction_destination(sys.argv[1], *sys.argv[:])
else:

    print("Veuillez vous connecter pour acceder au tableau de bord d'administrateur")