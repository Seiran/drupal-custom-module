<?php

namespace Drupal\my_custom_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class SimpleForm extends FormBase {

  public function getFormId() {
    return 'my_custom_module_simple_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['text_field'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Введіть текст'),
      '#required' => TRUE,
      '#size' => 60,
      '#maxlength' => 255,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Відправити'),
      '#ajax' => [
        'callback' => '::ajaxSubmit',
        'wrapper' => 'result-wrapper',
        'progress' => ['type' => 'throbber'],
      ],
    ];

    $form['result_wrapper'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'result-wrapper'],
    ];

    return $form;
  }

  public function ajaxSubmit(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    
    $text = $form_state->getValue('text_field');

    if (!empty($text)) {
      $markup = '<div class="success" style="margin-top: 20px; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px;">
                  Результат: ' . htmlspecialchars($text) . '
                 </div>';
      
      // Очищаем поле после успешной отправки
      $form_state->setValue('text_field', '');
    } else {
      $markup = '<div class="error" style="margin-top: 20px; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px;">
                  Введіть текст
                 </div>';
    }
    
    $response->addCommand(new HtmlCommand('#result-wrapper', $markup));
    
    // Перестраиваем форму, чтобы очистить поле
    $form_state->setRebuild();
    
    return $response;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Этот метод не вызывается при AJAX запросе
    // Оставляем для обычной отправки (без JS)
    $text = $form_state->getValue('text_field');
    if (!empty($text)) {
      \Drupal::messenger()->addMessage($this->t('Результат: @text', ['@text' => $text]));
    } else {
      \Drupal::messenger()->addMessage($this->t('Введіть текст!'), 'error');
    }
  }

}