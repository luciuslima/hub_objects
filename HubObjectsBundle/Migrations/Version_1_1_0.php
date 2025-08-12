<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Mautic\IntegrationsBundle\Migration\AbstractMigration;

class Version_1_1_0 extends AbstractMigration
{
    /**
     * @throws SchemaException
     */
    protected function isApplicable(Schema $schema): bool
    {
        return !$schema->hasTable($this->concatPrefix('hub_objects_definitions'));
    }

    protected function up(): void
    {
        // Definitions Table
        $this->addSql("
            CREATE TABLE `{$this->concatPrefix('hub_objects_definitions')}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `created_by` int(11) DEFAULT NULL,
                `created_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_added` datetime DEFAULT NULL,
                `modified_by` int(11) DEFAULT NULL,
                `modified_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_modified` datetime DEFAULT NULL,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `plural_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `{$this->concatPrefix('hub_objects_definitions_slug_unique')}` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Fields Table
        $this->addSql("
            CREATE TABLE `{$this->concatPrefix('hub_objects_fields')}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `ordering` int(11) NOT NULL DEFAULT 0,
                `object_definition_id` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `{$this->concatPrefix('hub_objects_fields_object_definition_id')}` (`object_definition_id`),
                CONSTRAINT `{$this->concatPrefix('fk_hub_objects_fields_object_definition')}` FOREIGN KEY (`object_definition_id`) REFERENCES `{$this->concatPrefix('hub_objects_definitions')}` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Instances Table
        $this->addSql("
            CREATE TABLE `{$this->concatPrefix('hub_objects_instances')}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `created_by` int(11) DEFAULT NULL,
                `created_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_added` datetime DEFAULT NULL,
                `modified_by` int(11) DEFAULT NULL,
                `modified_by_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `date_modified` datetime DEFAULT NULL,
                `properties` json DEFAULT NULL,
                `object_definition_id` int(11) NOT NULL,
                `contact_id` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `{$this->concatPrefix('hub_objects_instances_object_definition_id')}` (`object_definition_id`),
                KEY `{$this->concatPrefix('hub_objects_instances_contact_id')}` (`contact_id`),
                CONSTRAINT `{$this->concatPrefix('fk_hub_objects_instances_object_definition')}` FOREIGN KEY (`object_definition_id`) REFERENCES `{$this->concatPrefix('hub_objects_definitions')}` (`id`) ON DELETE CASCADE,
                CONSTRAINT `{$this->concatPrefix('fk_hub_objects_instances_contact')}` FOREIGN KEY (`contact_id`) REFERENCES `{$this->concatPrefix('leads')}` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }
}
