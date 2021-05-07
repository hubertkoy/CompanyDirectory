<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "put", "delete", "patch"},
 *     normalizationContext={"groups"="department:read"},
 *     denormalizationContext={"groups"="department:write"}
 * )
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50, maxMessage="Name your Department in 50 chars or less.")
     * @Groups({"department:read", "department:write", "location:read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="departments")
     * @ORM\JoinColumn(name="locationID", referencedColumnName="id")
     * @Groups({"department:read", "department:write"})
     */
    private $locationID;

    /**
     * @ORM\OneToMany(targetEntity=Personnel::class, mappedBy="departmentID")
     * @Groups({"department:read"})
     */
    private $personnels;

    public function __construct()
    {
        $this->personnels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocationID(): ?Location
    {
        return $this->locationID;
    }

    public function setLocationID(?Location $locationID): self
    {
        $this->locationID = $locationID;

        return $this;
    }

    /**
     * @return Collection|Personnel[]
     */
    public function getPersonnels(): Collection
    {
        return $this->personnels;
    }

    public function addPersonnel(Personnel $personnel): self
    {
        if (!$this->personnels->contains($personnel)) {
            $this->personnels[] = $personnel;
            $personnel->setDepartmentID($this);
        }

        return $this;
    }

    public function removePersonnel(Personnel $personnel): self
    {
        if ($this->personnels->removeElement($personnel)) {
            // set the owning side to null (unless already changed)
            if ($personnel->getDepartmentID() === $this) {
                $personnel->setDepartmentID(null);
            }
        }

        return $this;
    }
}
