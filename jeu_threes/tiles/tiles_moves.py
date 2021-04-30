from game.play import *
from tiles.game.play import *
from tiles.tiles_acces import *
from ui.play_display import *
from ui.user_entries import *


# Fonction de la Partie 1

def get_nb_empty_room(plateau):

    """Met a jour le dictionnaire plateau avec le nombre de case libre du plateau et renvoie le nombre de case libre"""

    nb_empty_room = 0
    for i in range(0,plateau["n"]): #linge de 0 a n
        for j in range(0,plateau["n"]): # colonne de 0 a n
            if is_room_empty(plateau,i,j):
                nb_empty_room += 1
    plateau["nombre_cases_libres"] = nb_empty_room # mise a jour du plateau
    return nb_empty_room


####################################################################################################
####################################################################################################

# Fonctions de la partie 2

def get_next_alea_tiles(plateau, mode):
    """
    permet de donner une valeur et sa position (position correspond une case vide) en mode init_play
    deux valeurs en mode encours
    """
    from random import randint
    # si le saisie et condition de plateau sont valide
    if mode.upper() == "INIT" and get_nb_empty_room(plateau) >= 2 or mode.upper() == "ENCOURS" and get_nb_empty_room(plateau) >= 1:
        if mode.upper() == "INIT":
            tableau = {'mode' : "init", 'check' :  not is_game_over(plateau), '0' : {'val' : 1, 'lig' : randint(0,3), 'col' : randint(0,3)}, '1' : {'val' : 2, 'lig' : randint(0,3), 'col' : randint(0,3)}}
            # quand les deux cases choisi ne sont pas vide et la position des 2 valeur donner sont egaux
            while not ( is_room_empty(plateau,tableau["0"]["lig"], tableau["0"]["col"]) and  is_room_empty(plateau, tableau["1"]["lig"], tableau["1"]["col"]) and  not(tableau["0"]["lig"] == tableau["1"]["lig"] and tableau["0"]["col"] == tableau["1"]["col"])):
                tableau["0"] = {'val' : 1, 'lig' : randint(0,3), 'col' : randint(0,3)}
                tableau["1"] = {'val' : 2, 'lig' : randint(0,3), 'col' : randint(0,3)}
        else:
            tableau = {'mode' : 'encours', 'check' : not is_game_over(plateau), '0' : {'val' : randint(1,3), 'lig' : randint(0,3), 'col' : randint(0,3)}}
            # auand la cases choisi n est pas vide
            while not (is_room_empty(plateau, tableau["0"]["lig"], tableau["0"]["col"])):
                tableau["0"] = {'val' : randint(1,3), 'lig' : randint(0,3), 'col' : randint(0,3)}
        return tableau
    else:
        return 'Erreur !'



def put_next_tiles(p,tiles):
    """
    permet de rentrer les valeurs donner par la fonction get_next_alea_tiles
    """
    if tiles['mode'].upper() == "INIT":
        set_value(p,tiles['0']['lig'],tiles['0']['col'],tiles['0']['val']) # mettre la vleur dans le plateau avec la position donnee de tiles
        set_value(p,tiles['1']['lig'],tiles['1']['col'],tiles['1']['val'])
    else:
        set_value(p,tiles['0']['lig'],tiles['0']['col'],tiles['0']['val'])



def line_pack(plateau, num_lig, debut, sens):
    """
    permet de tasser les tuiles d une ligne dans un sens donne
    """
    if check_room(plateau,num_lig,debut) == False or (sens != 1 and sens != 0):  # si plateau n est pas valide ou sens != 1 et 0
        return "Erreur !"
    if sens == 1:
        i = debut  # tasser a partie de i
        while i  <3:
            set_value(plateau, num_lig, i, get_value(plateau, num_lig, i+1)) # remplacer la valeur avec la valeur suivant
            i += 1
        set_value(plateau, num_lig, i, 0)
    else:
        i = debut
        while i > 0:
            set_value(plateau, num_lig, i, get_value(plateau, num_lig, i-1)) # remplacer la valeur avec la valeur precedente
            i -= 1
        set_value(plateau, num_lig, i, 0)



def column_pack(plateau, num_col, debut, sens):
    """
    permet de tasser les truiles d une colonne dans un sens donne
    """
    if check_room(plateau, num_col, debut) == False or (sens != 1 and sens != 0): # si plateau n est pas valide ou sens != 1 et 0
        return "Erreur !"
    if sens == 1:
        i = debut # tasser a partie de i
        while i < 3:
            set_value(plateau, i, num_col, get_value(plateau, i+1, num_col)) # remplacer la valeur avec la valeur suivant
            i += 1
        set_value(plateau, i, num_col, 0)
    else:
        i = debut
        while i > 0:
            set_value(plateau, i, num_col, get_value(plateau, i-1, num_col)) # remplacer la valeur avec la valeur precedente
            i -= 1
        set_value(plateau, i, num_col, 0)



