<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities as EventPrioritiesAlias;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RenameHydraDataSubscriber implements EventSubscriberInterface
{
    const KEYS_TO_REMOVE = [
        '@context', '@id', '@type', 'hydra:search'
    ];

    public function updateData(ViewEvent $event)
    {
        $result = $event->getControllerResult();

        $data = json_decode($result, true);

        foreach (self::KEYS_TO_REMOVE as $key) {
            if (isset($data[$key])) {
                unset($data[$key]);
            }
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


        $event->setControllerResult(json_encode($data));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['updateData', EventPrioritiesAlias::POST_SERIALIZE]
        ];
    }
}