<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities as EventPrioritiesAlias;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RenameHydraDataSubscriber implements EventSubscriberInterface
{
    public function updateData(ViewEvent $event)
    {
        $result = $event->getControllerResult();

        $data = json_decode($result, true);

        if (isset($data['@context'])) {
            unset($data['@context']);
        }

        if (isset($data['@id'])) {
            unset($data['@id']);
        }

        if (isset($data['@type'])) {
            unset($data['@type']);
        }

        if (isset($data['hydra:member'])) {
            $data['items'] = $data['hydra:member'];
            unset($data['hydra:member']);
        }
        if (isset($data['hydra:totalItems'])) {
            $data['total'] = $data['hydra:totalItems'];
            unset($data['hydra:totalItems']);
        }

        if (isset($data['hydra:view'])) {
            // Pagination
            if (isset($data['hydra:view']['hydra:last'])) {
                $data['firstPage'] = $data['hydra:view']['hydra:first'];
                $data['lastPage'] = $data['hydra:view']['hydra:last'];
                $data['nextPage'] = $data['hydra:view']['hydra:next'];
            }
            unset($data['hydra:view']);
        }

        if (isset($data['hydra:search'])) {
            unset($data['hydra:search']);
        }


        $event->setControllerResult(json_encode($data));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['updateData', EventPrioritiesAlias::POST_SERIALIZE]
        ];
    }
}