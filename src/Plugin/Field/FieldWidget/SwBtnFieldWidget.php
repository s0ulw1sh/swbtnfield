<?php

namespace Drupal\swbtnfield\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

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
                2 => t('Target blank URL'),
                3 => t('JavaScript expression'),
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
            '#title'            => $this->t('URL or JavaScript expression'),
            '#default_value'    => isset($items[$delta]->scurl) ? $items[$delta]->scurl : '',
            '#element_validate' => [ [$this, 'validate_scurl'] ],
            '#required'         => $element['#required'],
            '#required'         => TRUE,
        ];

        return $element;
    }

    public function validate_scurl($element, FormStateInterface $form_state) {
        $url = $element['#value'];
        $uri = NULL;

        $vals = $form_state->getValues();

        $p0 = $element['#parents'][0];
        $p1 = $element['#parents'][1];

        if (intval($vals[$p0][$p1]['mode']) === 3) {
            $form_state->setValueForElement($element, $url);
            return;
        }

        if (strpos($url, '/') === 0 || strpos($url, '#') === 0 || strpos($url, '?') === 0) {
            $url = 'internal:'.$url;
        } else if (strpos($url, '<') === 0) {
            $url = 'route:'.$url;
        }

        try {
            $uri = Url::fromUri($url);
        } catch (\Exception $e) {
            $form_state->setError($element, $e->getMessage());
            return;
        }
        
        $form_state->setValueForElement($element, $uri->toUriString());
    }
}
