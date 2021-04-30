from tiles.tiles_acces import *
from tiles.tiles_moves import *




def test_get_next_alea_tiles():
    p ={'n': 4, 'nombre_cases_libres': 16, 'tiles': [1, 1, 1, 1,
                                                     1, 1, 1, 1,
                                                     1, 1, 1, 1,
                                                     1, 1, 0, 0]}
    #pour le mode init
    tiles = get_next_alea_tiles(p,"init")
    assert get_nb_empty_room(p) >= 2 and (get_value(p,tiles["0"]["lig"],tiles["0"]["col"]) == 0 and get_value(p,tiles["1"]["lig"],tiles["1"]["col"]) == 0) and (tiles["0"]["val"] == 1 or tiles["0"]["val"] == 2 ) and ((tiles["1"]["val"] == 1 or tiles["1"]["val"] == 2))

    # pour le mode encours
    tiles = get_next_alea_tiles(p,"encours")
    assert get_nb_empty_room(p) >= 1 and get_value(p,tiles["0"]["lig"],tiles["0"]["col"]) == 0  and (tiles["0"]["val"] == 1 or tiles["0"]["val"] == 2 or tiles["0"]["val"] == 3)

    # pour les valeur incoherent
    assert get_next_alea_tiles(p,"xxx") == "Erreur !"
    print("test_get_next_alea_tiles est ok!")
test_get_next_alea_tiles()


def test_put_next_tiles():
    p = {'n': 4, 'nombre_cases_libres': 16, 'tiles': [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]}

    # test pour le mode init
    tiles = get_next_alea_tiles(p,"init")
    put_next_tiles(p,tiles)
    assert get_value(p,tiles["0"]["lig"],tiles["0"]["col"]) == tiles["0"]["val"] and get_value(p,tiles["1"]["lig"],tiles["1"]["col"]) == tiles["1"]["val"]

    # test pour le mode encours
    tiles = get_next_alea_tiles(p,"encours")
    put_next_tiles(p,tiles)
    assert get_value(p,tiles["0"]["lig"],tiles["0"]["col"]) == tiles["0"]["val"]
    print("test_put_next_tiles est ok")
test_put_next_tiles()


def test_line_pack():
    p = {'n': 4, 'nombre_cases_libres': 1, 'tiles': [0, 1, 2, 3,
                                                     4, 5, 6, 7,
                                                     8, 9, 10, 11,
                                                     12, 13, 14, 15]}

    #pour droite
    line_pack(p,1,1,0)
    assert p == {'n': 4, 'nombre_cases_libres': 2, 'tiles': [0, 1, 2, 3,
                                                              0, 4, 6, 7,
                                                              8, 9, 10, 11,
                                                             12, 13, 14, 15]}
    line_pack(p,2,3,0)
    assert p == {'n': 4, 'nombre_cases_libres': 3, 'tiles': [0,  1, 2, 3,
                                                              0,  4, 6, 7,
                                                              0,  8, 9, 10,
                                                             12, 13, 14, 15]}

    #pour gauche
    line_pack(p,1,1,1)
    assert p == {'n': 4, 'nombre_cases_libres': 4, 'tiles': [0, 1, 2, 3,
                                                             0,   6, 7, 0,
                                                             0,   8, 9, 10,
                                                             12, 13, 14, 15]}
    line_pack(p,2,3,1)
    assert p == {'n': 4, 'nombre_cases_libres': 5, 'tiles': [0, 1, 2,   3,
                                                             0,  6,  7,   0,
                                                             0,  8,   9,  0,
                                                             12, 13, 14, 15]}

    # pour les valeur incoherent
    assert line_pack(p,4,3,1) and line_pack(p,-4,-3,0) == "Erreur !"
    assert line_pack(p,0,0,4) and line_pack(p,0,0,-4) == "Erreur !"
    print("test_line_pack est ok!")
test_line_pack()



