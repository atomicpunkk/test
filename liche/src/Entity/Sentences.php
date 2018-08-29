<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SentencesRepository")
 */
class Sentences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $constraintnumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Saison", inversedBy="sentences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $saison;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getConstraintnumber(): ?string
    {
        return $this->constraintnumber;
    }

    public function setConstraintnumber(?string $constraintnumber): self
    {
        $this->constraintnumber = $constraintnumber;

        return $this;
    }

    public function getSaison(): ?Saison
    {
        return $this->saison;
    }

    public function setSaison(?Saison $saison): self
    {
        $this->saison = $saison;

        return $this;
    }

    public function getOrders(): ?int
    {
        return $this->orders;
    }

    public function setOrders(?int $orders): self
    {
        $this->orders = $orders;

        return $this;
    }
}
