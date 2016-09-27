<?php

namespace OpenOrchestra\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
@trigger_error('The '.__NAMESPACE__.'\ConditionFromBooleanToBddTransformerInterface class is deprecated since version 1.2.0 and will be removed in 2.0', E_USER_DEPRECATED);

/**
 * Class ConditionFromBooleanToBddTransformer
 *
 * @deprecated will be removed in 2.0
 */
interface ConditionFromBooleanToBddTransformerInterface extends DataTransformerInterface
{
    /**
     * @param string $field
     */
    public function setField($field);
}
