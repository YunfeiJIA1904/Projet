from game.play import*


def test_create_new_play():
    """
    Test de la fonction create_new_play
    """
    partie = create_new_play()
    assert partie["score"] == 3 and partie["plateau"]["tiles"] != [0 for i in range(16)]
    assert get_value(partie["plateau"], partie["next_tile"]["0"]["lig"], partie["next_tile"]["0"]["col"]) == partie["next_tile"]["0"]["val"] and get_value(partie["plateau"], partie["next_tile"]["1"]["lig"], partie["next_tile"]["1"]["col"]) == partie["next_tile"]["1"]["val"]
    print("test_create_new_play est ok !")
test_create_new_play()
