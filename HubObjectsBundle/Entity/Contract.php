<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Entity\CommonEntity;
use Mautic\LeadBundle\Entity\Lead;

class Contract extends CommonEntity
{
    private ?int $id = null;
    private ?string $name = null;
    private ?float $value = null;
    private ?\DateTimeInterface $startDate = null;
    private ?\DateTimeInterface $endDate = null;
    private ?Lead $contact = null;
    /**
     * @var Collection<int, Product>
     */
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('hub_objects_contracts')
            ->setCustomRepositoryClass(ContractRepository::class);

        $builder->addIdColumns();
        $builder->addStandardAuditableColumns();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->build();

        $builder->createField('value', 'decimal')
            ->columnName('value')
            ->precision(10)
            ->scale(2)
            ->nullable(true)
            ->build();

        $builder->createField('startDate', 'datetime')
            ->columnName('start_date')
            ->nullable(true)
            ->build();

        $builder->createField('endDate', 'datetime')
            ->columnName('end_date')
            ->nullable(true)
            ->build();

        $builder->createManyToOne('contact', Lead::class)
            ->inversedBy('contracts')
            ->addJoinColumn('contact_id', 'id', true, false, 'CASCADE')
            ->build();

        $builder->createManyToMany('products', Product::class)
            ->setJoinTable('hub_objects_contracts_products')
            ->addJoinColumn('contract_id', 'id', false, false, 'CASCADE')
            ->addInverseJoinColumn('product_id', 'id', false, false, 'CASCADE')
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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;
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

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): void
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }
    }

    public function removeProduct(Product $product): void
    {
        $this->products->removeElement($product);
    }

    public static function loadApiMetadata(ApiMetadataDriver $driver): void
    {
        $driver->add(
            'contractList',
            [
                'id',
                'name',
                'value',
            ]
        );

        $driver->add(
            'contractDetails',
            [
                'id',
                'name',
                'value',
                'startDate',
                'endDate',
                'contact',
                'products',
                'dateAdded',
                'createdBy',
                'createdByUser',
                'dateModified',
                'modifiedBy',
                'modifiedByUser',
            ],
            'contract'
        );
    }
}