def test_column_pack():
    p = {'n': 4, 'nombre_cases_libres': 1, 'tiles': [0, 1, 2, 3,
                                                     4, 5, 6, 7,
                                                     8, 9, 10, 11,
                                                     12, 13, 14, 15]}

    #pour bas
    column_pack(p,1,1,0)
    assert p == {'n': 4, 'nombre_cases_libres': 2, 'tiles': [0, 0, 2, 3,
                                                             4, 1, 6, 7,
                                                             8, 9, 10, 11,
                                                             12, 13, 14, 15]}
    column_pack(p,2,3,0)
    assert p == {'n': 4, 'nombre_cases_libres': 3, 'tiles': [0, 0, 0, 3,
                                                             4, 1, 2, 7,
                                                             8, 9, 6, 11,
                                                             12, 13, 10, 15]}

    # pour haut
    column_pack(p,1,1,1)
    assert p == {'n': 4, 'nombre_cases_libres': 4, 'tiles': [0, 0, 0, 3,
                                                             4, 9, 2, 7,
                                                             8, 13, 6, 11,
                                                             12, 0, 10, 15]}
    column_pack(p,2,3,1)
    assert p == {'n': 4, 'nombre_cases_libres': 5, 'tiles': [0, 0, 0, 3,
                                                             4, 9, 2, 7,
                                                             8, 13, 6, 11,
                                                             12, 0, 0, 15]}

    # pour les valeur incoherent
    assert column_pack(p,4,3,1) and column_pack(p,-4,-3,0) == "Erreur !"
    assert column_pack(p,0,0,4) and column_pack(p,0,0,-4) == "Erreur !"
    print("test_column_pack est ok!")
test_column_pack()



def test_line_move():
    p = {'n': 4, 'nombre_cases_libres': 8, 'tiles': [0, 1, 0, 3,
                                                     0, 5, 0, 7,
                                                     0, 9, 0, 11,
                                                     0, 13, 0, 15]}

    #pour droite
    line_move(p,1,0)
    assert p == {'n': 4, 'nombre_cases_libres': 8, 'tiles': [0, 1, 0, 3,
                                                             0, 0, 5, 7,
                                                             0, 9, 0, 11,
                                                             0, 13, 0, 15]}
    line_move(p,3,0)
    assert p == {'n': 4, 'nombre_cases_libres': 8, 'tiles': [0, 1, 0, 3,
                                                             0, 0, 5, 7,
                                                             0, 9, 0, 11,
                                                             0, 0, 13, 15]}

    #pour gauche
    line_move(p,1,1)
    assert p == {'n': 4, 'nombre_cases_libres': 8, 'tiles': [0, 1, 0, 3,
                                                             0, 5, 7, 0,
                                                             0, 9, 0, 11,
                                                             0, 0, 13, 15]}
    line_move(p,3,1)
    assert p == {'n': 4, 'nombre_cases_libres': 8, 'tiles': [0, 1, 0, 3,
                                                             0, 5, 7, 0,
                                                             0, 9, 0, 11,
                                                             0, 13, 15, 0]}

    # pour les valeur incoherent
    assert line_move(p,4,1) and line_move(p,-4,0) == "Erreur !"
    assert line_move(p,0,4) and line_move(p,0,-4) == "Erreur !"
    print("test_line_move est ok!")
test_line_move()

def test_column_move():
    p = {'n': 4, 'nombre_cases_libres': 6, 'tiles': [0, 1, 0, 3,
                                                     4, 0, 3, 2,
                                                     9, 0, 0, 11,
                                                     0, 13, 2, 15]}

    #pour bas
    column_move(p,1,0)
    assert p == {'n': 4, 'nombre_cases_libres': 6, 'tiles': [0, 0, 0, 3,
                                                             4, 1, 3, 2,
                                                             9, 0, 0, 11,
                                                             0, 13, 2, 15]}
    column_move(p,2,0)
    assert p == {'n': 4, 'nombre_cases_libres': 6, 'tiles': [0, 0, 0, 3,
                                                             4, 1, 0, 2,
                                                             9, 0, 3, 11,
                                                             0, 13, 2, 15]}

    # pour haut
    column_move(p,1,1)
    assert p == {'n': 4, 'nombre_cases_libres': 6, 'tiles': [0, 1, 0, 3,
                                                             4, 0, 0, 2,
                                                             9, 13, 3, 11,
                                                             0, 0, 2, 15]}
    column_move(p,2,1)
    assert p == {'n': 4, 'nombre_cases_libres': 6, 'tiles': [0, 1, 0, 3,
                                                             4, 0, 3, 2,
                                                             9, 13, 2, 11,
                                                             0, 0, 0, 15]}

    # pour les valeur incoherent
    assert column_move(p,4,1) and column_move(p,-4,0) == "Erreur !"
    assert column_move(p,0,4) and column_move(p,0,-4) == "Erreur !"
    print("test_colum_move est ok!")
