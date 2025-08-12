<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\EventListener;

use Doctrine\DBAL\Query\QueryBuilder;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\SegmentBundle\Event\SegmentBuilderEvent;
use Mautic\SegmentBundle\Event\SegmentQueryEvent;
use Mautic\SegmentBundle\SegmentEvents;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;

class SegmentSubscriber extends CommonSubscriber
{
    private ObjectDefinitionModel $definitionModel;

    public function __construct(ObjectDefinitionModel $definitionModel)
    {
        $this->definitionModel = $definitionModel;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SegmentEvents::SEGMENT_ON_BUILD       => ['onSegmentBuild', 0],
            SegmentEvents::SEGMENT_QUERY_ON_BUILD => ['onSegmentQueryBuild', 0],
        ];
    }

    public function onSegmentBuild(SegmentBuilderEvent $event): void
    {
        $definitions = $this->definitionModel->getRepository()->findAll();
        $choices     = [];

        foreach ($definitions as $definition) {
            $fields = [];
            foreach ($definition->getFields() as $field) {
                $fields[$field->getName()] = [
                    'label' => $field->getName(),
                    'type'  => $field->getType(),
                ];
            }

            if (count($fields)) {
                $choices['hub_objects_instances_'.$definition->getSlug()] = [
                    'label'   => $definition->getName(),
                    'filters' => $fields,
                ];
            }
        }

        if (count($choices)) {
            $event->addChoices('plugin.hubobjects', $choices);
        }
    }

    public function onSegmentQueryBuild(SegmentQueryEvent $event): void
    {
        $filter = $event->getFilter();

        if (str_starts_with($filter['table'] ?? '', 'hub_objects_instances_')) {
            $slug         = str_replace('hub_objects_instances_', '', $filter['table']);
            $definition   = $this->definitionModel->getRepository()->findOneBy(['slug' => $slug]);

            if ($definition) {
                $queryBuilder = $event->getQueryBuilder();
                $joinAlias    = 'hoi_'.uniqid(); // hub_objects_instances

                $queryBuilder->leftJoin(
                    'l',
                    $this->concatPrefix('hub_objects_instances'),
                    $joinAlias,
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('l.id', $joinAlias.'.contact_id'),
                        $queryBuilder->expr()->eq($joinAlias.'.object_definition_id', $definition->getId())
                    )
                );

                // IMPORTANT: This query logic is a placeholder.
                // Real-world filtering on a JSON column is database-specific (e.g., JSON_EXTRACT)
                // and complex to write without a live environment.
                $whereClause = "{$joinAlias}.properties LIKE '%\"{$filter['field']}\":\"{$filter['filter']}\"%'";
                $event->addWhere($whereClause);
            }
        }
    }

    private function concatPrefix(string $tableName): string
    {
        // This is a simplified way to get the prefix. In a real scenario, injecting the entity manager is better.
        return 'ma_';
    }
}
