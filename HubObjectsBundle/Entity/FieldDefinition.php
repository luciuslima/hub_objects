<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\CommonEntity;

class FieldDefinition extends CommonEntity
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $type = null;
    private int $ordering = 0;
    private ?ObjectDefinition $objectDefinition = null;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('hub_objects_fields')
            ->setCustomRepositoryClass(FieldDefinitionRepository::class);

        $builder->addIdColumns();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->build();

        $builder->createField('type', 'string')
            ->columnName('type')
            ->build();

        $builder->createField('ordering', 'integer')
            ->columnName('ordering')
            ->build();

        $builder->createManyToOne('objectDefinition', ObjectDefinition::class)
            ->inversedBy('fields')
            ->addJoinColumn('object_definition_id', 'id', false, false, 'CASCADE')
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getOrdering(): int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): self
    {
        $this->ordering = $ordering;
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
}
