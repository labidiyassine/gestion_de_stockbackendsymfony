<?php

namespace App\Entity;

use App\Repository\MvtStkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MvtStkRepository::class)
 */
class MvtStk
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateMvt;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=article::class, inversedBy="mvtStks")
     */
    private $article;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeMvt;

    /**
     * @ORM\Column(type="integer")
     */
    private $sourceMvt;

    /**
     * @ORM\Column(type="integer")
     */
    private $idEntreprise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateMvt(): ?\DateTimeInterface
    {
        return $this->dateMvt;
    }

    public function setDateMvt(\DateTimeInterface $dateMvt): self
    {
        $this->dateMvt = $dateMvt;

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

    public function getArticle(): ?article
    {
        return $this->article;
    }

    public function setArticle(?article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getTypeMvt(): ?string
    {
        return $this->typeMvt;
    }

    public function setTypeMvt(string $typeMvt): self
    {
        $this->typeMvt = $typeMvt;

        return $this;
    }

    public function getSourceMvt(): ?int
    {
        return $this->sourceMvt;
    }

    public function setSourceMvt(int $sourceMvt): self
    {
        $this->sourceMvt = $sourceMvt;

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
