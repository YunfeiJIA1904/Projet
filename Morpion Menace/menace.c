#include <stdio.h>
#include <stdint.h>
#include <assert.h>
#include <stdlib.h>
#include <time.h>
#include <string.h>
#include <math.h>

// init la taille des configuration 
// ici 627 est le nombre de config apres le filtrage
// cad en enlevant les cas impossible, gagant, similair etc
uint32_t taille_config=627;
// enregistrer les parties win et draw  pour faire un statistique à la fin
float AI_win = 0.0, AI_draw = 0.0, nb_game = 0.0; 
//-------------------------------------------------------------------
//-------------Partie gestion structure donnee-----------------------
//-------------------------------------------------------------------
typedef struct _bille
{
  uint8_t data;
  struct _bille *suivant;
  struct _bille *precedent;
}bille;

typedef struct
{
  uint32_t taille;
  bille *tete;
  bille *queue;
}box;

typedef struct
{
  box **tab;
  uint32_t taille;
}tab_box;


bille* new_bille(uint8_t d)
{
  bille *bi = malloc(sizeof(bille));
  bi->data = d;
  return bi;
}

box* new_box()
{
  box *bo = malloc(sizeof(box));
  bo->taille = 0;
  bo->tete = NULL;
  bo->queue = NULL;
  return bo;
}

tab_box* new_tab_box()
{
  uint32_t i;
  tab_box *tb = malloc(sizeof(tab_box));

  tb->tab = malloc(taille_config *sizeof(box));

  for(i = 0; i<taille_config; i++)
  {
    tb->tab[i] = new_box();
  }

  tb->taille = taille_config;
  return tb;
}

// trouver la bille(maillon) en fonction de la position donnee
bille *trouver_bille(box *bo, uint64_t position)
{
  bille *bi = bo->tete;
  uint64_t i;
  if (position >= bo->taille)
  {
    return NULL;
  }
  for (i = 0; i < position; i++)
  {
    bi = bi->suivant;
  }
  return bi;
}

//return data de la maillon
uint8_t trouver_data(box *bo, uint64_t position)
{
  bille *bi = trouver_bille(bo, position);
  if(bi == NULL)
  {
    printf("Erreur, position introuvable\n" );
  }
  return bi->data;
}

void add_queue_box(box *bo, uint8_t d)
{
  bille *bi = new_bille(d);
  bi->suivant = NULL;
  bi->precedent = bo->queue;

  if (bo->taille > 0 )
  {
    bo->queue->suivant = bi;
  }
  else
  {
    bo->tete = bi;
  }
  bo->queue = bi;
  bo->taille += 1;
}

uint8_t rem_tete(box *bo)
{
  bille *t = bo->tete;
  uint8_t r = t->data;

  bo->tete = bo->tete->suivant;
  free(t);
  bo->taille -= 1;
  if (bo->taille == 0)
  {
    bo->queue = NULL;
  }
  else
  {
    bo->tete->precedent = NULL;
  }
  return r;
}

uint8_t rem_queue(box *bo)
{
  bille *t = bo->queue;
  uint8_t r = t->data;

  bo->queue = bo->queue->precedent;
  free(t);
  bo->taille -= 1;
  if (bo->taille == 0)
  {
    bo->tete = NULL;
  }
  else
  {
    bo->queue->suivant = NULL;
  }
  return r;
}

uint8_t rem_position(box *bo, uint64_t position)
{
  bille *bi, *prec, *suiv;
  uint8_t d;
  // printf("j enleverai la bille position %ld\n", position);

  if (position >= bo->taille)
  {
    printf("Erreur dans fonction rem_position, position est >= taille\n");
    assert(0);
  }
  else if(position == 0)
  {
    return rem_tete(bo);
  }
  else if((position == bo->taille-1))
  {
    return rem_queue(bo);
  }
  else
  {
    bi = trouver_bille(bo, position);
    // printf("info de la bille est : %d\n", bi->data);
    prec = bi->precedent;
    suiv = bi->suivant;
    d = bi->data;
    prec->suivant = suiv;
    suiv->precedent = prec;
    free(bi);
    bo->taille -= 1;
    return d;
  }
}

void delete_box(box* bo)
{
  while (bo->taille != 0)
  {
    rem_tete(bo);
  }
  free(bo);
}

void delete_tab_box(tab_box* tb)
{
  uint32_t i;
  for (i = 0; i < tb->taille; i++)
  {
    delete_box(tb->tab[i]);
  }
  free(tb->tab);
  free(tb);
}

