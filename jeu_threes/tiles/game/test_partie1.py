from tiles.game.play import *


def test_is_game_over():
    plateau={'n': 4, 'nombre_cases_libres': 0, 'tiles': [1, 3, 3, 6, 6, 6, 12, 12, 12, 12, 12, 24, 24, 24, 24, 12]}
    assert is_game_over(plateau)
    plateau={'n': 4, 'nombre_cases_libres': 1, 'tiles': [1, 3, 3, 6, 6, 6, 12, 0, 12, 12, 48, 24, 24, 24, 24, 12]}
    assert not is_game_over(plateau)
    print('test_is_game_over ok')


test_is_game_over()


def test_get_score():
    plateau={'n': 4, 'nombre_cases_libres': 11, 'tiles': [0, 12, 0, 1, 0, 0, 6, 0, 0, 2, 0, 3, 0, 0, 0, 0]}
    assert get_score(plateau)==24
    assert not get_score(plateau)==14
    print('test_get_score ok')

test_get_score()
