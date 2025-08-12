<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Mautic\IntegrationsBundle\Migration\AbstractMigration;

class Version_1_0_0 extends AbstractMigration
{
    /**
     * @throws SchemaException
     */
    protected function isApplicable(Schema $schema): bool
    {
        return !$schema->hasTable($this->concatPrefix('hub_objects_products'));
    }

    protected function up(): void
    {
        // Products Table
        $this->addSql("
            CREATE TABLE `{$this->concatPrefix('hub_objects_products')}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `created_by` int(11) DEFAULT NULL,
                `created_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_added` datetime DEFAULT NULL,
                `modified_by` int(11) DEFAULT NULL,
                `modified_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_modified` datetime DEFAULT NULL,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `price` decimal(10,2) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `{$this->concatPrefix('hub_objects_products_created_by')}` (`created_by`),
                KEY `{$this->concatPrefix('hub_objects_products_modified_by')}` (`modified_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Contracts Table
        $this->addSql("
            CREATE TABLE `{$this->concatPrefix('hub_objects_contracts')}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `created_by` int(11) DEFAULT NULL,
                `created_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_added` datetime DEFAULT NULL,
                `modified_by` int(11) DEFAULT NULL,
                `modified_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_modified` datetime DEFAULT NULL,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `value` decimal(10,2) DEFAULT NULL,
                `start_date` datetime DEFAULT NULL,
                `end_date` datetime DEFAULT NULL,
                `contact_id` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `{$this->concatPrefix('hub_objects_contracts_contact_id')}` (`contact_id`),
                CONSTRAINT `{$this->concatPrefix('fk_hub_objects_contracts_contact')}` FOREIGN KEY (`contact_id`) REFERENCES `{$this->concatPrefix('leads')}` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Opportunities Table
        $this->addSql("
            CREATE TABLE `{$this->concatPrefix('hub_objects_opportunities')}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `created_by` int(11) DEFAULT NULL,
                `created_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_added` datetime DEFAULT NULL,
                `modified_by` int(11) DEFAULT NULL,
                `modified_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_modified` datetime DEFAULT NULL,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `amount` decimal(10,2) DEFAULT NULL,
                `stage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `close_date` datetime DEFAULT NULL,
                `contact_id` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `{$this->concatPrefix('hub_objects_opportunities_contact_id')}` (`contact_id`),
                CONSTRAINT `{$this->concatPrefix('fk_hub_objects_opportunities_contact')}` FOREIGN KEY (`contact_id`) REFERENCES `{$this->concatPrefix('leads')}` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Contracts-Products Join Table
        $this->addSql("
            CREATE TABLE `{$this->concatPrefix('hub_objects_contracts_products')}` (
                `contract_id` int(11) NOT NULL,
                `product_id` int(11) NOT NULL,
                PRIMARY KEY (`contract_id`,`product_id`),
                KEY `{$this->concatPrefix('idx_contract_id')}` (`contract_id`),
                KEY `{$this->concatPrefix('idx_product_id')}` (`product_id`),
                CONSTRAINT `{$this->concatPrefix('fk_contract_products_contract')}` FOREIGN KEY (`contract_id`) REFERENCES `{$this->concatPrefix('hub_objects_contracts')}` (`id`) ON DELETE CASCADE,
                CONSTRAINT `{$this->concatPrefix('fk_contract_products_product')}` FOREIGN KEY (`product_id`) REFERENCES `{$this->concatPrefix('hub_objects_products')}` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }
}
