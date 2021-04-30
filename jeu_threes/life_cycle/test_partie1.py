from life_cycle.play import *

def test_get_nb_empty_room():
    plateau={'n': 4, 'nombre_cases_libres': 16, 'tiles': [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]}
    assert get_nb_empty_room(plateau)==16
    plateau={'n': 4, 'nombre_cases_libres': 1, 'tiles': [1, 3, 3, 6, 6, 6, 12, 12, 12, 12, 12, 24, 24, 24, 24, 0]}
    assert get_nb_empty_room(plateau)==1
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 3, 0, 12, 0, 0, 3, 0, 0, 6, 0, 0, 1, 0, 0, 0]}
    assert not get_nb_empty_room(plateau)==4
    assert not get_nb_empty_room(plateau)==12
    print('test_get_nb_empty_room ok')


test_get_nb_empty_room()
