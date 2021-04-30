from game.play import *
from tiles.game.play import *
from tiles.tiles_acces import *
from tiles.tiles_moves import *
from ui.play_display import *
from ui.user_entries import *


#  Fonctions de la partie 3

def cycle_play(partie):

    """
    jouer a Three (affichage, mouvement, menu)
    """
    get_nb_empty_room(partie["plateau"]) # mise a jour de nombre de cases libre
    if partie is None:  # si on a pas commencer une partie
        print("Aucune partie en cours")
    while not is_game_over(partie['plateau']):  # quand le jeu n est pas termine on execute
        next_value=get_next_alea_tiles(partie['plateau'],'encours')  # demande une prochaine valeur
        full_display(partie["plateau"])  # affiche le plateau
        partie["score"]=get_score(partie["plateau"]) # demande le score
        print("Score :",partie["score"])
        print("Prochaine tuiles :",next_value['0']['val'])
        choix=get_user_move()  # demande le choix d utilisateur
        if choix.upper()=='M':
            return False,partie   # si M retourne False et partie actuelle
        else: # si non jeu continue
            put_next_tiles(partie["plateau"],next_value)   # rentre la nouvelle valeur dans le plateau
            play_move(partie["plateau"],choix)  # demande le mouvemant
        if is_game_over(partie["plateau"]):  # si jeu est terminer return True
            return True


def save_game(partie):
    import json
    """
    Sauvegarde une partie de threes
    """
    if partie is None or type(partie) is dict:  #si partie non commencer ou n est pas dict
        fiche = open("saved_game.json", 'w') #ouvrire fiche
        fiche.write(json.dumps(partie))  # ecrire
        fiche.close()  #fermer
        print("Partie est sauvegardee") # print info
    else:
        "Erreur !"  #si non Erreur




def restore_game():
    import json
    """
    permet de reprendre une partie sauvegarder
    """
    fiche = open('saved_game.json')  # ouvrire le fiche
    if fiche.read() == "null":  # si fiche vide
        return create_new_play()  # commence une new partie
    else:
        fiche.seek(0)  # dirige vers la 1st ligne
        return json.load(fiche) #lire la fiche
