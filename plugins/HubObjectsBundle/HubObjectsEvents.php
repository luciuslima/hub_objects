<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle;

final class HubObjectsEvents
{
    /**
     * The event fired when a campaign condition is triggered.
     *
     * The event listener receives a Mautic\CampaignBundle\Event\CampaignExecutionEvent instance.
     *
     * @var string
     */
    public const ON_CAMPAIGN_TRIGGER_CONDITION = 'mautic.hubobjects.on_campaign_trigger_condition';
}
