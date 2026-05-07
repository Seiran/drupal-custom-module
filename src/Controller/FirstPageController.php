<?php

namespace Drupal\my_custom_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstPageController extends ControllerBase {

  public function content() {
    $current_date = date('d.m.Y H:i:s');
    
    return [
      '#markup' => '<h1>Hello, Drupal!</h1>' .
                   '<p>Current date and time: ' . $current_date . '</p>',
      '#cache' => [
        'max-age' => 0,  // Страница не кэшируется
      ],
    ];
  }

}