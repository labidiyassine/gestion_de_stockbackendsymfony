<?php

namespace App\Entity;

use App\Repository\CommandeClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeClientRepository::class)
 */
class CommandeClient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="commandeClients")
     */
    private $client;

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
     * @ORM\Column(type="integer")
     */
    private $idEntreprise;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommandeClients::class, mappedBy="commandeClient")
     */
    private $LigneCommandeClients;

    public function __construct()
    {
        $this->LigneCommandeClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
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

    public function getIdEntreprise(): ?int
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(int $idEntreprise): self
    {
        $this->idEntreprise = $idEntreprise;

        return $this;
    }

    /**
     * @return Collection<int, LigneCommandeClients>
     */
    public function getLigneCommandeClients(): Collection
    {
        return $this->LigneCommandeClients;
    }

    public function addLigneCommandeClient(LigneCommandeClients $ligneCommandeClient): self
    {
        if (!$this->LigneCommandeClients->contains($ligneCommandeClient)) {
            $this->LigneCommandeClients[] = $ligneCommandeClient;
            $ligneCommandeClient->setCommandeClient($this);
        }

        return $this;
    }

    public function removeLigneCommandeClient(LigneCommandeClients $ligneCommandeClient): self
    {
        if ($this->LigneCommandeClients->removeElement($ligneCommandeClient)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommandeClient->getCommandeClient() === $this) {
                $ligneCommandeClient->setCommandeClient(null);
            }
        }

        return $this;
    }
}
