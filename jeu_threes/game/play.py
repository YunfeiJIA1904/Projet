
from tiles.game.play import *
from tiles.tiles_acces import *
from tiles.tiles_moves import *
from ui.play_display import *
from ui.user_entries import *


# Fonction de la partie 1

def init_play():

    """
    Initialisation de la partie
    """

    plateau = {}
    plateau["n"] = 4
    plateau["nombre_cases_libres"] = plateau["n"]**2
    plateau["tiles"] = [0 for i in range(0,plateau["n"]**2)]  # mettre 0  de  0  a n**2
    return plateau

####################################################################################################
####################################################################################################

# Fonction de la partie 3

def create_new_play():

    """
    cree et retourne une nouvelle partie
    """
    partie = {}
    partie["plateau"] = init_play()      # cree un plateau de depart
    partie["next_tile"] = get_next_alea_tiles(partie["plateau"],"init")  # prendre 2 valeurs avec 2 positions diffetents (mode init)
    put_next_tiles(partie["plateau"],partie["next_tile"])  # mettre les 2 valeurs dans le plateau
    partie["score"] = get_score(partie["plateau"]) # mettre le score (somme de tous les tuiles )
    return partie
