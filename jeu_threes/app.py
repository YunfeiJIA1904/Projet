from game.play import *
from life_cycle.play import *
from tiles.game.play import *
from tiles.tiles_acces import *
from tiles.tiles_moves import *
from ui.play_display import *
from ui.user_entries import *



# Fonction de la partie 3


def threes():
    """
    affichage menu et jouer au jeu
    """
    partie=None        # aucune partie en cours
    choix=''
    while not choix=='Q':     # si le choix (vide au depart) n est pas de quitter le jeu
        choix=get_user_menu(partie)       #demande le choix
        if choix=='N':   #si N  commence une new partie de jeu
            partie=cycle_play(create_new_play())
        elif choix=='L':  #si L  reprendre une partie sauvegarder (si une partie deja sauvgarder, si non --> saisie controler grace get_user_menu)
            partie=cycle_play(restore_game())
        elif choix=='S': # si S  sauvegarder une partie (seulement si une partie est en cours, si non --> saisie controler grace get_user_menu)
            save_game(partie[1])
        elif choix=='C': # si C  reprendre la partie en cours (si une partie est en cours , si non --> saisie controler grace get_user_menu)
            cycle_play(partie[1])
