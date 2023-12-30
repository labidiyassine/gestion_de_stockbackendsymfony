<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeArticle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $designation;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $prixUnitaireHt;
    
    /**
     * @ORM\Column(name="tauxtva", type="decimal", precision=5, scale=2)
     */
    private $tauxTva;

    /**
     * @ORM\Column(name="prixunitairettc", type="decimal", precision=10, scale=2)
     */
    private $prixUnitaireTtc;

    /**
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommandeClients::class, mappedBy="article")
     */
    private $ligneCommandeClients;

    /**
     * @ORM\OneToMany(targetEntity=LigneVente::class, mappedBy="article")
     */
    private $ligneVentes;

    /**
     * @ORM\OneToMany(targetEntity=MvtStk::class, mappedBy="article")
     */
    private $mvtStks;

    public function __construct()
    {
        $this->ligneCommandeClients = new ArrayCollection();
        $this->ligneVentes = new ArrayCollection();
        $this->mvtStks = new ArrayCollection();
    }

    /**
     * @ORM\Column(name="identreprise", type="integer")
     */
    // private $idEntreprise;

    // /**
    //  * @ORM\ManyToOne(targetEntity="Category")
    //  * @ORM\JoinColumn(name="idcategory", referencedColumnName="id")
    //  */
    // private $category;

    // /**
    //  * @ORM\OneToMany(targetEntity="LigneVente", mappedBy="article")
    //  */
    // private $ligneVentes;

    // /**
    //  * @ORM\OneToMany(targetEntity="LigneCommandeClient", mappedBy="article")
    //  */
    // private $ligneCommandeClients;

    // /**
    //  * @ORM\OneToMany(targetEntity="LigneCommandeFournisseur", mappedBy="article")
    //  */
    // private $ligneCommandeFournisseurs;

    // /**
    //  * @ORM\OneToMany(targetEntity="MvtStk", mappedBy="article")
    //  */
    // private $mvtStks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeArticle(): ?string
    {
        return $this->codeArticle;
    }

    public function setCodeArticle(string $codeArticle): self
    {
        $this->codeArticle = $codeArticle;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrixUnitaireHt(): ?string
    {
        return $this->prixUnitaireHt;
    }

    public function setPrixUnitaireHt(string $prixUnitaireHt): self
    {
        $this->prixUnitaireHt = $prixUnitaireHt;

        return $this;
    }
    public function getTauxTva(): ?string
    {
        return $this->tauxTva;
    }

    public function setTauxTva(string $tauxTva): self
    {
        $this->tauxTva = $tauxTva;

        return $this;
    }
    public function getPrixUnitaireTtc(): ?string
    {
        return $this->prixUnitaireTtc;
    }

    public function setPrixUnitaireTtc(string $prixUnitaireTtc): self
    {
        $this->prixUnitaireTtc = $prixUnitaireTtc;

        return $this;
    }
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getIdEntreprise(): ?int
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(int $idEntreprise): self
    {
        $this->idEntreprise = $idEntreprise;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
    public function getLigneVentes(): ?Collection
    {
        return $this->ligneVentes;
    }

    public function setLigneVentes(?Collection $ligneVentes): self
    {
        $this->ligneVentes = $ligneVentes;

        return $this;
    }

    public function getLigneCommandeClients(): ?Collection
    {
        return $this->ligneCommandeClients;
    }

    public function setLigneCommandeClients(?Collection $ligneCommandeClients): self
    {
        $this->ligneCommandeClients = $ligneCommandeClients;

        return $this;
    }

    public function getLigneCommandeFournisseurs(): ?Collection
    {
        return $this->ligneCommandeFournisseurs;
    }

    public function setLigneCommandeFournisseurs(?Collection $ligneCommandeFournisseurs): self
    {
        $this->ligneCommandeFournisseurs = $ligneCommandeFournisseurs;

        return $this;
    }

    public function getMvtStks(): ?Collection
    {
        return $this->mvtStks;
    }

    public function setMvtStks(?Collection $mvtStks): self
    {
        $this->mvtStks = $mvtStks;

        return $this;
    }

    public function addLigneCommandeClient(LigneCommandeClients $ligneCommandeClient): self
    {
        if (!$this->ligneCommandeClients->contains($ligneCommandeClient)) {
            $this->ligneCommandeClients[] = $ligneCommandeClient;
            $ligneCommandeClient->setArticle($this);
        }

        return $this;
    }

    public function removeLigneCommandeClient(LigneCommandeClients $ligneCommandeClient): self
    {
        if ($this->ligneCommandeClients->removeElement($ligneCommandeClient)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommandeClient->getArticle() === $this) {
                $ligneCommandeClient->setArticle(null);
            }
        }

        return $this;
    }

    public function addLigneVente(LigneVente $ligneVente): self
    {
        if (!$this->ligneVentes->contains($ligneVente)) {
            $this->ligneVentes[] = $ligneVente;
            $ligneVente->setArticle($this);
        }

        return $this;
    }

    public function removeLigneVente(LigneVente $ligneVente): self
    {
        if ($this->ligneVentes->removeElement($ligneVente)) {
            // set the owning side to null (unless already changed)
            if ($ligneVente->getArticle() === $this) {
                $ligneVente->setArticle(null);
            }
        }

        return $this;
    }

    public function addMvtStk(MvtStk $mvtStk): self
    {
        if (!$this->mvtStks->contains($mvtStk)) {
            $this->mvtStks[] = $mvtStk;
            $mvtStk->setArticle($this);
        }

        return $this;
    }

    public function removeMvtStk(MvtStk $mvtStk): self
    {
        if ($this->mvtStks->removeElement($mvtStk)) {
            // set the owning side to null (unless already changed)
            if ($mvtStk->getArticle() === $this) {
                $mvtStk->setArticle(null);
            }
        }

        return $this;
    }

    }
