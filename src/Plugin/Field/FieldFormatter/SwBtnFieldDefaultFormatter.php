<?php

namespace Drupal\swbtnfield\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Url;
use Drupal\Component\Serialization\Json;

/** *
 * @FieldFormatter(
 *   id = "swbtnfield_formatter",
 *   label = @Translation("Button field"),
 *   field_types = {
 *     "swbtnfield"
 *   }
 * )
 */
class SwBtnFieldDefaultFormatter extends FormatterBase {

    public function viewElements(FieldItemListInterface $items, $langcode) {  
        $element = [];

        foreach ($items as $delta => $item) {
            # Выводим наши элементы.

            $url  = $item->scurl;
            $mode = intval($item->mode);

            if ($mode === 3) {

                $element[$delta] = [
                    '#type'      => 'html_tag',
                    '#tag'       => 'button',
                    '#value'     => $this->t($item->caption),
                    '#attributes' => [
                        'class'   => ['button', 'swbtnfield'],
                        'onclick' => $url,
                        'type'    => 'button',
                    ]
                ];

            } else {

                $element[$delta] = [
                    '#type'      => 'link',
                    '#title'     => $this->t($item->caption),
                    '#url'       => Url::fromUri($url),
                    '#attributes' => [
                        'class' => ['button', 'swbtnfield'],
                    ]
                ];

                if ($mode === 1) {
                    $element[$delta]['#attributes']['class'][] = 'use-ajax';
                    $element[$delta]['#attributes'] += [
                        'data-dialog-type'    => 'modal',
                        'data-dialog-options' => Json::encode([
                            'width' => 700,
                        ]),
                    ];
                }

                if ($mode === 2) {
                    $element[$delta]['#attributes'] += [
                        'target' => '_blank'
                    ];
                }
            }
        }

        return $element;
    }

}