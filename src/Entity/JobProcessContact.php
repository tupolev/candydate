<?php

namespace Candydate\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * @ORM\Entity(repositoryClass="Candydate\Repository\JobProcessContactRepository")
 */
class JobProcessContact
{
    use Timestampable, SoftDeleteable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="fullname",type="string", nullable=false)
     */
    private $fullName;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     * @ORM\Column(name="extra_info",type="text", nullable=true)
     */
    private $extraInfo;

    /**
     * @var Collection|JobProcess[]
     *
     * @ORM\ManyToMany(targetEntity="JobProcess", mappedBy="contacts")
     */
    protected $jobProcesses;

    public function __construct()
    {
        $this->jobProcesses = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getExtraInfo(): ?string
    {
        return $this->extraInfo;
    }

    /**
     * @param string|null $extraInfo
     */
    public function setExtraInfo(?string $extraInfo): void
    {
        $this->extraInfo = $extraInfo;
    }

    /**
     * @param JobProcess $jobProcess
     */
    public function addJobProcess(JobProcess $jobProcess): void
    {
        if ($this->jobProcesses->contains($jobProcess)) {
            return;
        }
        $this->jobProcesses->add($jobProcess);
        $jobProcess->addJobProcessContact($this);
    }

    /**
     * @param JobProcess $jobProcess
     */
    public function removeJobProcess(JobProcess $jobProcess): void
    {
        if (!$this->jobProcesses->contains($jobProcess)) {
            return;
        }
        $this->jobProcesses->removeElement($jobProcess);
        $jobProcess->removeJobProcessContact($this);
    }
}
