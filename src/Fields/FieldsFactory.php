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
     * @var FieldsFactory
     */
    protected static $instance;

    /**
     * Declared protected to prevent external usage
     * Auto-prepends the FF\Forms\Fields namespace
     */
    protected function __construct()
    {
        $this->prependNamespaces(__NAMESPACE__);
    }

    /**
     * Declared protected to prevent external usage
     */
    protected function __clone()
    {

    }

    /**
     * Retrieves the singleton instance of this class
     *
     * @return FieldsFactory
     */
    public static function getInstance(): FieldsFactory
    {
        if (is_null(self::$instance)) {
            self::$instance = new FieldsFactory();
        }

        return self::$instance;
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