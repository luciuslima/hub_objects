<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\CommonEntity;

class ObjectDefinition extends CommonEntity
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $pluralName = null;
    private ?string $slug = null;
    /**
     * @var Collection<int, FieldDefinition>
     */
    private Collection $fields;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('hub_objects_definitions')
            ->setCustomRepositoryClass(ObjectDefinitionRepository::class);

        $builder->addIdColumns();
        $builder->addStandardAuditableColumns();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->unique(true)
            ->build();

        $builder->createField('pluralName', 'string')
            ->columnName('plural_name')
            ->build();

        $builder->createField('slug', 'string')
            ->columnName('slug')
            ->unique(true)
            ->build();

        $builder->createOneToMany('fields', FieldDefinition::class)
            ->setmappedBy('objectDefinition')
            ->setOrderBy(['ordering' => 'ASC'])
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

    public function getPluralName(): ?string
    {
        return $this->pluralName;
    }

    public function setPluralName(string $pluralName): self
    {
        $this->pluralName = $pluralName;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return Collection<int, FieldDefinition>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }
}
