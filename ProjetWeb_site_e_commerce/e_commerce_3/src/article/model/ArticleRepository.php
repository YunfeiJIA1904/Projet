<?php


interface ArticleRepository
{
    public function createArticle($nom, $image, $quantite, $prix, $categorie, $description) : bool;
    public function deleteArticle($id) : void;
    public function getIdArticle($nom, $prix, $categorie) : int;
    public function getArticleById($id) : ?Article;
    public function getAllCategories(): array;
    public function getAllArticles(): PDOStatement;
    public function getAllArticlesParametre($categorie='all',$trie='default') : PDOStatement;
}