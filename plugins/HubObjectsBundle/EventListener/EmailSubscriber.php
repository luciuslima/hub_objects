<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\HubObjectsBundle\Model\OpportunityModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailSubscriber extends CommonSubscriber
{
    private OpportunityModel $opportunityModel;

    public function __construct(OpportunityModel $opportunityModel, UrlGeneratorInterface $router)
    {
        $this->opportunityModel = $opportunityModel;
        parent::__construct(null, null, null, $router);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvents::EMAIL_ON_BUILD => ['onEmailBuild', 0],
            EmailEvents::EMAIL_ON_SEND  => ['onEmailSend', 0],
        ];
    }

    public function onEmailBuild(EmailBuilderEvent $event): void
    {
        $tokens = [
            '{contact.opportunity.name}'   => $this->translator->trans('mautic.hubobjects.opportunity.name'),
            '{contact.opportunity.amount}' => $this->translator->trans('mautic.hubobjects.opportunity.amount'),
            '{contact.opportunity.stage}'  => $this->translator->trans('mautic.hubobjects.opportunity.stage'),
        ];
        $event->addTokenSection('hubobjects.opportunity', 'mautic.hubobjects.opportunity.plural', $tokens);
    }

    public function onEmailSend(EmailSendEvent $event): void
    {
        $content = $event->getContent();
        if (!$content) {
            return;
        }

        $contact = $event->getLead();
        if (!$contact) {
            return;
        }

        if (str_contains($content, '{contact.opportunity.')) {
            // Find the latest opportunity for the contact
            $opportunities = $this->opportunityModel->getRepository()->findBy(['contact' => $contact], ['dateAdded' => 'DESC'], 1);
            if (!empty($opportunities)) {
                $opportunity = $opportunities[0];
                $tokens = [
                    '{contact.opportunity.name}'   => $opportunity->getName(),
                    '{contact.opportunity.amount}' => $opportunity->getAmount(),
                    '{contact.opportunity.stage}'  => $opportunity->getStage(),
                ];
                $content = str_replace(array_keys($tokens), array_values($tokens), $content);
            }
        }

        $event->setContent($content);
    }
}