//ajouter une donnee dans un box precis d'un table hashage
void add_queue_tabbox(tab_box *tb, uint8_t d, uint64_t num_config)
{
  add_queue_box(tb->tab[num_config], d);
}

//enlever une bille(maillon) dans un box precis d'un table hashage
uint8_t rem_position_tabbox(tab_box *tb, uint64_t num_config, uint64_t indice_bille)
{
  uint8_t d;
  d = rem_position(tb->tab[num_config], indice_bille);
  return d;
}

//retourner une donnee dans un box precis d'un table hashage
uint8_t data_bille_tabbox(tab_box *tb,  uint64_t num_config, uint64_t indice_bille)
{
  uint8_t d;
  d = trouver_data(tb->tab[num_config], indice_bille);
  return d;
}

// permet de charger les 627 configurations en base10 dans un tableau
void load_b10Configs_to_tab(FILE *b10Config, uint32_t *b10ConfigTab)
{
  uint32_t e, i = 0, base10;
  e = fscanf(b10Config, "%d", &base10);
  while (e != EOF)
  {
    b10ConfigTab[i] = base10;
    e = fscanf(b10Config, "%d", &base10);
    i++;
  }
  fclose(b10Config);
}

/* initialiser tab_box(initialiser les bille dans chaque configuration en fonction de leur case vide
 en lisant un fichier de congif en base 3)*/
tab_box* init_tab_box(FILE *b3Config)
{
  tab_box *tb = new_tab_box();
  // d'abord on parcour le fichier b3Config pour trouver les 0 dans la grille et ajouter les billes correspondant
  char base3[10];
  uint32_t e, i, num_config = 0;
  e = fscanf(b3Config, "%s", base3);
  while (e != EOF)
  {
    // pour chaque string de base3 on cherche le 0 et ajouter les indcides des 0 dans les configs correspondant
    for (i = 0; i < strlen(base3); i++)
    {
      if (base3[i] == '0')
      {
        add_queue_tabbox(tb, i, num_config);
      }
    }
    e = fscanf(b3Config, "%s", base3);
    num_config++;
  }
  return tb;
}

//permet de mettre à jour les données
tab_box* maj_tab_box(tab_box *tb, uint8_t result, uint32_t* indice_bille_joue, uint32_t* indice_config_joue, uint8_t nb_coup_ai)
{
  uint32_t i;
  // si gagant
  if (result == 1)
  {
    // nb_coup_ai est le nombre de coup joué par AI dans une partie
    for ( i = 0; i < nb_coup_ai; i++)
    {
      //on donne une condition que les bille ne depasse 100 pour ne pas surcharger la box
      //on considere que 100 est suffisant
      if (tb->tab[indice_config_joue[i]]->taille <= 100)
      {
        // on ajoute 3 bille de dans le config correspondant
        add_queue_tabbox(tb, data_bille_tabbox(tb, indice_config_joue[i], indice_bille_joue[i]), indice_config_joue[i]);
        add_queue_tabbox(tb, data_bille_tabbox(tb, indice_config_joue[i], indice_bille_joue[i]), indice_config_joue[i]);
        add_queue_tabbox(tb, data_bille_tabbox(tb, indice_config_joue[i], indice_bille_joue[i]), indice_config_joue[i]);
      }
    }
  }

  // si nul
  else if (result == 2)
  {
    for ( i = 0; i < nb_coup_ai; i++)
    {
      if (tb->tab[indice_config_joue[i]]->taille <= 100)
      {
        // on ajoute 1 bille de dans le config correspondant
        add_queue_tabbox(tb, data_bille_tabbox(tb, indice_config_joue[i], indice_bille_joue[i]), indice_config_joue[i]);
      }
    }
  }

  //si perdant
  else if (result == 0)
  {
      for ( i = 0; i < nb_coup_ai; i++)
      {
          // enlever si box n'est pas vide pour éviter erreur lorsq'on tire une bille random dans un box vide
          if (tb->tab[indice_config_joue[i]]->taille > 1)
          {
            // on retire 1 bille de dans le config correspondant
            rem_position_tabbox(tb, indice_config_joue[i], indice_bille_joue[i]);
          }
      }
  }
  return tb;
}

