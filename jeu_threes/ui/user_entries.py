# Fonctions de la partie 3

def get_user_move():
    """
    Saisi et retourne le coup joue par le joueur parmi les choix
    """
    choix_utilisateur=""
    # saisie controlee
    while not (choix_utilisateur.upper() == "B" or choix_utilisateur.upper() == "H" or choix_utilisateur.upper() == "D" or choix_utilisateur.upper() == "G" or choix_utilisateur.upper()=='M'):
        choix_utilisateur = input("Sasir une valeur: ")
    return choix_utilisateur




def get_user_menu(partie):
    """
    Saisi et retourne le choix  du joueur dans le menu principale
    """
    print('N: Commencer une nouvelle partie (New play) \nL: Charger une partie (Load save) \nS: Sauvegarder une partie en cours (Save game) \nC: Reprendre la partie en cours (Continu) \nQ: Terminer le jeu (Quit)')
    choix_utilisateur=""
    if partie ==None:
        while not (choix_utilisateur.upper() == "N" or choix_utilisateur.upper() == "L" or choix_utilisateur.upper()=='Q'): # pour none seulement 3 choix possible
            choix_utilisateur=input("Sasir une valeur :")
        return choix_utilisateur.upper()

    else:
        while not (choix_utilisateur.upper() == "N" or choix_utilisateur.upper() == "L" or choix_utilisateur.upper() == "S" or choix_utilisateur.upper() == "C" or choix_utilisateur.upper()=='Q'):
            choix_utilisateur=input("Sasir une valeur :")
        return choix_utilisateur.upper()
