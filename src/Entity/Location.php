<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *     "get" = { "security" = "is_granted('ROLE_USER')" },
 *     "post" = { "security" = "is_granted('ROLE_ADMIN')" }
 * },
 *     itemOperations={
 *     "get" = { "security" = "is_granted('ROLE_USER')" },
 *     "put" = { "security" = "is_granted('ROLE_ADMIN')" },
 *     "delete" = { "security" = "is_granted('ROLE_ADMIN')" },
 *     "patch" = { "security" = "is_granted('ROLE_ADMIN')" }
 * },
 *     normalizationContext={"groups"={"location:read"}},
 *     denormalizationContext={"groups"={"location:write"}}
 * )
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Location
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
     * @Assert\Length(min=2, max=50, maxMessage="Name Location in 50 chars or less.")
     * @Groups({"location:read", "location:write", "personnel:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Department::class, mappedBy="locationID")
     * @Groups({"location:read", "location:write"})
     */
    private $departments;

    public function __construct()
    {
        $this->departments = new ArrayCollection();
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

    /**
     * @return Collection|Department[]
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments[] = $department;
            $department->setLocationID($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->removeElement($department)) {
            // set the owning side to null (unless already changed)
            if ($department->getLocationID() === $this) {
                $department->setLocationID(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeLocation(): void
    {
        if (!$this->getDepartments()->isEmpty()) {
            throw new \Exception('Location have assigned Departments and cannot be removed.');
        }
    }
}
