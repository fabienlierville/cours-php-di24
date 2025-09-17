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

}