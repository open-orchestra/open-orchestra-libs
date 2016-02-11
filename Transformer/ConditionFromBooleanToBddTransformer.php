<?php

namespace OpenOrchestra\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ConditionFromBooleanToBddTransformer
 */
abstract class ConditionFromBooleanToBddTransformer implements DataTransformerInterface
{
    protected $field = '';

    /**
     * @param string $field
     */
    public function __construct($field) {
        $this->field = $field;
    }
}
