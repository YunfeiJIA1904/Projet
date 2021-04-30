from game.play import*

def test_init_play():
    assert init_play()=={'n': 4, 'nombre_cases_libres': 16, 'tiles': [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]}
    assert not init_play()=={'n': 3, 'nombre_cases_libres': 20, 'tiles': [0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0]}
    print('test_init_play ok')

test_init_play()
