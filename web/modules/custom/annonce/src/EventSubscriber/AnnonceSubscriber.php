<?php

namespace Drupal\annonce\EventSubscriber;

use Drupal\Component\Datetime\Time;
use Drupal\Core\Routing\ResettableStackedRouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Class AnnonceSubscriber.
 */
class AnnonceSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\Core\Messenger\MessengerInterface definition.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;
  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

    /**
     * @var
     */
    protected $currentRouteMatch;

    protected  $database;

    protected $time;

  /**
   * Constructs a new AnnonceSubscriber object.
   */
  public function __construct(MessengerInterface $messenger, AccountProxyInterface $current_user, ResettableStackedRouteMatchInterface $currentRouteMatch, \Drupal\Core\Database\Connection $database, Time $time) {
    $this->messenger = $messenger;
    $this->currentUser = $current_user;
    $this->currentRouteMatch = $currentRouteMatch;
    $this->database = $database;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events['kernel.request'] = ['request'];

    return $events;
  }

    /**
     * This method is called whenever the kernel.request event is
     * dispatched.
     *
     * @param GetResponseEvent $event
     * @throws \Exception
     */
  public function request(Event $event) {
    $this->messenger->addMessage(t('Event for'.$this->currentUser->getDisplayName()),'status');

    if ($this->currentRouteMatch->getRouteName() == 'entity.annonce.canonical'){
        $this->messenger->addMessage(t('Entité annonce'));

        $this->database->insert('annonce_history')
                        ->fields([
                            'uid' => $this->currentUser->id(),
                            'aid'=> $this->currentRouteMatch->getParameter('annonce')->id(),
                            'date' => $this->time->getCurrentTime(),
                    ])
                ->execute();
    }

  }

}
