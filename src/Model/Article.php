<?php
namespace src\Model;

class Article{
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
}