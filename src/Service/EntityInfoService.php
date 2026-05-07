<?php

namespace Drupal\my_custom_module\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Сервис для получения информации о сущностях.
 */
class EntityInfoService {

  /**
   * Менеджер типов сущностей.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Конструктор сервиса.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Получает базовую информацию о сущности.
   *
   * @param string $entity_type
   *   Тип сущности (node, user, taxonomy_term, и т.д.)
   * @param int $entity_id
   *   ID сущности
   *
   * @return array
   *   Массив с информацией о сущности
   */
  public function getEntityInfo($entity_type, $entity_id) {
    try {
      // Загружаем сущность
      $entity = $this->entityTypeManager->getStorage($entity_type)->load($entity_id);
      
      // Если сущность не найдена
      if (!$entity) {
        return [
          'success' => FALSE,
          'message' => "Сутність типу '$entity_type' з ID '$entity_id' не знайдена",
          'entity_type' => $entity_type,
          'entity_id' => $entity_id,
        ];
      }
      
      // Базовая информация о сущности
      $info = [
        'success' => TRUE,
        'entity_type' => $entity_type,
        'entity_id' => $entity_id,
        'label' => $entity->label(), // Название сущности
        'bundle' => $entity->bundle(), // Тип подтип (например, article, page для нод)
        'created' => date('d.m.Y H:i:s', $entity->getCreatedTime()), // Дата создания
        'changed' => date('d.m.Y H:i:s', $entity->getChangedTime()), // Дата изменения
      ];
      
      // Дополнительная информация для разных типов сущностей
      
      // Для материалов (nodes)
      if ($entity_type == 'node') {
        $info['status'] = $entity->isPublished() ? 'Опубліковано' : 'Не опубліковано';
        $info['author'] = $entity->getOwner()->getAccountName();
        $info['author_id'] = $entity->getOwnerId();
      }
      
      // Для пользователей (users)
      if ($entity_type == 'user') {
        $info['email'] = $entity->getEmail();
        $info['roles'] = implode(', ', $entity->getRoles());
        $info['status'] = $entity->isActive() ? 'Активний' : 'Заблоковано';
      }
      
      // Для таксономии (taxonomy terms)
      if ($entity_type == 'taxonomy_term') {
        $info['description'] = $entity->getDescription();
        $info['parent'] = $entity->get('parent')->target_id ?? 'Нет родителя';
      }
      
      return $info;
      
    } catch (\Exception $e) {
      return [
        'success' => FALSE,
        'message' => 'Помилка: ' . $e->getMessage(),
        'entity_type' => $entity_type,
        'entity_id' => $entity_id,
      ];
    }
  }

}