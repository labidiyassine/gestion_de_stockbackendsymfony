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
     * @ORM\ManyToOne(targetEntity=CommandeFournisseur::class, inversedBy="LigneCommandeFournisseur")
     */
    private $commandeFournisseur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandeFournisseur(): ?CommandeFournisseur
    {
        return $this->commandeFournisseur;
    }

    public function setCommandeFournisseur(?CommandeFournisseur $commandeFournisseur): self
    {
        $this->commandeFournisseur = $commandeFournisseur;

        return $this;
    }
}
