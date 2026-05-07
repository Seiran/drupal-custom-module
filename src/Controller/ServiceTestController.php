<?php

namespace Drupal\my_custom_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\my_custom_module\Service\EntityInfoService;

/**
 * Контроллер для тестирования сервиса.
 */
class ServiceTestController extends ControllerBase {

  /**
   * Сервис информации о сущностях.
   *
   * @var \Drupal\my_custom_module\Service\EntityInfoService
   */
  protected $entityInfoService;

  /**
   * Конструктор контроллера.
   */
  public function __construct(EntityInfoService $entity_info_service) {
    $this->entityInfoService = $entity_info_service;
  }

  /**
   * Создание экземпляра контроллера.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('my_custom_module.entity_info_service')
    );
  }

  /**
   * Вывод информации о сущности.
   */
  public function content($entity_type, $entity_id) {
    // Получаем информацию о сущности через сервис
    $info = $this->entityInfoService->getEntityInfo($entity_type, $entity_id);
    
    $output = '<h2>Інформація про сутність</h2>';
    $output .= '<table border="1" cellpadding="10" style="border-collapse: collapse;">';
    
    foreach ($info as $key => $value) {
      if (is_array($value)) {
        $value = implode(', ', $value);
      }
      $output .= '<tr>';
      $output .= '<th style="text-align: left;">' . ucfirst($key) . '</th>';
      $output .= '<td>' . $value . '</td>';
      $output .= '</tr>';
    }
    
    $output .= '</table>';
    
    // Ссылки для тестирования
    $output .= '<h3>Тестування інших сутностей:</h3>';
    $output .= '<ul>';
    $output .= '<li><a href="/test-service/node/1">Матеріал #1</a></li>';
    $output .= '<li><a href="/test-service/node/2">Матеріал #2</a></li>';
    $output .= '<li><a href="/test-service/user/1">Користувач #1</a></li>';
    $output .= '</ul>';
    
    return [
      '#markup' => $output,
      '#cache' => ['max-age' => 0], // Не кэшируем для теста
    ];
  }

}