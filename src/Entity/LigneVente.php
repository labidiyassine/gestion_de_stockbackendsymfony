<?php

namespace App\Entity;

use App\Repository\LigneVenteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneVenteRepository::class)
 */
class LigneVente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ventes::class, inversedBy="ligneVentes")
     */
    private $vente;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="ligneVentes")
     */
    private $Article;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $quantite;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $prixUnitaire;

    /**
     * @ORM\Column(type="integer")
     */
    private $idEntreprise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVente(): ?Ventes
    {
        return $this->vente;
    }

    public function setVente(?Ventes $vente): self
    {
        $this->vente = $vente;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->Article;
    }

    public function setArticle(?Article $Article): self
    {
        $this->Article = $Article;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(string $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

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
