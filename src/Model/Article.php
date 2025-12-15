<?php
namespace src\Model;
use JsonSerializable;
class Article implements JsonSerializable {
    private ?int $Id = null;
    private ?string $Titre = null;
    private ?string $Auteur = null;
    private ?string $Description = null;
    private ?\DateTime $DatePublication = null;
    private ?string $ImageRepository = null;
    private ?string $ImageFileName = null;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function setId(?int $Id): Article
    {
        $this->Id = $Id;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(?string $Titre): Article
    {
        $this->Titre = $Titre;
        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->Auteur;
    }

    public function setAuteur(?string $Auteur): Article
    {
        $this->Auteur = $Auteur;
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): Article
    {
        $this->Description = $Description;
        return $this;
    }

    public function getDatePublication(): ?\DateTime
    {
        return $this->DatePublication;
    }

    public function setDatePublication(?\DateTime $DatePublication): Article
    {
        $this->DatePublication = $DatePublication;
        return $this;
    }

    public function getImageRepository(): ?string
    {
        return $this->ImageRepository;
    }

    public function setImageRepository(?string $ImageRepository): Article
    {
        $this->ImageRepository = $ImageRepository;
        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->ImageFileName;
    }

    public function setImageFileName(?string $ImageFileName): Article
    {
        $this->ImageFileName = $ImageFileName;
        return $this;
    }

    public static function SqlGetAll()
    {
        $bdd = BDD::getInstance();
        $req = $bdd->query('SELECT * FROM articles order by Id DESC ');
        $articles = $req->fetchAll(\PDO::FETCH_ASSOC);
        $arrayArticles = [];
        foreach ($articles as $article) {
            $articleObj = new Article();
            $articleObj->setId($article['Id']);
            $articleObj->setTitre($article['Titre']);
            $articleObj->setAuteur($article['Auteur']);
            $articleObj->setDescription($article['Description']);
            $articleObj->setDatePublication(new \DateTime($article['DatePublication']));
            $articleObj->setImageRepository($article['ImageRepository']);
            $articleObj->setImageFileName($article['ImageFileName']);
            $arrayArticles[] = $articleObj;
        }

        return $arrayArticles;
    }

    public static function SqlGetLast(int $nb)
    {
        $bdd = BDD::getInstance();
        $req = $bdd->prepare('SELECT * FROM articles order by Id DESC LIMIT :limit');
        $req->bindValue(':limit', $nb, \PDO::PARAM_INT);
        $req->execute();

        $articles = $req->fetchAll(\PDO::FETCH_ASSOC);
        $arrayArticles = [];
        foreach ($articles as $article) {
            //var_dump(json_encode($article));
            $articleObj = new Article();
            $articleObj->setId($article['Id']);
            $articleObj->setTitre($article['Titre']);
            $articleObj->setAuteur($article['Auteur']);
            $articleObj->setDescription($article['Description']);
            $articleObj->setDatePublication(new \DateTime($article['DatePublication']));
            $articleObj->setImageRepository($article['ImageRepository']);
            $articleObj->setImageFileName($article['ImageFileName']);
            $arrayArticles[] = $articleObj;
            var_dump(json_encode($articleObj));
        }

        return $arrayArticles;
    }


    public static function SqlAdd(Article $article) : int{
        try{
            $req = BDD::getInstance()->prepare("INSERT INTO articles (Titre,Description,DatePublication,Auteur,ImageRepository,ImageFileName)  VALUES (:Titre, :Description, :DatePublication, :Auteur, :ImageRepository, :ImageFileName)");
            $req->bindValue(':Titre', $article->getTitre());
            $req->bindValue(':Description', $article->getDescription());
            $req->bindValue(':DatePublication',$article->getDatePublication()?->format('Y-m-d'));
            $req->bindValue(':Auteur',$article->getAuteur());
            $req->bindValue(':ImageRepository',$article->getImageRepository());
            $req->bindValue(':ImageFileName',$article->getImageFileName());
            $req->execute();
            return BDD::getInstance()->lastInsertId();

        }catch (\Exception $e){
            var_dump($e->getMessage());
        }
    }

    public static function SqlGetById(int $id) : ?Article
    {
        $bdd = BDD::getInstance();
        $req = $bdd->prepare('SELECT * FROM articles WHERE Id = :Id ');
        $req->bindValue(':Id', $id);
        $req->execute();
        $articleSql = $req->fetch(\PDO::FETCH_ASSOC);
        if($articleSql != false){
            $articleObj = new Article();
            $articleObj->setId($articleSql['Id']);
            $articleObj->setTitre($articleSql['Titre']);
            $articleObj->setAuteur($articleSql['Auteur']);
            $articleObj->setDescription($articleSql['Description']);
            $articleObj->setDatePublication(new \DateTime($articleSql['DatePublication']));
            $articleObj->setImageRepository($articleSql['ImageRepository']);
            $articleObj->setImageFileName($articleSql['ImageFileName']);

            return $articleObj;
        }
        return null;
    }

    public static function SqlUpdate(Article $article) : ?Article
    {
        $bdd = BDD::getInstance();
        $req = $bdd->prepare('UPDATE articles set Titre=:Titre, Description=:Description, DatePublication=:DatePublication, Auteur=:Auteur, ImageRepository=:ImageRepository, ImageFileName=:ImageFileName  where Id=:Id');

        $req->bindValue(':Id', $article->getId());
        $req->bindValue(':Titre', $article->getTitre());
        $req->bindValue(':Description', $article->getDescription());
        $req->bindValue(':DatePublication', $article->getDatePublication()->format('Y-m-d'));
        $req->bindValue(':Auteur', $article->getAuteur());
        $req->bindValue(':ImageRepository', $article->getImageRepository());
        $req->bindValue(':ImageFileName', $article->getImageFileName());
        $req->execute();

        return $article;
    }

    public static function SqlDelete(int $Id) {
        $bdd = BDD::getInstance();
        $req = $bdd->prepare('DELETE from articles where Id=:Id');
        $req->bindValue(':Id', $Id);
        $req->execute();
    }

    public static function SqlSearch(string $keyword) : array
    {
        $requete = BDD::getInstance()->prepare('SELECT * FROM articles WHERE Titre LIKE :keyword OR Description LIKE :keyword ORDER BY Id DESC');
        $requete->bindValue(':keyword','%'.$keyword.'%');
        $requete->execute();
        $articlesSql = $requete->fetchAll(\PDO::FETCH_ASSOC);
        $articlesObjet = [];
        foreach ($articlesSql as $articleSql){
            $article = new Article();
            $article->setId($articleSql["Id"]);
            $article->setTitre($articleSql["Titre"]);
            $article->setDescription($articleSql["Description"]);
            $article->setDatePublication(new \DateTime($articleSql["DatePublication"]));
            $article->setAuteur($articleSql["Auteur"]);
            $article->setImageRepository($articleSql["ImageRepository"]);
            $article->setImageFileName($articleSql["ImageFileName"]);
            $articlesObjet[] = $article;
        }
        return $articlesObjet;
    }


    public function jsonSerialize() :mixed
    {
        return [
            'Id' => $this->getId(),
            'Titre' => $this->getTitre(),
            'Description' => $this->getDescription(),
            'DatePublication' => $this->getDatePublication()->format('Y-m-d'),
            'Auteur' => $this->getAuteur(),
            'ImageRepository' => $this->getImageRepository(),
            'ImageFileName' => $this->getImageFileName()
        ];
    }
}