// tirer une bille random en fonction de la taille de box correspondant
uint8_t random_bille(tab_box *tb, uint32_t num_config)
{
  uint8_t r;
  // if (tb->tab[num_config]->taille == 0)
  // {
  //   printf("config %d\n", num_config);
  // }

  r = rand()%(tb->tab[num_config]->taille);
  return r;
}



//-------------------------------------------------------------------
//-----------------------------partie gestion jeu--------------------
//-------------------------------------------------------------------

typedef enum transformation{ID, ROT_90, ROT_180, ROT_270, MIROIR_VERT, MIROIR_HORIZ} transformation;
//print le symbole correspondant aux numeros
char return_value(uint8_t value)
{
  switch(value)
  {
    case (0):
        return ' ';
    case (1):
        return 'x';
    case (2):
        return 'o';
    default:
        assert(0);
  }
}

//print le grille du jeu
void print_grille_2d(uint8_t grille[9])
{
  uint8_t i=0;
  while (i<9)
  {
    printf("|%c",return_value(grille[i]));
    if ((i+1)%3==0)
    {
      printf("|\n");
    }
    i++;
  }
}

//init grille
void init_grille(uint8_t grille[9])
{
  uint8_t i;
  for ( i = 0; i < 9; i++)
  {
    grille[i] = 0;
  }
}

//verifier si coup est valide, valide return 1 sinon 0
uint8_t move_valid(uint8_t pos, uint8_t grille[9])
{
  if(grille[pos]==0)
  {
    return 1;
  }
  return 0;
}

//demander de Saisir et  verifier si valide ou non
uint8_t ask_move(uint8_t grille[9])
{
  uint32_t res = 10, valid = 0;
  while ((res < 1 || res > 9) || (valid== 0))
  {
    printf("Veuillez saisir un coup valide (entre 1 et 9) : \n");
    scanf("%d", &res);
    valid = move_valid(res-1, grille);
  }
  return res-1;
}

//jouer un coup
void move(uint8_t Case, uint8_t signe, uint8_t grille[9])
{
  grille[Case]=signe;
}


//changement de signe
uint8_t change_signe(uint8_t signe)
{
  if (signe==1)
  {
    return 2;
  }
  return 1;
}

// // return 1 si gagner, return 2 si partie null, return 0 non ternimer
uint8_t end_game(uint8_t grille[9])
{

  //comparer par rapport case du milieu != 0
  //ici verifie les 2 diagonales
  if (grille[4]!=0 &&( (grille[0]==grille[4] && grille[4]==grille[8]) ||(grille[2]==grille[4] && grille[4]==grille[6]))) return 1;
  //verifier les lignes
  if (grille[0]!=0 && ((grille[0] == grille[1]) && (grille[1] == grille[2]))) return 1;
  if (grille[3]!=0 && ((grille[3] == grille[4]) && (grille[4] == grille[5]))) return 1;
  if (grille[7]!=0 && ((grille[6] == grille[7]) && (grille[7] == grille[8]))) return 1;

  //verifier les colonnes
  if (grille[3]!=0 && ((grille[0] == grille[3]) && (grille[3] == grille[6]))) return 1;
  if (grille[4]!=0 && ((grille[1] == grille[4]) && (grille[4] == grille[7]))) return 1;
  if (grille[5]!=0 && ((grille[2] == grille[5]) && (grille[5] == grille[8]))) return 1;

  int i=0;
  // verifier si parie est null avec casevide ==0
  int case_vide = 0;
  while (i<9)
  {
    if (grille[i]==0)
    {
      case_vide++;
    }
    i++;
  }
  if (case_vide==0)
  {
    return 2;
  }
  return 0;
}

//transformer grille en base 10
uint32_t grille_to_b10(uint8_t grille[9])
{
  uint32_t result =0, exp =0;
 
  // Calculer la base 10 avec grille
  for (int j = 8; j >=0; j--)
  {
    result += grille[j]%48 *pow(3, exp);
    exp++;
  }
  return result;
}

//transformer une base 10 en grille
void b10_to_grille(uint32_t b10, uint8_t* newgrille)
{
  uint8_t temp[9] = {0};
  uint32_t quotient = b10, i = 0, compt;
  //trouver le base3 et mettre dans temp[9] (ici tab est inversé)
  //ici on a utiliser le technique de modulation et trouve la base3 avec les reste de la modulation
  // exemple: base10 = 21, je fais 21/3 = 7, reste = 0 | 7/3 = 2, reste 1 | 2/3 = 0, reste 2 | fini
  // du coup on a  012(il est inversé) une fois inverser on aura 210 donc 21 en base3 est 210
  while (quotient!=0)
  {
    temp[i] = quotient%3;
    quotient=(quotient-quotient%3)/3;
    i++;
  }
  compt = 9;

  //remettre tab en bon ordre cad inverser le tab qu'on a récupéré précédemment
  for(int i = 0 ; i < 9; i++)
  {
    newgrille[i] = temp[compt-1];
    compt--;
  }
}

