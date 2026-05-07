<?php

namespace Drupal\my_custom_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Block\Annotation\Block;

/**
 * Provides a block with current route name.
 *
 * @Block(
 *   id = "current_route_block",
 *   admin_label = @Translation("Current Route Block"),
 *   category = @Translation("Custom")
 * )
 */
class CurrentRouteBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current route match service.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * Constructs a new CurrentRouteBlock instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $current_route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get current route name
    $route_name = $this->currentRouteMatch->getRouteName();
    
    if (!$route_name) {
      $route_name = 'No route found';
    }
    
    $output = '<strong>Current route:</strong> ' . $route_name;
    
    return [
      '#markup' => $output,
      '#cache' => [
        'contexts' => ['route'],
        'max-age' => Cache::PERMANENT,
      ],
    ];
  }

}