<?php
namespace Drupal\recycle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\recycle\Service\Batterypack as BatterypackService;

class BatterypackController extends ControllerBase {
  /**
   * @var BatterypackService
   */
  protected $service;

  /**
   * @param BatterypackService $service
   */
  public function __construct(BatterypackService $service) {
    $this->service = $service;
  }

  /**
   * @param ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('recycle.batterypack'));
  }

  /**
   * @return array
   */
  public function indexAction() {
    $addNewUrl = new Url('recycle.form');

    return array(
      '#theme' => 'recycle_index',
      '#batterypacks' => $this->service->getAllGroupedByType(),
      '#add_new_url' => $addNewUrl->toString(),
    );
  }
}
