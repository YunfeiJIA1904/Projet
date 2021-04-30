

# Fonctions de la partie 1

def check_indice(plateau,indice):

    """Retourne True si indice correspond a un indice valide de case pour le plateau (entre 0 et n-1)"""

    if 0 <= indice <= plateau["n"] - 1:
        return True
    return False



def check_room(plateau,lig,col):

    """Retourne True si (lig,col) est une case du plateau (lig et col sont des indices valide)"""

    if check_indice(plateau, lig) and check_indice(plateau, col):
        return True
    return False



def get_value(plateau,lig,col):

    """retourne la valeur de la case (lig,col).
    Erreur si (lig,col) n est pas valide"""

    if check_room(plateau,lig,col): # si plateau est valide
        return plateau["tiles"][lig * 4 + col] # retourne la valeur de lig*4+col eme position
    return "Erreur"



def set_value(plateau,lig,col,val):

    """affecte la valeur + verification validite + case libre diminue"""

    if get_value(plateau,lig,col)==0 and val != 0: # verifier si la case est vide et valeur inserer n est pas 0
        plateau["nombre_cases_libres"] -= 1 # cases libre -1
    if get_value(plateau,lig,col)!=0 and val == 0: # veerifier si la case n est pas vide et valeur inserer est 0
        plateau["nombre_cases_libres"] += 1 # cases libre +1
    if check_room(plateau,lig,col) and val >= 0: # verifier si plateau est valide et val inserer n est pas negative
        plateau["tiles"][lig * 4 + col] = val # remplace la valeur en lig*4+col eme position
    else:
        return "Erreur"



def is_room_empty(plateau,i,j):

    """Teste si une case du plateau est libre ou pas
    return True si la case est libre, False si non"""

    if get_value(plateau,i,j) != 0:
        return False
    return True
