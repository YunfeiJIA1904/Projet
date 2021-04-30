from tiles.tiles_acces import *
from tiles.tiles_moves import *



def test_check_indice():
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    assert check_indice(plateau,2)
    assert check_indice(plateau,3)
    assert check_indice(plateau,1)
    assert not check_indice(plateau,-1)
    assert not check_indice(plateau,10)
    print('test_check_indice ok')
test_check_indice()

def test_get_value():
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    assert get_value(plateau,0,1)==12
    assert get_value(plateau,1,2)==6
    assert not get_value(plateau,3,2)==1
    assert not get_value(plateau,2,2)==6
    print('test_get_value ok')

test_get_value()


def test_set_value():
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    set_value(plateau,1,1,4)
    assert plateau=={'n': 4, 'nombre_cases_libres': 10, 'tiles': [0, 12, 0, 1, 0, 4, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    set_value(plateau,3,3,3)
    assert plateau=={'n': 4, 'nombre_cases_libres': 10, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 3]}
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    set_value(plateau,0,0,2)
    assert not plateau=={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    set_value(plateau,0,1,4)
    assert not plateau=={'n': 4, 'nombre_cases_libres': 15, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    print('test_set_value ok')


test_set_value()


def test_is_room_empty():
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 3, 0, 12, 0, 0, 3, 0, 0, 6, 0, 0, 1, 0, 0, 0]}
    assert is_room_empty(plateau,0,0)
    assert is_room_empty(plateau,3,3)
    assert not is_room_empty(plateau,0,1)
    assert not is_room_empty(plateau,2,1)
    print('test_is_room_empty ok')

test_is_room_empty()


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
