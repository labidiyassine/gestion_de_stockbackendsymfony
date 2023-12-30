<?php

namespace App\Entity;

use App\Repository\CommandeFournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeFournisseurRepository::class)
 */
class CommandeFournisseur
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
    private $code;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCommande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etatCommande;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="commandeFournisseurs")
     */
    private $idEntreprise;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommandeFournisseur::class, mappedBy="commandeFournisseur")
     */
    private $LigneCommandeFournisseur;

    public function __construct()
    {
        $this->LigneCommandeFournisseur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getEtatCommande(): ?string
    {
        return $this->etatCommande;
    }

    public function setEtatCommande(string $etatCommande): self
    {
        $this->etatCommande = $etatCommande;

        return $this;
    }

    public function getIdEntreprise(): ?Fournisseur
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(?Fournisseur $idEntreprise): self
    {
        $this->idEntreprise = $idEntreprise;

        return $this;
    }

    /**
     * @return Collection<int, LigneCommandeFournisseur>
     */
    public function getLigneCommandeFournisseur(): Collection
    {
        return $this->LigneCommandeFournisseur;
    }

    public function addLigneCommandeFournisseur(LigneCommandeFournisseur $ligneCommandeFournisseur): self
    {
        if (!$this->LigneCommandeFournisseur->contains($ligneCommandeFournisseur)) {
            $this->LigneCommandeFournisseur[] = $ligneCommandeFournisseur;
            $ligneCommandeFournisseur->setCommandeFournisseur($this);
        }

        return $this;
    }

    public function removeLigneCommandeFournisseur(LigneCommandeFournisseur $ligneCommandeFournisseur): self
    {
        if ($this->LigneCommandeFournisseur->removeElement($ligneCommandeFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommandeFournisseur->getCommandeFournisseur() === $this) {
                $ligneCommandeFournisseur->setCommandeFournisseur(null);
            }
        }

        return $this;
    }
}
