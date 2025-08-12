<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Entity\CommonEntity;
use Mautic\LeadBundle\Entity\Lead;

class Opportunity extends CommonEntity
{
    private ?int $id = null;
    private ?string $name = null;
    private ?float $amount = null;
    private ?string $stage = null;
    private ?\DateTimeInterface $closeDate = null;
    private ?Lead $contact = null;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('hub_objects_opportunities')
            ->setCustomRepositoryClass(OpportunityRepository::class);

        $builder->addIdColumns();
        $builder->addStandardAuditableColumns();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->build();

        $builder->createField('amount', 'decimal')
            ->columnName('amount')
            ->precision(10)
            ->scale(2)
            ->nullable(true)
            ->build();

        $builder->createField('stage', 'string')
            ->columnName('stage')
            ->nullable(true)
            ->build();

        $builder->createField('closeDate', 'datetime')
            ->columnName('close_date')
            ->nullable(true)
            ->build();

        $builder->createManyToOne('contact', Lead::class)
            ->inversedBy('opportunities')
            ->addJoinColumn('contact_id', 'id', true, false, 'CASCADE')
            ->build();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getStage(): ?string
    {
        return $this->stage;
    }

    public function setStage(?string $stage): self
    {
        $this->stage = $stage;
        return $this;
    }

    public function getCloseDate(): ?\DateTimeInterface
    {
        return $this->closeDate;
    }

    public function setCloseDate(?\DateTimeInterface $closeDate): self
    {
        $this->closeDate = $closeDate;
        return $this;
    }

    public function getContact(): ?Lead
    {
        return $this->contact;
    }

    public function setContact(?Lead $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    public static function loadApiMetadata(ApiMetadataDriver $driver): void
    {
        $driver->add(
            'opportunityList',
            [
                'id',
                'name',
                'amount',
                'stage',
            ]
        );

        $driver->add(
            'opportunityDetails',
            [
                'id',
                'name',
                'amount',
                'stage',
                'closeDate',
                'contact',
                'dateAdded',
                'createdBy',
                'createdByUser',
                'dateModified',
                'modifiedBy',
                'modifiedByUser',
            ],
            'opportunity'
        );
    }
}
