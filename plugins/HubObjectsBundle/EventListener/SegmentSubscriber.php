<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\SegmentBundle\Event\SegmentBuilderEvent;
use Mautic\SegmentBundle\Event\SegmentQueryEvent;
use Mautic\SegmentBundle\SegmentEvents;

class SegmentSubscriber extends CommonSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            SegmentEvents::SEGMENT_ON_BUILD       => ['onSegmentBuild', 0],
            SegmentEvents::SEGMENT_QUERY_ON_BUILD => ['onSegmentQueryBuild', 0],
        ];
    }

    public function onSegmentBuild(SegmentBuilderEvent $event): void
    {
        // Add Opportunities
        $opportunityFilters = [
            'amount' => [
                'label' => 'mautic.hubobjects.opportunity.amount',
                'type'  => 'number',
            ],
            'stage' => [
                'label' => 'mautic.hubobjects.opportunity.stage',
                'type'  => 'text',
            ],
        ];
        $event->addCompanyFilter('hub_objects_opportunities', $opportunityFilters);
    }

    public function onSegmentQueryBuild(SegmentQueryEvent $event): void
    {
        $filter = $event->getFilter();

        if (isset($filter['table']) && 'hub_objects_opportunities' === $filter['table']) {
            $queryBuilder = $event->getQueryBuilder();
            $joinAlias    = 'hoo_'.uniqid();

            $queryBuilder->leftJoin(
                'l',
                $this->concatPrefix('hub_objects_opportunities'),
                $joinAlias,
                $queryBuilder->expr()->eq('l.id', $joinAlias.'.contact_id')
            );

            $event->addWhere(
                $queryBuilder->expr()->eq($joinAlias.'.'.$filter['field'], $queryBuilder->expr()->literal($filter['filter']))
            );
        }
    }

    private function concatPrefix(string $tableName): string
    {
        return $this->em->getConfiguration()->getTablePrefix().$tableName;
    }
}
