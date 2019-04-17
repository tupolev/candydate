<?php

namespace Candydate\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Candydate\Entity\Language as CandydateLanguage;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * @ORM\Entity(repositoryClass="Candydate\Repository\UserRepository")
 */
class User
{
    use Timestampable, SoftDeleteable;

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true, columnDefinition="INT(1) NULL DEFAULT 1")
     */
    private $active;

    /**
     * @var string
     * @ORM\Column(name="fullname",type="boolean", nullable=false)
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", nullable=false)
     */
    private $userName;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $salt;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true, columnDefinition="INT(1) NULL DEFAULT 0")
     */
    private $verified;

    /**
     * @var string
     * @ORM\Column(name="verification_link",type="string", nullable=false)
     */
    private $verificationLink;

    /**
     * @var CandydateLanguage|null
     * @ORM\ManyToOne(targetEntity="Candydate\Entity\Language", inversedBy="users")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    /**
     * @var string[]
     * @ORM\Column(type="json_array", nullable=false)
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     */
    public function setVerified(bool $verified): void
    {
        $this->verified = $verified;
    }

    /**
     * @return string
     */
    public function getVerificationLink(): string
    {
        return $this->verificationLink;
    }

    /**
     * @param string $verificationLink
     */
    public function setVerificationLink(string $verificationLink): void
    {
        $this->verificationLink = $verificationLink;
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @param Language|null $language
     */
    public function setLanguage(?Language $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
