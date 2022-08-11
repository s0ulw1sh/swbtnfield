<?php

namespace Drupal\swbtnfield\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldWidget(
 *   id = "swbtnfield_widget",
 *   module = "swbtnfield",
 *   label = @Translation("Button Field Witdget"),
 *   field_types = {
 *     "swbtnfield"
 *   }
 * )
 */
class SwBtnFieldWidget extends WidgetBase {
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

        $element += [
            '#type'             => 'details',
            '#title'            => $element['#title'],
            '#description'      => $element['#description'],
            '#weight'           => $element['#weight'],
        ];

        $element['mode'] = [
            '#type'             => 'select',
            '#title'            => $this->t('Mode'),
            '#options'          => [
                0 => t('URL'),
                1 => t('Popup URL'),
            ],
            '#default_value'    => isset($items[$delta]->mode) ? $items[$delta]->mode : NULL,
            '#required'         => TRUE,
        ];

        $element['caption'] = [
            '#type'             => 'textfield',
            '#title'            => $this->t('Caption'),
            '#default_value'    => isset($items[$delta]->caption) ? $items[$delta]->caption : '',
            '#required'         => TRUE,
        ];

        $element['scurl'] = [
            '#type'             => 'textfield',
            '#title'            => $this->t('Button URL or Action'),
            '#default_value'    => isset($items[$delta]->scurl) ? $items[$delta]->scurl : '',
            '#element_validate' => [ [$this, 'validate_scurl'] ],
            '#required'         => $element['#required'],
        ];

        return $element;
    }

    public function validate_scurl($element, FormStateInterface $form_state) {
        $url = $element['#value'];

        $valid = strpos($url, '/') === 0 || strpos($url, '#') === 0 || strpos($url, '?') === 0;
        $valid = $valid || strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0 || strpos($url, 'route:') === 0;

        if ($valid === FALSE) {
            $form_state->setError($element, $this->t("Invalid URL"));
        }
    }
}