// appliquer la rotation sur la grille
void appliquer_transformation(uint8_t grille[9],transformation tr )
{
  uint8_t temp;
  switch (tr)
  {
  case(ID):
    break;

  case(ROT_90):
            temp = grille[0];
            grille[0]=grille[6];
            grille[6]=grille[8];
            grille[8]=grille[2];
            grille[2]=temp;

            temp = grille[1];
            grille[1] = grille[3];
            grille[3]=grille[7];
            grille[7]=grille[5];
            grille[5]=temp;
            break;

  case(ROT_180):
            appliquer_transformation(grille, ROT_90);
            appliquer_transformation(grille, ROT_90);
            break;

  case(ROT_270):
            appliquer_transformation(grille, ROT_90);
            appliquer_transformation(grille, ROT_90);
            appliquer_transformation(grille, ROT_90);
            break;

  case(MIROIR_VERT):
            temp = grille[0];
            grille[0] = grille[2];
            grille[2] = temp;

            temp = grille[3];
            grille[3] = grille[5];
            grille[5] = temp;

            temp = grille[6];
            grille[6] = grille[8];
            grille[8] = temp;
            break;

  case(MIROIR_HORIZ):
            appliquer_transformation(grille, ROT_90);
            appliquer_transformation(grille, MIROIR_VERT);
            appliquer_transformation(grille, ROT_270);
  }
}

// appliquer la rotation sur les billes : billes de base est {0,1,2,3,4,5,6,7,8}
void appliquer_transformation_billes(uint8_t billes[9], uint8_t num_rotation)
{
  switch (num_rotation)
  {
    case(0):
      break;

    case(1):
          appliquer_transformation(billes, ROT_90);
          break;

    case(2):
          appliquer_transformation(billes, ROT_180);
          break;

    case(3):
          appliquer_transformation(billes, ROT_270);
          break;

    case(4):
          appliquer_transformation(billes, MIROIR_VERT);
          break;

    case(5):
          appliquer_transformation(billes, MIROIR_VERT);
          appliquer_transformation(billes, ROT_90);
          break;

    case(6):
          appliquer_transformation(billes, MIROIR_VERT);
          appliquer_transformation(billes, ROT_180);
          break;

    case(7):
          appliquer_transformation(billes, MIROIR_VERT);
          appliquer_transformation(billes, ROT_270);
  }
}

// enregistrer les similaires d'un base10 dans un tableau
void tab_similaire(uint32_t base10, uint32_t tab[8])
{
  uint8_t g[9];
  init_grille(g);

  b10_to_grille(base10,g);

  tab[0]=base10;
  appliquer_transformation(g,ROT_90);
  tab[1]=grille_to_b10(g);

  appliquer_transformation(g,ROT_90);
  tab[2]=grille_to_b10(g);

  appliquer_transformation(g,ROT_90);
  tab[3]=grille_to_b10(g);

  appliquer_transformation(g,ROT_90);
  appliquer_transformation(g,MIROIR_VERT);
  tab[4]=grille_to_b10(g);


  appliquer_transformation(g,ROT_90);
  tab[5]=grille_to_b10(g);

  appliquer_transformation(g,ROT_90);
  tab[6]=grille_to_b10(g);

  appliquer_transformation(g,ROT_90);
  tab[7]=grille_to_b10(g);

  appliquer_transformation(g,ROT_90);
  appliquer_transformation(g, MIROIR_VERT);
}

//initialiser un tableau croissant de taille 9 : billes[9] = {0,1,2,3,4,5,6,7,8}
void init_tab_billes(uint8_t billes[9])
{
  for (uint8_t i = 0; i < 9; i++)
  {
    billes[i] = i;
  }
}

// chercher dans un tableau l'indice du nombre demandé
uint8_t find_indice(uint8_t nb, uint8_t tab[9])
{
  uint8_t result;
  for (uint8_t i = 0; i < 9; i++)
  {
    if (tab[i] == nb)
    {
      result = i;
    }
  }
  return result;
}


