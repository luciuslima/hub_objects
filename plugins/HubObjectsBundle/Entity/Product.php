<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Entity\CommonEntity;

class Product extends CommonEntity
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $description = null;
    private ?float $price = null;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('hub_objects_products')
            ->setCustomRepositoryClass(ProductRepository::class);

        $builder->addIdColumns();
        $builder->addStandardAuditableColumns();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->build();

        $builder->createField('description', 'text')
            ->columnName('description')
            ->nullable(true)
            ->build();

        $builder->createField('price', 'decimal')
            ->columnName('price')
            ->precision(10)
            ->scale(2)
            ->nullable(true)
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public static function loadApiMetadata(ApiMetadataDriver $driver): void
    {
        $driver->add(
            'productList',
            [
                'id',
                'name',
                'price',
            ]
        );

        $driver->add(
            'productDetails',
            [
                'id',
                'name',
                'description',
                'price',
                'dateAdded',
                'createdBy',
                'createdByUser',
                'dateModified',
                'modifiedBy',
                'modifiedByUser',
            ],
            'product'
        );
    }
}
