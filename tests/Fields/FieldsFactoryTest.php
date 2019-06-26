<?php
/**
 * Definition of FieldsFactoryTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Forms\Fields;

use FF\Forms\Fields\FieldsFactory;
use PHPUnit\Framework\TestCase;

/**
 * Test FileFieldTest
 *
 * @package FF\Tests
 */
class FieldsFactoryTest extends TestCase
{
    /**
     * Tests the namesake method/feature
     */
    public function testGetInstance()
    {
        $instanceA = FieldsFactory::getInstance();
        $instanceB = FieldsFactory::getInstance();
        $this->assertSame($instanceA, $instanceB);
    }
}
