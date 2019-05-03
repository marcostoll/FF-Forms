<?php
/**
 * Definition of FieldsFactory
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields;

use FF\Utilities\Factories\AbstractFactory;

/**
 * Class FieldsFactory
 *
 * @package FF\Forms\Fields
 */
class FieldsFactory extends AbstractFactory
{
    /**
     * Auto-prepends the Fast Forward Fields namespace
     */
    public function __construct()
    {
        $this->prependNamespaces(__NAMESPACE__);
    }

    /**
     * {@inheritdoc}
     * @return AbstractField
     */
    public function create(string $localClassName, ...$args)
    {
        /** @var AbstractField $field */
        $field = parent::create($localClassName, ...$args);
        return $field;
    }
}