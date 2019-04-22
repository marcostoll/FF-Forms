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

use FF\Services\AbstractFactory;

/**
 * Class ConstraintsFactory
 *
 * @package FF\Forms\Fields\Constraints
 */
class ConstraintsFactory extends AbstractFactory
{
    /**
     * Auto-prepends the Fast Forward Constraints namespace
     */
    public function __construct()
    {
        parent::__construct();
        $this->prependNamespaces(__NAMESPACE__);
    }

    /**
     * {@inheritdoc}
     * @return AbstractConstraint
     */
    public function create(string $localClassName, ...$args)
    {
        /** @var AbstractConstraint $constraint */
        $constraint = parent::create($localClassName, ...$args);
        return $constraint;
    }
}