//----------------------------------------------------------------
//-------------------Partie gestion jeu---------------------------
//----------------------------------------------------------------

//Human vs AI
void human_ai(uint8_t grille[9], uint32_t *b10ConfigTab, tab_box *tb)
{
  uint8_t commencePar, res, signe = 1, result, pos, nb_coup_ai = 0, who_win, mark;
  uint8_t billes[9];
  uint32_t i, j, k,base10, avancer = 0, bille;
  uint32_t tab_simi[8], tab_simi_config[8], indice_bille_joue[5], indice_config_joue[5];

  //0 est joueur, 1 est robot
  commencePar = rand()%2;
  print_grille_2d(grille);

  //0 = pas fini, 1 = gagner, 2 = partie nul
  result = end_game(grille);
  while (result == 0)
  {
    // billes[9] = {0,1,2,3,4,5,6,7,8}
    init_tab_billes(billes);

    // coup joueur
    if (commencePar == 0 && result == 0)
    {
      printf("-----------------\n");
      printf("Votre tour\n");
      res = ask_move(grille);
      move(res, signe, grille);
      signe = change_signe(signe);
      print_grille_2d(grille);

      result = end_game(grille);
      if (result == 0) commencePar = 1;
      else if(result == 2) printf("Match nul!\n");
      else
      {
        who_win = 0;
        printf("Vous avez gagner!\n");
      }
    }

    else if (commencePar == 1 && result == 0)
    {
      //coup AI
      nb_coup_ai++;
      printf("-----------------\n");
      printf("Tour d'AI\n");
      base10 = grille_to_b10(grille);

      //obtenir les 8 similaire de grille actuelle
      tab_similaire(base10, tab_simi);

      //chercher dans les 627 configurations celui qui correspond
      for (i = 0; i<8; i++)
      {
        for (j = 0; j<taille_config; j++)
        {
          //si un des similaire dans le trableau est identique à un des 304 configuration
          if(tab_simi[i] == b10ConfigTab[j])
          {
            //on memorise l'indice de ce configuration dans le tableau: indice_config_joue
            indice_config_joue[avancer] = j;

            // on tire une bille random dans ce configue
            bille = random_bille(tb, j);

            //enregistrer la bille qu'on a retirer
            indice_bille_joue[avancer] = bille;
            
            //enregistrer les similaire de ce config de base 10
            tab_similaire(b10ConfigTab[j], tab_simi_config);
      
            /*on cherche à quelle position on retrouve la grille du jeu actuel par rapport 
            aux configuration de base(les 627)*/
            for (k = 0; k < 8; k++)
            {
              if (tab_simi_config[k] == base10)
              {
                mark = k;
                k = 8;
              }
            }

             /* EXPLIQUATION: on tourne aussi le tableau des bille car lorsqu'on tourne une grill, 
            les billes de 1-9 qui represente les position seront aussi different.
            exemple: |0|1|2|       |6|3|0|
                     |3|4|5| ----> |7|4|1|
                     |6|7|8|       |8|5|2|   
            */

            // en fonction de la position qu'on a trouve precedemment, on tourne le tableau des billes
            appliquer_transformation_billes(billes, mark);

            // on trouve l'indice de la bille retirer dans le tableau de billes apres rotation
            pos = find_indice(data_bille_tabbox(tb, j, bille), billes);
            move(pos, signe, grille);
            signe = change_signe(signe);
            print_grille_2d(grille);
            // stop les 2 boucle
            i = 8;
            j = taille_config;
          }
        }
      }
      avancer++;
      result = end_game(grille);
      if (result == 0) commencePar = 0;
      else if(result == 2) printf("Match nul!\n\n");
      else
      {
        who_win = 1;
        printf("AI vous a vaincu!\n\n");
      }
    }
  }
  // si nul
  if (result == 2)
  {
    // mise a jour le structure de donnee(gagne, perte ou nul)
    tb = maj_tab_box(tb, result, indice_bille_joue, indice_config_joue, nb_coup_ai);
  }
  // si gagant
  else if(who_win == 1 && result == 1)
  {
    tb = maj_tab_box(tb, result, indice_bille_joue, indice_config_joue, nb_coup_ai);
  }
  //si perdant(joueur win)
  else if(who_win == 0 && result == 1)
  {
    result = 0;
    tb = maj_tab_box(tb, result, indice_bille_joue, indice_config_joue, nb_coup_ai);
  }
}

