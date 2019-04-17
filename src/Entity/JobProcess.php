<?php

namespace Candydate\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * @ORM\Entity(repositoryClass="Candydate\Repository\JobProcessRepository")
 */
class JobProcess
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
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true, columnDefinition="INT(1) NULL DEFAULT 1")
     */
    private $active;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="Candydate\Entity\User", inversedBy="jobProcesses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="organization_name",type="string", nullable=false)
     */
    private $organizationName;

    /**
     * @var string|null
     * @ORM\Column(name="organization_description",type="text", nullable=true)
     */
    private $organizationDescription;

    /**
     * @var string|null
     * @ORM\Column(name="job_title",type="string", nullable=false)
     */
    private $jobTitle;

    /**
     * @var string|null
     * @ORM\Column(name="job_description",type="text", nullable=true)
     */
    private $jobDescription;

    /**
     * @var string|null
     * @ORM\Column(name="job_link",type="string", nullable=true)
     */
    private $jobLink;

    /**
     * @var string|null
     * @ORM\Column(name="job_origin_platform",type="string", nullable=true)
     */
    private $jobOriginPlatform;

    /**
     * @var string|null
     * @ORM\Column(name="salary_requested",type="string", nullable=true)
     */
    private $salaryRequested;

    /**
     * @var string|null
     * @ORM\Column(name="salary_offered",type="string", nullable=true)
     */
    private $salaryOffered;

    /**
     * @var string
     * @ORM\Column(name="location_country",type="string", nullable=false)
     */
    private $locationCountry;

    /**
     * @var string|null
     * @ORM\Column(name="location_city",type="string", nullable=true)
     */
    private $locationCity;

    /**
     * @var string|null
     * @ORM\Column(name="location_extra_info",type="text", nullable=true)
     */
    private $locationExtraInfo;

    /**
     * @var bool|null
     * @ORM\Column(name="is_fully_remote",type="boolean", nullable=true, columnDefinition="INT(1) NULL DEFAULT 0")
     */
    private $isFullyRemote;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_start_offered",type="date", nullable=true)
     */
    private $dateStartOffered;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_start_requested",type="date", nullable=true)
     */
    private $dateStartRequested;

    /**
     * @var Collection|JobProcessContact[]
     *
     * @ORM\ManyToMany(targetEntity="JobProcessContact", inversedBy="jobProcesses")
     * @ORM\JoinTable(
     *  name="job_process_job_process_contact",
     *  joinColumns={
     *      @ORM\JoinColumn(name="job_process_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="job_process_contact_id", referencedColumnName="id")
     *  }
     * )
     */
    private $contacts;


    /**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }

    /**
     * @param string $organizationName
     */
    public function setOrganizationName(string $organizationName): void
    {
        $this->organizationName = $organizationName;
    }

    /**
     * @return string|null
     */
    public function getOrganizationDescription(): ?string
    {
        return $this->organizationDescription;
    }

    /**
     * @param string|null $organizationDescription
     */
    public function setOrganizationDescription(?string $organizationDescription): void
    {
        $this->organizationDescription = $organizationDescription;
    }

    /**
     * @return string|null
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @param string|null $jobTitle
     */
    public function setJobTitle(?string $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return string|null
     */
    public function getJobDescription(): ?string
    {
        return $this->jobDescription;
    }

    /**
     * @param string|null $jobDescription
     */
    public function setJobDescription(?string $jobDescription): void
    {
        $this->jobDescription = $jobDescription;
    }

    /**
     * @return string|null
     */
    public function getJobLink(): ?string
    {
        return $this->jobLink;
    }

    /**
     * @param string|null $jobLink
     */
    public function setJobLink(?string $jobLink): void
    {
        $this->jobLink = $jobLink;
    }

    /**
     * @return string|null
     */
    public function getJobOriginPlatform(): ?string
    {
        return $this->jobOriginPlatform;
    }

    /**
     * @param string|null $jobOriginPlatform
     */
    public function setJobOriginPlatform(?string $jobOriginPlatform): void
    {
        $this->jobOriginPlatform = $jobOriginPlatform;
    }

    /**
     * @return string|null
     */
    public function getSalaryRequested(): ?string
    {
        return $this->salaryRequested;
    }

    /**
     * @param string|null $salaryRequested
     */
    public function setSalaryRequested(?string $salaryRequested): void
    {
        $this->salaryRequested = $salaryRequested;
    }

    /**
     * @return string|null
     */
    public function getSalaryOffered(): ?string
    {
        return $this->salaryOffered;
    }

    /**
     * @param string|null $salaryOffered
     */
    public function setSalaryOffered(?string $salaryOffered): void
    {
        $this->salaryOffered = $salaryOffered;
    }

    /**
     * @return string
     */
    public function getLocationCountry(): string
    {
        return $this->locationCountry;
    }

    /**
     * @param string $locationCountry
     */
    public function setLocationCountry(string $locationCountry): void
    {
        $this->locationCountry = $locationCountry;
    }

    /**
     * @return string|null
     */
    public function getLocationCity(): ?string
    {
        return $this->locationCity;
    }

    /**
     * @param string|null $locationCity
     */
    public function setLocationCity(?string $locationCity): void
    {
        $this->locationCity = $locationCity;
    }

    /**
     * @return string|null
     */
    public function getLocationExtraInfo(): ?string
    {
        return $this->locationExtraInfo;
    }

    /**
     * @param string|null $locationExtraInfo
     */
    public function setLocationExtraInfo(?string $locationExtraInfo): void
    {
        $this->locationExtraInfo = $locationExtraInfo;
    }

    /**
     * @return bool|null
     */
    public function getIsFullyRemote(): ?bool
    {
        return $this->isFullyRemote;
    }

    /**
     * @param bool|null $isFullyRemote
     */
    public function setIsFullyRemote(?bool $isFullyRemote): void
    {
        $this->isFullyRemote = $isFullyRemote;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateStartOffered(): ?\DateTime
    {
        return $this->dateStartOffered;
    }

    /**
     * @param \DateTime|null $dateStartOffered
     */
    public function setDateStartOffered(?\DateTime $dateStartOffered): void
    {
        $this->dateStartOffered = $dateStartOffered;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateStartRequested(): ?\DateTime
    {
        return $this->dateStartRequested;
    }

    /**
     * @param \DateTime|null $dateStartRequested
     */
    public function setDateStartRequested(?\DateTime $dateStartRequested): void
    {
        $this->dateStartRequested = $dateStartRequested;
    }

    /**
     * @param JobProcessContact $contact
     */
    public function addJobProcessContact(JobProcessContact $contact): void
    {
        if ($this->contacts->contains($contact)) {
            return;
        }
        $this->contacts->add($contact);
        $contact->addJobProcess($this);
    }

    /**
     * @param JobProcessContact $contact
     */
    public function removeJobProcessContact(JobProcessContact $contact): void
    {
        if (!$this->contacts->contains($contact)) {
            return;
        }
        $this->contacts->removeElement($contact);
        $contact->removeJobProcess($this);
    }
}
