from life_cycle.play import *

def test_save_and_restore_game():
    partie={'plateau': {'n': 4, 'nombre_cases_libres': 14, 'tiles': [0, 0, 0, 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]}, 'next_tile': {'mode': 'init', 'check': True, '0': {'val': 1, 'lig': 0, 'col': 3}, '1': {'val': 2, 'lig': 1, 'col': 1}}, 'score': 3}
    save_game(partie)
    assert partie==restore_game()
    print('test_save_and_restore_game est ok')

test_save_and_restore_game()
