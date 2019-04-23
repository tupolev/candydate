<?php

namespace Candydate\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Candydate\Entity\Language as CandydateLanguage;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable;
use Gedmo\Timestampable\Traits\Timestampable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;

/*
* @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="roles",
 *          column=@ORM\Column(
 *              name     = "c_roles",
 *              type     = "simple_array"
    *          )
 *      )
 * })
*/

/**
 * @ORM\Table(name="users", indexes={
 *     @ORM\Index(name="search_idx_username", columns={"username"}),
 *     @ORM\Index(name="search_idx_email", columns={"email"}),
 * })
 * @ORM\Entity(repositoryClass="Candydate\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="EMAIL_IS_ALREADY_IN_USE")
 * @UniqueEntity(fields={"username"}, message="USERNAME_IS_ALREADY_IN_USE")
 */
class User extends BaseUser
{
    use Timestampable, SoftDeleteable;

    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    /**
     * To validate supported roles
     *
     * @var array
     */
    static public $ROLES_SUPPORTED = [
        self::ROLE_SUPER_ADMIN => self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN => self::ROLE_ADMIN,
        self::ROLE_USER => self::ROLE_USER,
    ];

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true, columnDefinition="INT(1) NULL DEFAULT 1")
     */
    private $active;

    /**
     * @var string
     * @ORM\Column(name="fullname",type="string", nullable=true)
     */
    private $fullName;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true, columnDefinition="INT(1) NULL DEFAULT 0")
     */
    private $verified;

    /**
     * @var CandydateLanguage|null
     * @ORM\ManyToOne(targetEntity="Candydate\Entity\Language", inversedBy="users")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

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
     * @return User
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
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
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->unserialize($this->roles);

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }
}
