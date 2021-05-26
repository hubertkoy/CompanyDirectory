<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\DepartmentRepository;
use App\Repository\PersonnelRepository;
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
 *     normalizationContext={"groups"={"personnel:read"}},
 *     denormalizationContext={"groups"={"personnel:write"}}
 * )
 * @ORM\Table(name="personnel")
 * @ORM\Entity(repositoryClass=PersonnelRepository::class)
 * @ApiFilter(SearchFilter::class,
 *     properties={
 *     "firstName": "partial",
 *     "lastName": "partial",
 *     "jobTitle": "partial",
 *     "email": "partial",
 *     "departmentID.name": "partial"
 * })
 */
class Personnel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", length=11, nullable=false)
     * @Groups({"personnel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=50, maxMessage="Type First Name in 50 chars or less.")
     * @Groups({"personnel:read", "personnel:write", "department:read"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=50, maxMessage="Type Last Name in 50 chars or less.")
     * @Groups({"personnel:read", "personnel:write", "department:read"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(max=50, maxMessage="Type Job Title in 50 chars or less.")
     * @Groups({"personnel:read", "personnel:write", "department:read"})
     */
    private $jobTitle;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Email()
     * @Assert\Length(max=50, maxMessage="Type Email in 50 chars or less.")
     * @Groups({"personnel:read", "personnel:write", "department:read"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="personnels")
     * @ORM\JoinColumn(name="departmentID", referencedColumnName="id")
     * @Groups({"personnel:read", "personnel:write"})
     */
    private $departmentID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDepartmentID(): ?Department
    {
        return $this->departmentID;
    }

    public function setDepartmentID(?Department $departmentID): self
    {
        $this->departmentID = $departmentID;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->departmentID?->getName();
    }

    public function getLocation(): ?string
    {
        return $this->departmentID?->getLocationID()->getName();
    }
}
