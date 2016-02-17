<?php

namespace OpenOrchestra\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ConditionFromBooleanToBddTransformer
 */
interface ConditionFromBooleanToBddTransformerInterface extends DataTransformerInterface
{
    /**
     * @param string $field
     */
    public function setField($field);
}
