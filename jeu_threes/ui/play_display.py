
# Fonction de la partie 1

def full_display(plateau):
    """
    affichage en couleur
    """
    import sys
    from termcolor import colored, cprint
    ligne = ""
    ligne2 = ""
    b = colored(" ", 'red', "on_grey") # une espace gris
    print( b * 36) # bord du haut
    for i in range(0,len(plateau["tiles"])): # lire toutes les valeurs de tiles

        if plateau["tiles"][i] == 0:
            ligne2 += b + colored(" ".center(7), 'grey', 'on_white') + b  # une ligne blanche
            ligne += b + colored(" ".center(7), 'grey', 'on_white')+ b  # une ligne blanche

        elif plateau["tiles"][i] == 1 :
            ligne2 += b + colored(" ".center(7), 'white', 'on_blue') + b # une ligne bleu
            ligne += b + colored(str(plateau["tiles"][i]).center(7, " "), 'white', 'on_blue') + b # une ligne bleu avec 1 au centre

        elif plateau["tiles"][i] == 2:
            ligne2 += b + colored(" ".center(7), 'white', 'on_red') + b # une ligne rouge
            ligne += b + colored(str(plateau["tiles"][i]).center(7, " "), 'white', 'on_red') + b # une ligne rouge avec 2 au centre

        else:
            ligne2 += b + colored(" ".center(7), 'grey', 'on_white') + b # une ligne blanche
            ligne += b + colored(str(plateau["tiles"][i]).center(7, " "), 'grey', 'on_white') + b # une ligne blanche avec une chiffre(sauf 1 et 2)au centre

        if (i+1) %4 == 0: # si on a 4 cases de la ligne sont affichee
            print(ligne2)
            print(ligne2)
            print(ligne)
            print(ligne2)
            print(b * 36)  # bord du bas
            # reinitialisation
            ligne2 = ""
            ligne = ""
