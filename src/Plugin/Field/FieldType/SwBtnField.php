<?php

namespace Drupal\swbtnfield\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * @FieldType(
 *   id = "swbtnfield",
 *   label = @Translation("Button Field"),
 *   module = "swbtnfield",
 *   description = @Translation("Create button on field"),
 *   category = @Translation("Elements"),
 *   default_widget = "swbtnfield_widget",
 *   default_formatter = "swbtnfield_formatter"
 * )
 */
class SwBtnField extends FieldItemBase {
    public static function schema(FieldStorageDefinitionInterface $field_definition) {
        return [
            'columns' => [
                'mode' => [
                    'type'     => 'int',
                    'size'     => 'tiny',
                    'unsigned' => TRUE,
                    'not null' => FALSE,
                    'default'  => 0,
                ],
                'caption' => [
                    'type'     => 'varchar',
                    'size'     => 'normal',
                    'length'   => 64,
                    'not null' => FALSE,
                ],
                'scurl' => [
                    'type'     => 'varchar',
                    'size'     => 'normal',
                    'length'   => 256,
                    'not null' => FALSE,
                ],
            ],
        ];
    }

    public function isEmpty() {
        $scurl = $this->get('scurl')->getValue();
        return $scurl === NULL || $scurl === '';
    }

    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
        $properties = [];
        $properties['mode']    = DataDefinition::create('integer')->setLabel(t('Mode'));
        $properties['caption'] = DataDefinition::create('string')->setLabel(t('Caption'));
        $properties['scurl']   = DataDefinition::create('string')->setLabel(t('URL or Action'));
        return $properties;
    }
}