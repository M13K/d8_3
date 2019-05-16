<?php

namespace Drupal\sad_hill\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class SadHillSubscriber.
 */
class SadHillSubscriber implements EventSubscriberInterface {


  /**
   * Constructs a new SadHillSubscriber object.
   */
  public function __construct() {

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
   */
  public function request(Event $event) {
    drupal_set_message('Event kernel.request thrown by Subscriber in module sad_hill: the world is divided into 2 categories...Those who have the gun loaded and those who dig...');
  }

}
