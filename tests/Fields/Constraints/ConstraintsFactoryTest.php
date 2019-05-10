<?php
/**
 * Definition of ConstraintsFactoryTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields\Values\Constraints;

use FF\Forms\Fields\Constraints\ConstraintsFactory;
use PHPUnit\Framework\TestCase;

/**
 * Test ConstraintsFactoryTest
 *
 * @package FF\Tests
 */
class ConstraintsFactoryTest extends TestCase
{
    /**
     * Tests the namesake method/feature
     */
    public function testGetInstance()
    {
        $instanceA = ConstraintsFactory::getInstance();
        $instanceB = ConstraintsFactory::getInstance();
        $this->assertSame($instanceA, $instanceB);
    }
}
