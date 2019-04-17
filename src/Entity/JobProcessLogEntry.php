<?php

namespace Candydate\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * @ORM\Entity(repositoryClass="Candydate\Repository\JobProcessLogRepository")
 */
class JobProcessLogEntry
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
     * @var JobProcess
     * @ORM\ManyToOne(targetEntity="JobProcess", inversedBy="logEntries")
     * @ORM\JoinColumn(name="job_process_id", referencedColumnName="id")
     */
    private $jobProcess;

    /**
     * @var JobProcessStatus
     * @ORM\ManyToOne(targetEntity="JobProcessStatus", inversedBy="jobProcessLogEntries")
     * @ORM\JoinColumn(name="job_process_status_id", referencedColumnName="id")
     */
    private $jobProcessStatus;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $details;

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
     * @return JobProcess
     */
    public function getJobProcess(): JobProcess
    {
        return $this->jobProcess;
    }

    /**
     * @param JobProcess $jobProcess
     */
    public function setJobProcess(JobProcess $jobProcess): void
    {
        $this->jobProcess = $jobProcess;
    }

    /**
     * @return JobProcessStatus
     */
    public function getJobProcessStatusId(): JobProcessStatus
    {
        return $this->jobProcessStatus;
    }

    /**
     * @param JobProcessStatus $jobProcessStatus
     */
    public function setJobProcessStatusId(JobProcessStatus $jobProcessStatus): void
    {
        $this->jobProcessStatus = $jobProcessStatus;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDetails(): ?string
    {
        return $this->details;
    }

    /**
     * @param string|null $details
     */
    public function setDetails(?string $details): void
    {
        $this->details = $details;
    }
}