//AI vs AI
void ai_ai(uint32_t fois, uint32_t *b10ConfigTab, tab_box *tb)
{
  uint8_t signe, result, pos, nb_coup_ai, nb_coup_ai2, mark;
  uint8_t billes[9], grille[9];
  uint32_t i, j, k, l, base10, avancer, avancer2, bille;
  uint32_t tab_simi[8], tab_simi_config[8], indice_bille_joue[5], indice_config_joue[5], indice_bille_joue2[5], indice_config_joue2[5];

  for (l = 0; l < fois; l++)
  {
    // printf("game %d\n", l);
    nb_coup_ai = 0;
    nb_coup_ai2 = 0;
    init_grille(grille);
    avancer = 0;
    avancer2 = 0;
    result = end_game(grille);
    signe = 1;
    // printf("signe  =  %d\n", signe);
    while (result == 0)
    {
      init_tab_billes(billes);
      if (signe == 1) nb_coup_ai++;
      else nb_coup_ai2++;

      base10 = grille_to_b10(grille);
      //obtenir les 8 similaire de grille actuelle
      tab_similaire(base10, tab_simi);

      //chercher dans les 627 configurations celui qui correspond
      for (i = 0; i<8; i++)
      {
        for (j = 0; j<taille_config; j++)
        {
          //si un des similaire dans le trableau est identique à un des 627 configuration
          if(tab_simi[i] == b10ConfigTab[j])
          {
            //on memorise son indice dans le tableau: indice_config_joue
            if (signe == 1) indice_config_joue[avancer] = j;
            else indice_config_joue2[avancer2] = j;

            // on tire une bille random dans ce configue
            bille = random_bille(tb, j);

            //enregistrer la bille qu'on a retirer
            if (signe == 1) indice_bille_joue[avancer] = bille;
            else indice_bille_joue2[avancer2] = bille;

            /* on tourne le tableau des bille aussi car lorsqu'on tourne une grill, les billes de 1-9 qui represente
            les position seront aussi different*/
            tab_similaire(b10ConfigTab[j], tab_simi_config);
            for (k = 0; k < 8; k++)
            {
              if (tab_simi_config[k] == base10)
              {
                mark = k;
                k = 8;
              }
            }

            appliquer_transformation_billes(billes, mark);

            // on trouve l'indice de la bille retirer dans le tableau de billes apres rotation
            pos = find_indice(data_bille_tabbox(tb, j, bille), billes);
            move(pos, signe, grille);
            // stop les 2 boucle
            i = 8;
            j = taille_config;
          }
        }
      }
      if (signe == 1) avancer++;
      else avancer2++;
      signe = change_signe(signe);
      result = end_game(grille);

    }
  
    // si nul
    if (result == 2)
    {
      AI_draw ++;
      // printf("partie null\n\n");
      // mise a jour le structure de donnee(gagne, perte ou nul)
      tb = maj_tab_box(tb, result, indice_bille_joue, indice_config_joue, nb_coup_ai);
      tb = maj_tab_box(tb, result, indice_bille_joue2, indice_config_joue2, nb_coup_ai2);
    }
    // si 1 a gagne
    else if(signe == 2 && result == 1)
    {
      AI_win ++;
      // printf("partie gagner robot 1\n\n");
      // ajoute pour 1 et enlever pour 2
      tb = maj_tab_box(tb, result, indice_bille_joue, indice_config_joue, nb_coup_ai);
      result = 0;
      tb = maj_tab_box(tb, result, indice_bille_joue2, indice_config_joue2, nb_coup_ai2);
    }
    //si 2 a gagne
    else if(signe == 1 && result == 1)
    {
      // printf("partie gagner robot 2\n\n");
      // ajoute pour 2 et enlever pour 1
      tb = maj_tab_box(tb, result, indice_bille_joue2, indice_config_joue2, nb_coup_ai2);
      result = 0;
      tb = maj_tab_box(tb, result, indice_bille_joue, indice_config_joue, nb_coup_ai);
    }
  }
}

//demander utilisateur si continue apres une game
uint8_t ask_continu()
{
  uint8_t res = 10;
  printf("Voulez-vous continuer?\n");
  while (res < 1 || res > 2)
  {
    printf("1: Continuer\n2: Quitter Vers Menu\n");
    scanf("%hhd", &res);
  }
  return res;
}

