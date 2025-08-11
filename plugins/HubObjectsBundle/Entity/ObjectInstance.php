<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\CommonEntity;
use Mautic\LeadBundle\Entity\Lead;

class ObjectInstance extends CommonEntity
{
    private ?int $id = null;
    private ?array $properties = [];
    private ?ObjectDefinition $objectDefinition = null;
    private ?Lead $contact = null;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('hub_objects_instances')
            ->setCustomRepositoryClass(ObjectInstanceRepository::class);

        $builder->addIdColumns();
        $builder->addStandardAuditableColumns();

        $builder->createField('properties', 'json')
            ->columnName('properties')
            ->build();

        $builder->createManyToOne('objectDefinition', ObjectDefinition::class)
            ->addJoinColumn('object_definition_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('contact', Lead::class)
            ->addJoinColumn('contact_id', 'id', true, false, 'CASCADE')
            ->build();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperties(): ?array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }

    public function getObjectDefinition(): ?ObjectDefinition
    {
        return $this->objectDefinition;
    }

    public function setObjectDefinition(?ObjectDefinition $objectDefinition): self
    {
        $this->objectDefinition = $objectDefinition;
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
}
