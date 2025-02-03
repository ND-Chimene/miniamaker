<?php

namespace App\Entity;

use App\Repository\DetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailRepository::class)]
#[ORM\HasLifecycleCallbacks] // Gestion de createdAt et updatedAt
class Detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $company_number = null;

    #[ORM\Column(length: 80)]
    private ?string $company_name = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(length: 80)]
    private ?string $city = null;

    #[ORM\Column(length: 80)]
    private ?string $postal_code = null;

    #[ORM\Column(length: 80)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $portfolio_link = null;

    #[ORM\Column]
    private ?bool $portfolio_check = null;

    #[ORM\Column]
    private ?int $strikes = null;

    #[ORM\Column]
    private ?bool $is_banned = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToOne(inversedBy: 'detail', cascade: ['persist', 'remove'])]
    private ?User $pro = null;


    public function __construct()
    {
        $this->country = 'FR';
        $this->portfolio_check = false;
        $this->strikes = 0;
        $this->is_banned = false;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyNumber(): ?string
    {
        return $this->company_number;
    }

    public function setCompanyNumber(string $company_number): static
    {
        $this->company_number = $company_number;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): static
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getFullAdress(): ?string
    {
        return $this->adress . ', ' . $this->postal_code . '' . $this->city . ', ' . $this->country;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPortfolioLink(): ?string
    {
        return $this->portfolio_link;
    }

    public function setPortfolioLink(string $portfolio_link): static
    {
        $this->portfolio_link = $portfolio_link;

        return $this;
    }

    public function isPortfolioCheck(): ?bool
    {
        return $this->portfolio_check;
    }

    public function setPortfolioCheck(bool $portfolio_check): static
    {
        $this->portfolio_check = $portfolio_check;

        return $this;
    }

    public function getStrikes(): ?int
    {
        return $this->strikes;
    }

    public function setStrikes(int $strikes): static
    {
        $this->strikes = $strikes;

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->is_banned;
    }

    public function setIsBanned(): static
    {
        if ($this->strikes > 1) {
            $this->is_banned = true;
        }
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getPro(): ?User
    {
        return $this->pro;
    }

    public function setPro(?User $pro): static
    {
        $this->pro = $pro;

        return $this;
    }

    // Indispensable pour afficher une string plutôt que l'objet
    public function __toString(): string
    {
        return $this->company_name;
    }
}
