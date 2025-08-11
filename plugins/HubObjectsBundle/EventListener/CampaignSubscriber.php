<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use MauticPlugin\HubObjectsBundle\HubObjectsEvents;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;
use MauticPlugin\HubObjectsBundle\Model\ObjectInstanceModel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
        // This is a proof-of-concept for a condition. A fully dynamic UI would be more complex.
        // This condition checks the 'stage' of an object with the slug 'oportunidades'.
        $event->addCondition(
            'hubobjects.opportunity_stage_check',
            [
                'label'           => 'mautic.hubobjects.campaign.condition.opportunity_stage',
                'description'     => 'mautic.hubobjects.campaign.condition.opportunity_stage.desc',
                'eventName'       => HubObjectsEvents::ON_CAMPAIGN_TRIGGER_CONDITION,
                'formType'        => ChoiceType::class,
                'formTypeOptions' => [
                    'choices' => [
                        'Prospecting'   => 'prospecting',
                        'Qualification' => 'qualification',
                        'Proposal'      => 'proposal',
                        'Negotiation'   => 'negotiation',
                        'Closed Won'    => 'closed_won',
                        'Closed Lost'   => 'closed_lost',
                    ],
                ],
            ]
        );
    }

    public function onCampaignTriggerCondition(CampaignExecutionEvent $event): void
    {
        $contact    = $event->getLead();
        $config     = $event->getConfig();
        $checkStage = $config['form']['stage'] ?? null;

        if (!$checkStage) {
            $event->setResult(false);
            return;
        }

        // Find the 'oportunidades' object definition
        $definition = $this->definitionModel->getRepository()->findOneBy(['slug' => 'oportunidades']);
        if (!$definition) {
            $event->setResult(false);
            return;
        }

        // Find the latest instance of this object for the contact
        $instances = $this->instanceModel->getRepository()->findBy(
            ['contact' => $contact, 'objectDefinition' => $definition],
            ['dateAdded' => 'DESC'],
            1
        );

        if (empty($instances)) {
            $event->setResult(false);
            return;
        }

        $properties  = $instances[0]->getProperties();
        $actualStage = $properties['stage'] ?? null;

        $event->setResult($actualStage === $checkStage);
    }
}