//entrainer l'AI
tab_box* play_entrainement(tab_box *tb, uint32_t *b10ConfigTab, uint64_t fois)
{
  //initialise grille de jeu
  uint8_t grille[9];
  init_grille(grille);
  //nb_game, AI_win et AI_draw sont des varaibles globale crées au début du programme.
  nb_game += fois;

  // lancer ai vs ai
  ai_ai(fois, b10ConfigTab, tb);
  printf("entrainement %ld fini\n\n", fois);
  printf("taux win : %f\ntaux draw : %f\n", AI_win/nb_game*100, AI_draw/nb_game*100);
  return tb;
}

//jouer
tab_box* play(tab_box *tb, uint32_t *b10ConfigTab)
{
  uint8_t continu;
  uint8_t grille[9];

  init_grille(grille);
  human_ai(grille, b10ConfigTab, tb);
  continu = ask_continu();
  while (continu == 1)
  {
    init_grille(grille);
    human_ai(grille, b10ConfigTab, tb);
    continu = ask_continu();
  }
  return tb;
}


//----------------------------------------------------------------
//-------------------Partie gestion donnee------------------------
//----------------------------------------------------------------

//enregistrer AI actuel
void save_AI(tab_box *tb)
{
  FILE *tab_bille =fopen("AI.txt","w");
  bille *bi;

  //parcour toutes les configurations
  for (int j = 0; j < tb->taille ; j++)
  {
    // bi prends la tete de la j configuration 
    bi = tb->tab[j]->tete;
    //parcour toutes les billes de cette configuration
    for (int i = 0; i < tb->tab[j]->taille; i++)
    {
      // ecrire dans AI.txt
      fprintf(tab_bille,"%i",bi->data);
      bi=bi->suivant;
    }
    //saut de ligne à la fin de chaque configuration
    fprintf(tab_bille,"\n");
  }
  free(bi);
  fclose(tab_bille);
}

//charger AI
void load_AI(tab_box *tb)
{
  FILE *load =fopen("AI.txt","r");
  // ici la taille varier en fonction de la capacité d'un box
  // qu'on a déclarer dans la fonction maj_tab_box
  char box[200];

  fscanf(load,"%s",box);
  // i est le numero de config ici taille_config == 627
  for (int i = 0; i < taille_config; i++)
  {
    // parcourir la ligne qu'on a lue dans AI.txt
    for (int j = 0; j < strlen(box); j++)
    {
      // on le transforme en int et l'ajouter dans chaque tab de notre tab hachage
      add_queue_tabbox(tb,box[j]%48,i);
    }
  
  fscanf(load,"%s",box);
  }
  fclose(load);
}

//Avoir tout les configuration d'un jeu morpion(cas impossible inclu)
uint8_t next_configuration(uint8_t grille[9])
{
  //EXPLICATION:
  // x--------  ==> o-------- ==> -x------- ==> xx------- ==> ox------- ==> -o------- ==> etc.
  // on incremente la 1er case et lorsqu'il depasse 3, on lui remet 0 et on incremente la case suivante
  grille[0]++;
  for (int i = 0; i < 9; i++)
  {
    // vérifier si l’incrémentation depasse 3, car les cas possibles sont seulement : 0, 1 et 2
    // avec 0 pour case vide, 1 pour x et 2 pour o.
    if (grille[i]==3)
    {
      // si ça depasse on remet à 0.
      grille[i]=0;

      // et si on n’est pas à la dernière case, on incrémente la case suivante, etc.
      if (i<8)
      {
        grille[i+1]++;
      }
      else return 1;
    }
  }
  return 0;
}

// verifier si la grille est valide: valide 1 sinon 0
uint8_t grille_valide(uint8_t grille[9])
{
  uint8_t nbx=0, nbo=0, res=0;

  //compter nombre de x et o
  for (int i = 0; i < 9; i++)
  {
    // 1 == x et 2 == o
    if (grille[i]==1) {nbx++;}
    if (grille[i]==2) {nbo++;}
  }

  //comme x commence tout le temps donc on verifie si leur diff est 0 ou 1
  if ((nbx == nbo || nbx==nbo+1 ))
  {
    res=1;
  }
  return res;
}

// retourne indice de minimun valeur dans un tab[8]
uint8_t min_tab(uint32_t tab[8])
{
  uint8_t min=0;
  for (int i = 0; i < 8; i++)
  {
    if (tab[min]>tab[i])
    {
      min=i;
    }
  }
  return min;
}

