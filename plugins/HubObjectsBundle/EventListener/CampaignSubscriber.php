<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use MauticPlugin\HubObjectsBundle\HubObjectsEvents;
use MauticPlugin\HubObjectsBundle\Model\OpportunityModel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CampaignSubscriber extends CommonSubscriber
{
    private OpportunityModel $opportunityModel;

    public function __construct(OpportunityModel $opportunityModel)
    {
        $this->opportunityModel = $opportunityModel;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD => ['onCampaignBuild', 0],
            HubObjectsEvents::ON_CAMPAIGN_TRIGGER_CONDITION => ['onCampaignTriggerCondition', 0],
        ];
    }

    public function onCampaignBuild(CampaignBuilderEvent $event): void
    {
        $event->addCondition(
            'hubobjects.opportunity_stage',
            [
                'label'           => 'mautic.hubobjects.campaign.condition.opportunity_stage',
                'description'     => 'mautic.hubobjects.campaign.condition.opportunity_stage.desc',
                'eventName'       => HubObjectsEvents::ON_CAMPAIGN_TRIGGER_CONDITION,
                'formType'        => ChoiceType::class,
                'formTypeOptions' => [
                    'choices' => [
                        'Prospecting' => 'prospecting',
                        'Qualification' => 'qualification',
                        'Proposal' => 'proposal',
                        'Negotiation' => 'negotiation',
                        'Closed Won' => 'closed_won',
                        'Closed Lost' => 'closed_lost',
                    ],
                ],
            ]
        );
    }

    public function onCampaignTriggerCondition(CampaignExecutionEvent $event): void
    {
        $contact = $event->getLead();
        $config  = $event->getConfig();
        $stage   = $config['form']['stage'] ?? null;

        if (!$stage) {
            $event->setResult(false);
            return;
        }

        $opportunities = $this->opportunityModel->getRepository()->findBy(['contact' => $contact]);
        $found = false;
        foreach ($opportunities as $opportunity) {
            if ($opportunity->getStage() === $stage) {
                $found = true;
                break;
            }
        }

        $event->setResult($found);
    }
}
