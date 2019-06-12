<?php
/**
 * Definition of ConstraintsFactory
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Forms\Fields\Constraints;

use FF\Factories\AbstractFactory;
use FF\Factories\ClassLocators\NamespaceClassLocator;

/**
 * Class ConstraintsFactory
 *
 * @package FF\Forms\Fields\Constraints
 */
class ConstraintsFactory extends AbstractFactory
{
    /**
     * @var ConstraintsFactory
     */
    protected static $instance;

    /**
     * Declared protected to prevent external usage
     * Uses a NamespaceClassLocator pre-configured with the FF\Forms\Fields\Constraints namespace.
     * @see FF\Factories\ClassLocators\NamespaceClassLocator
     */
    protected function __construct()
    {
        parent::__construct(new NamespaceClassLocator(__NAMESPACE__));
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
     * @return ConstraintsFactory
     */
    public static function getInstance(): ConstraintsFactory
    {
        if (is_null(self::$instance)) {
            self::$instance = new ConstraintsFactory();
        }

        return self::$instance;
    }

    /**
     * {@inheritdoc}
     * @return AbstractConstraint
     */
    public function create(string $classIdentifier, ...$args)
    {
        /** @var AbstractConstraint $constraint */
        $constraint = parent::create($classIdentifier, ...$args);
        return $constraint;
    }
}