<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use MauticPlugin\HubObjectsBundle\Form\Type\CampaignConditionType;
use MauticPlugin\HubObjectsBundle\HubObjectsEvents;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;
use MauticPlugin\HubObjectsBundle\Model\ObjectInstanceModel;

class CampaignSubscriber extends CommonSubscriber
{
    private ObjectDefinitionModel $definitionModel;
    private ObjectInstanceModel $instanceModel;

    public function __construct(ObjectDefinitionModel $definitionModel, ObjectInstanceModel $instanceModel)
    {
        $this->definitionModel = $definitionModel;
        $this->instanceModel   = $instanceModel;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD               => ['onCampaignBuild', 0],
            HubObjectsEvents::ON_CAMPAIGN_TRIGGER_CONDITION => ['onCampaignTriggerCondition', 0],
        ];
    }

    public function onCampaignBuild(CampaignBuilderEvent $event): void
    {
        $event->addCondition(
            'hubobjects.property_check',
            [
                'label'       => 'mautic.hubobjects.campaign.condition.property_check',
                'description' => 'mautic.hubobjects.campaign.condition.property_check.desc',
                'eventName'   => HubObjectsEvents::ON_CAMPAIGN_TRIGGER_CONDITION,
                'formType'    => CampaignConditionType::class,
            ]
        );
    }

    public function onCampaignTriggerCondition(CampaignExecutionEvent $event): void
    {
        $config = $event->getConfig();
        $formConfig = $config['form'] ?? [];

        $definitionId = $formConfig['object'] ?? null;
        $field        = $formConfig['field'] ?? null;
        $operator     = $formConfig['operator'] ?? null;
        $value        = $formConfig['value'] ?? null;

        if (!$definitionId || !$field || !$operator || $value === null) {
            $event->setResult(false);
            return;
        }

        $contact = $event->getLead();
        $definition = $this->definitionModel->getEntity($definitionId);

        if (!$definition) {
            $event->setResult(false);
            return;
        }

        // This is a simplified check. A full implementation would need a more robust query builder.
        $instances = $this->instanceModel->getRepository()->findBy(
            ['contact' => $contact, 'objectDefinition' => $definition],
            ['dateAdded' => 'DESC']
        );

        $found = false;
        foreach ($instances as $instance) {
            $properties = $instance->getProperties();
            if (isset($properties[$field])) {
                $actualValue = $properties[$field];
                if ($this->evaluateCondition($actualValue, $operator, $value)) {
                    $found = true;
                    break;
                }
            }
        }

        $event->setResult($found);
    }

    private function evaluateCondition($actualValue, string $operator, $conditionValue): bool
    {
        return match ($operator) {
            '=' => $actualValue == $conditionValue,
            '!=' => $actualValue != $conditionValue,
            '>' => $actualValue > $conditionValue,
            '<' => $actualValue < $conditionValue,
            'like' => str_contains((string) $actualValue, (string) $conditionValue),
            default => false,
        };
    }
}