//creer le fichier b3Config
void create_b3Config()
{
  uint8_t g[9] = {0, 0, 0, 0, 0, 0, 0, 0, 0};

  FILE *out = fopen("b3Config.txt", "w");

  uint8_t mini;
  uint32_t similaire[8]={0,0,0,0,0,0,0,0};

  // 1er config qui represente 0 en base 3
  fprintf(out,"%i%i%i%i%i%i%i%i%i\n",0,0,0,0,0,0,0,0,0);
  printf("\n");

  while (next_configuration(g)==0 )
  {
    tab_similaire(grille_to_b10(g),similaire);

    // trouver l'indice de la val mini
    mini=min_tab(similaire);
    // si le config actuelle n'est pas le plus petit de tous les similaire j'ignore
    if (mini==0 )
    {
      // si grille valide et game non fini
      if (grille_valide(g)==1 && end_game(g) == 0)
      {
        // print dans le fichier .txt
        // commme on doit ecrire une base 3 donc on parcour tout simplement la grille
        for (int i = 0; i < 9; i++)
        {
          fprintf(out,"%i",g[i]);
        }
        fprintf(out,"\n");
      }
    }
  }
  fclose(out);
}

//creer le fichier b10Config
void create_b10Config()
{
  uint8_t g[9] = {0, 0, 0, 0, 0, 0, 0, 0, 0};

  FILE *out = fopen("b10config.txt", "w");

  uint8_t mini;
  uint32_t similaire[8]={0,0,0,0,0,0,0,0};

  fprintf(out,"%i\n",grille_to_b10(g));
  printf("\n");
  while (next_configuration(g)==0 )
  {

    tab_similaire(grille_to_b10(g),similaire);

      // trouver l'indice de la val mini
      mini=min_tab(similaire);
      // si le config actuelle n'est pas le plus petit de tous les similaire j'ignore
      if (mini==0 )
      {
        // si grille valide et game non fini
        if (grille_valide(g)==1 && end_game(g) == 0)
        {
          // print dans le fichier .txt
          fprintf(out,"%i\n",grille_to_b10(g));
        }
      }
  }
  fclose(out);
}


//Menu du jeu
void menu(tab_box *tb, uint32_t *b10ConfigTab)
{
  uint8_t res;
  uint64_t fois;
  printf("--------------------MENU-------------------\n");
  printf("1:Entrainer AI\n2:Jouer une partie contre AI\n3:Enregister AI\n4:Supprimer AI\n5:Quitter\n");
  printf("-------------------------------------------\n\n");
  scanf("%hhd", &res);
  while (res < 1 || res > 5)
  {
    printf("veuillez choisir une option valide!\n");
    scanf("%hhd", &res);
  }
  switch (res)
  {
  case (1):
    printf("---------Entrainement AI------------\n");
    printf("Combien de game voulez-Vous l'entrainer?(Ps: En fonction de la performance de votre ordinateur)\n");
    scanf("%ld", &fois);
    tb = play_entrainement(tb, b10ConfigTab, fois);
    menu(tb, b10ConfigTab);
    break;

  case (2):
    printf("---------Human vs AI-----------------\n");
    tb = play(tb, b10ConfigTab);
    menu(tb, b10ConfigTab);
    break;

  case (3):
    save_AI(tb);
    printf("----------AI saved----------------\n\n");
    menu(tb, b10ConfigTab);
    break;

  case (4):
    remove("AI.txt");
    printf("-----AI deleted pls restart game-----\n\n");
    delete_tab_box(tb);
    break;

  case (5):
    delete_tab_box(tb);
    break;
  }
}

int main(int argc, char *argv[])
{
  srand(time(NULL));

   create_b3Config();
   create_b10Config();

  FILE *b3Config = fopen("b3Config.txt", "r");
  FILE *b10Config = fopen("b10Config.txt", "r");

  // mettre les 627 configuration dans un tableau
  uint32_t *b10ConfigTab = malloc(taille_config*sizeof(uint32_t));
  load_b10Configs_to_tab(b10Config, b10ConfigTab);


  //jouer
  tab_box *tb;
  FILE *AI;
  // verifier si AI.txt (sauvegarde) existe
  if ((AI = fopen("AI.txt","r"))!= NULL)
  {
      // initialiser hash map , tb2 pour 2e AI
    tb = new_tab_box();
    fclose(AI);
    load_AI(tb);
  }
  else{
    // initialiser hash map , tb2 pour 2e AI
    tb = init_tab_box(b3Config);

  }

  //lancer le jeu avec structure tb charge
  menu(tb,b10ConfigTab);
  return 0;
}
