<?php

namespace Drupal\annonce\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\Context\ContextDefinition;

/**
* Provides a 'Date condition' condition to enable a condition based in module selected status.
*
* @Condition(
*   id = "date_condition",
*   label = @Translation("Date condition"),
*
* )
*
*/
class DateCondition extends ConditionPluginBase {

/**
* {@inheritdoc}
*/
public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
{
    return new static(
    $configuration,
    $plugin_id,
    $plugin_definition
    );
}

/**
 * Creates a new DateCondition object.
 *
 * @param array $configuration
 *   The plugin configuration, i.e. an array with configuration values keyed
 *   by configuration option name. The special key 'context' may be used to
 *   initialize the defined contexts by setting it to an array of context
 *   values keyed by context names.
 * @param string $plugin_id
 *   The plugin_id for the plugin instance.
 * @param mixed $plugin_definition
 *   The plugin implementation definition.
 */
 public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
 }

 /**
   * {@inheritdoc}
   */
 public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
     // Sort all modules by their names.
//     $modules = system_rebuild_module_data();
//     uasort($modules, 'system_sort_modules_by_info_name');
//
//     $options = [NULL => t('Select a module')];
//     foreach($modules as $module_id => $module) {
//         $options[$module_id] = $module->info['name'];
//     }

//     $form['module'] = [
//         '#type' => 'select',
//         '#title' => $this->t('Select a module to validate'),
//         '#default_value' => $this->configuration['module'],
//         '#options' => $options,
//         '#description' => $this->t('Module selected status will be use to evaluate condition.'),
//     ];

     $form['date_debut'] = [
         '#type' => 'date',
         '#title' => $this->t('Date début'),
         '#default_value' => $this->configuration['date_debut'],
     ];

     $form['date_fin'] = [
         '#type' => 'date',
         '#title' => $this->t('Date fin'),
         '#default_value' => $this->configuration['date_fin']
     ];

     return parent::buildConfigurationForm($form, $form_state);
 }

/**
 * {@inheritdoc}
 */
 public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
     ($this->configuration['date_debut'] = $form_state->getValue('date_debut'));
     $this->configuration['date_fin'] = $form_state->getValue('date_fin');
     parent::submitConfigurationForm($form, $form_state);
 }

/**
 * {@inheritdoc}
 */
 public function defaultConfiguration() {
    return ['module' => ''] + parent::defaultConfiguration();
 }

/**
  * Evaluates the condition and returns TRUE or FALSE accordingly.
  *
  * @return bool
  *   TRUE if the condition has been met, FALSE otherwise.
  */
  public function evaluate() {
//      if (empty($this->configuration['module']) && !$this->isNegated()) {
//          return TRUE;
//      }
      if (empty($this->configuration['date_fin'])) return TRUE;

      if ($this->configuration['date_fin'] < \Drupal::service('date.formatter')->format(\Drupal::service('datetime.time')->getCurrentTime(), 'custom', 'Y-m-d')) {
          return FALSE;
      }else{
          return TRUE;
      }

//      $module = $this->configuration['module'];
//      $modules = system_rebuild_module_data();
//
//      return $modules[$module]->status;
  }

/**
 * Provides a human readable summary of the condition's configuration.
 */
 public function summary()
 {
//     $module = $this->getContextValue('module');
//     $modules = system_rebuild_module_data();
//
//     $status = ($modules[$module]->status)?t('enabled'):t('disabled');
//
//     return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);

     return $this->t('Date visibilty condition');
 }

}
