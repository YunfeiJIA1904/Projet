from tiles.tiles_acces import *


# Fonctions de la partie 1

def is_game_over(plateau):
    """
    verifier si le jeu est terminer (plus de 0)
    si oui retourne True si non False
    """
    if plateau["nombre_cases_libres"] == 0:  # voir si les cases libre == 0
        return True
    return False


def get_score(plateau):
    """
    return le score
    """
    somme = 0
    for i in range(0,plateau["n"]): # ligne de 0 a n
        for j in range(0,plateau["n"]): # colonne de 0 a n
            somme += get_value(plateau,i,j) #addition des valeurs obtenu
    return somme
