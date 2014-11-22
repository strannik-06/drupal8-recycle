<?php
namespace Drupal\recycle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\recycle\Service\Batterypack as BatterypackService;

class BatterypackForm extends FormBase {
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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('recycle.batterypack'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'recycle_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['intro'] = array(
      '#markup' => '<p>' . t('<a href="@url">List of results</a>',
          array('@url' => $this->getStatisticsUrl()->toString())) . '</p>',
    );
    $form['type'] = array(
      '#type' => 'textfield',
      '#title' => t('Type'),
      '#required' => TRUE,
    );
    $form['amount'] = array(
      '#type' => 'number',
      '#title' => t('Amount'),
      '#default_value' => 1,
      '#required' => TRUE,
    );
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('amount') < 1) {
      $form_state->setErrorByName('amount', t('Amount needs to be more or equal 1'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entry = array(
      'id' => $form_state->getValue('id'),
      'type' => $form_state->getValue('type'),
      'amount' => $form_state->getValue('amount'),
      'name' => $form_state->getValue('name'),
    );

    $this->service->insert($entry);
    drupal_set_message(t('Added new batterypack'));

    $form_state->setRedirectUrl($this->getStatisticsUrl());
  }

  /**
   * @return Url
   */
  protected function getStatisticsUrl() {
    return new Url('recycle.statistics');
  }
}
