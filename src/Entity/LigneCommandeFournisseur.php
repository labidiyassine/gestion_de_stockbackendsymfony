<?php

namespace App\Entity;

use App\Repository\LigneCommandeFournisseurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneCommandeFournisseurRepository::class)
 */
class LigneCommandeFournisseur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CommandeFournisseur::class, inversedBy="ligneCommandeFournisseurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCommandeFournisseur;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idArticle;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prixUnitaire;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastModifiedDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $idEntreprise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCommandeFournisseur(): ?CommandeFournisseur
    {
        return $this->idCommandeFournisseur;
    }

    public function setIdCommandeFournisseur(?CommandeFournisseur $idCommandeFournisseur): self
    {
        $this->idCommandeFournisseur = $idCommandeFournisseur;

        return $this;
    }

    public function getIdArticle(): ?Article
    {
        return $this->idArticle;
    }

    public function setIdArticle(?Article $idArticle): self
    {
        $this->idArticle = $idArticle;

        return $this;
    }

    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire($prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getLastModifiedDate(): ?\DateTimeInterface
    {
        return $this->lastModifiedDate;
    }

    public function setLastModifiedDate(\DateTimeInterface $lastModifiedDate): self
    {
        $this->lastModifiedDate = $lastModifiedDate;

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
}