def line_move(plateau, num_lig, sens):
    """
    permet de deplacer des tuiles d une ligne donnee dans un sens donne
    en appliquant les regles du jeu Threes
    """
    if check_room(plateau, num_lig, 3) == False or (sens != 1 and sens != 0): # si plateau n est pas valide ou sens != 1 et 0
        return "Erreur !"
    if sens==1:
        for i in range(0,3):
            if is_room_empty(plateau, num_lig, i): # si existe un 0  donc tasse a partir de 0
                line_pack(plateau, num_lig, i, sens)
                break
            # si val == val+1 et val % 3 donc val * 2 et tasse a partir de val+1
            if get_value(plateau, num_lig, i)==get_value(plateau, num_lig, i+1) and get_value(plateau, num_lig, i) % 3 == 0:
                set_value(plateau, num_lig, i, get_value(plateau, num_lig, i) * 2)
                line_pack(plateau, num_lig, i+1, sens)
                break
            # si val = 1 et val+1 = 2   ou l inverse : val + val+1 et tasse a partir de val+1
            if get_value(plateau, num_lig, i)==1 and get_value(plateau, num_lig, i+1) == 2:
                set_value(plateau, num_lig, i, 3)
                line_pack(plateau, num_lig, i+1, sens)
                break
            if get_value(plateau, num_lig, i) == 2 and get_value(plateau, num_lig, i+1) == 1:
                set_value(plateau, num_lig, i, 3)
                line_pack(plateau, num_lig, i+1, sens)
                break


    else:
        for i in range(3,0,-1):
            if is_room_empty(plateau, num_lig, i):
                line_pack(plateau, num_lig, i, sens)
                break
            if get_value(plateau, num_lig,i) == get_value(plateau, num_lig, i-1)and get_value(plateau, num_lig, i) % 3 == 0:
                set_value(plateau, num_lig, i, get_value(plateau, num_lig, i) * 2)
                line_pack(plateau, num_lig, i-1, sens)
                break
            if get_value(plateau, num_lig, i) == 1 and get_value(plateau, num_lig, i-1) == 2:
                set_value(plateau, num_lig, i, 3)
                line_pack(plateau, num_lig, i-1, sens)
                break
            if get_value(plateau, num_lig, i) == 2 and get_value(plateau, num_lig, i-1) == 1:
                set_value(plateau, num_lig, i, 3)
                line_pack(plateau, num_lig, i-1, sens)
                break



def column_move(plateau,num_col,sens):
    """
    permet de deplacer des tuiles d une colonne donnee dans un sens donne
    en appliquant les regles du jeu Threes
    """
    if check_room(plateau, 3, num_col) == False or (sens != 1 and sens != 0):
        return "Erreur !"
    if sens==1:
        for i in range(0,3):
            if is_room_empty(plateau,i,num_col):
                column_pack(plateau,num_col,i,sens)
                break
            if get_value(plateau,i,num_col)==get_value(plateau,i+1,num_col)and get_value(plateau,i,num_col)%3==0:
                set_value(plateau,i,num_col,get_value(plateau,i,num_col)*2)
                column_pack(plateau,num_col,i+1,sens)
                break
            if get_value(plateau,i,num_col)==1 and get_value(plateau,i+1,num_col)==2:
                set_value(plateau,i,num_col,3)
                column_pack(plateau,num_col,i+1,sens)
                break
            if get_value(plateau,i,num_col)==2 and get_value(plateau,i+1,num_col)==1:
                set_value(plateau,i,num_col,3)
                column_pack(plateau,num_col,i+1,sens)
                break

    else:
        for i in range(3,0,-1):
            if is_room_empty(plateau,i,num_col):
                column_pack(plateau,num_col,i,sens)
                break
            if get_value(plateau,i,num_col)==get_value(plateau,i-1,num_col) and get_value(plateau,i,num_col)%3==0:
                set_value(plateau,i,num_col,get_value(plateau,i,num_col)*2)
                column_pack(plateau,num_col,i-1,sens)
                break
            if get_value(plateau,i,num_col)==1 and get_value(plateau,i-1,num_col)==2:
                set_value(plateau,i,num_col,3)
                column_pack(plateau,num_col,i-1,sens)
                break
            if get_value(plateau,i,num_col)==2 and get_value(plateau,i-1,num_col)==1:
                set_value(plateau,i,num_col,3)
                column_pack(plateau,num_col,i-1,sens)
                break



def lines_move(plateau, sens):
    """
    permet de deplacer des tuiles de toutes les ligne du plateau dans un sens donne
    en appliquant les regles du jeu Threes
    """
    if sens != 0 and sens != 1:
        return "Erreur !"
    if sens == 1:
        for j in range(0, 4): # ligne de 0 a 4
            line_move(plateau, j ,sens)
    else:
        for j in range(0, 4): # ligne de 0 a 4
            line_move(plateau, j ,sens)


def columns_move(plateau, sens):
    """
    permet de deplacer des tuiles de toutes les colonnes du plateau dans un sens donne
    en appliquant les regles du jeu Threes
    """
    if sens != 0 and sens != 1:
        return "Erreur !"
    if sens == 1:
        for i in range(0, 4): # colonne de 0 a 4
            column_move(plateau, i ,sens)
    else:
        for i in range(0, 4): # colonne de 0 a 4
            column_move(plateau, i ,sens)



def play_move(plateau, sens):
    """
    permet de deplacer les tuiles du plateau dans un sens donne
    en appliquant les regles du jeu Threes
    """
    if not (sens.upper() == "B" or sens.upper() == "H" or sens.upper() == "D" or sens.upper() == "G"):
        return "Erreur !"
    # mouvement des colonne
    if sens.upper() == "B":
        columns_move(plateau, 0)

    elif sens.upper() == "H":
        columns_move(plateau, 1)

    # mouvement des lignes
    elif sens.upper() == "D":
        lines_move(plateau, 0)

    elif sens.upper() == "G":
        lines_move(plateau, 1)