test_column_move()

def test_lines_move():
    p = {'n': 4, 'nombre_cases_libres': 8, 'tiles': [0, 1, 0, 3,
                                                     0, 5, 0, 7,
                                                     0, 9, 0, 11,
                                                     0, 13, 0, 15]}

    # pour droite
    lines_move(p,0)
    assert p == {'n': 4, 'nombre_cases_libres': 8, 'tiles': [0, 0, 1, 3,
                                                              0, 0, 5, 7,
                                                              0, 0, 9, 11,
                                                             0, 0, 13, 15]}

    # pour gauche
    lines_move(p,1)
    assert p == {'n': 4, 'nombre_cases_libres': 8, 'tiles': [0, 1, 3, 0,
                                                              0, 5, 7, 0,
                                                             0, 9, 11, 0,
                                                             0, 13, 15, 0]}

    # pour les valeur incoherent
    assert lines_move(p,4) == "Erreur !" and lines_move(p,-4) == "Erreur !"
    print("test_lines_move est ok!")
test_lines_move()

def test_columns_move():
    p = {'n': 4, 'nombre_cases_libres': 6, 'tiles': [0, 1, 0, 3,
                                                     4, 0, 3, 2,
                                                     9, 0, 0, 11,
                                                     0, 13, 2, 15]}

    # pour bas
    columns_move(p,0)
    assert p == {'n': 4, 'nombre_cases_libres': 6, 'tiles': [0, 0, 0, 3,
                                                             0, 1, 0, 2,
                                                             4, 0, 3, 11,
                                                             9, 13, 2, 15]}

    # pour haut
    columns_move(p,1)
    assert p == {'n': 4, 'nombre_cases_libres': 6, 'tiles': [0, 1, 0, 3,
                                                             4, 0, 3, 2,
                                                             9, 13, 2, 11,
                                                             0, 0, 0, 15]}

    # pour les valeur incoherent
    assert columns_move(p,4) == "Erreur !" and columns_move(p,-4) == "Erreur !"
    print("test_columns_move est ok!")
test_columns_move()


def test_play_move():
    p = {'n': 4, 'nombre_cases_libres': 12, 'tiles': [0, 0, 0, 0,
                                                     0, 2, 3, 0,
                                                     0, 1, 6, 0,
                                                    0, 0, 0, 0]}


    # pour bas gauche haut droite
    play_move(p,"b")
    assert p == {'n': 4, 'nombre_cases_libres': 12, 'tiles': [0, 0, 0, 0,
                                                             0, 0, 0, 0,
                                                             0, 2, 3, 0,
                                                             0, 1, 6, 0]}

    play_move(p,"g")
    assert p == {'n': 4, 'nombre_cases_libres': 12, 'tiles': [0, 0, 0, 0,
                                                             0, 0, 0, 0,
                                                             2, 3, 0, 0,
                                                             1, 6, 0, 0]}

    play_move(p,"h")
    assert p == {'n': 4, 'nombre_cases_libres': 12, 'tiles': [0, 0, 0, 0,
                                                             2, 3, 0, 0,
                                                             1, 6, 0, 0,
                                                             0, 0, 0, 0]}

    play_move(p,"d")
    assert p == {'n': 4, 'nombre_cases_libres': 12, 'tiles': [0, 0, 0, 0,
                                                             0, 2, 3, 0,
                                                             0, 1, 6, 0,
                                                             0, 0, 0, 0]}

    # pour les valeur incoherent
    assert play_move(p,"x") == "Erreur !"
    print("test_play_move est ok")


test_play_move()
