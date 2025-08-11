<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;
use MauticPlugin\HubObjectsBundle\Model\ObjectInstanceModel;

class EmailSubscriber extends CommonSubscriber
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
            EmailEvents::EMAIL_ON_BUILD => ['onEmailBuild', 0],
            EmailEvents::EMAIL_ON_SEND  => ['onEmailSend', 0],
        ];
    }

    public function onEmailBuild(EmailBuilderEvent $event): void
    {
        $definitions = $this->definitionModel->getRepository()->findAll();
        $allTokens   = [];

        foreach ($definitions as $definition) {
            foreach ($definition->getFields() as $field) {
                $token = sprintf('{contact.hubobject.%s.%s}', $definition->getSlug(), $field->getName());
                $allTokens[$token] = $field->getName();
            }
        }

        if (!empty($allTokens)) {
            $event->addTokenSection('hubobjects', 'mautic.hubobjects.objects', $allTokens);
        }
    }

    public function onEmailSend(EmailSendEvent $event): void
    {
        $content = $event->getContent();
        if (!$content || !str_contains($content, '{contact.hubobject.')) {
            return;
        }

        $contact = $event->getLead();
        if (!$contact) {
            return;
        }

        preg_match_all('/{contact\.hubobject\.([^}]+)\.([^}]+)}/', $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return;
        }

        $replacements = [];
        $fetchedObjects = [];

        foreach ($matches as $match) {
            $token      = $match[0];
            $objectSlug = $match[1];
            $fieldName  = $match[2];

            if (!isset($fetchedObjects[$objectSlug])) {
                $definition = $this->definitionModel->getRepository()->findOneBy(['slug' => $objectSlug]);
                if ($definition) {
                    $instances = $this->instanceModel->getRepository()->findBy(
                        ['contact' => $contact, 'objectDefinition' => $definition],
                        ['dateAdded' => 'DESC'],
                        1
                    );
                    $fetchedObjects[$objectSlug] = !empty($instances) ? $instances[0] : null;
                } else {
                    $fetchedObjects[$objectSlug] = null;
                }
            }

            $instance = $fetchedObjects[$objectSlug];
            if ($instance) {
                $properties = $instance->getProperties();
                $replacements[$token] = $properties[$fieldName] ?? '';
            } else {
                $replacements[$token] = ''; // Replace with blank if no instance is found
            }
        }

        $content = str_replace(array_keys($replacements), array_values($replacements), $content);
        $event->setContent($content);
    }
}
