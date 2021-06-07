<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Integration\Model;

use Magento\Framework\DataObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    /**
     * @param string $property
     * @param $value
     * @dataProvider gettersAndSettersProvider
     */
    public final function testGettersAndSetters(string $property, $value)
    {
        $camelCasedMethod = $this->camelCase($property);
        $setter = 'set' . $camelCasedMethod;
        $getter = 'get' . $camelCasedMethod;

        $this->getModel()->$setter($value);
        $this->assertEquals($value, $this->model->$getter());
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    abstract protected function getModel(): DataObject;

    /**
     * @return array[]
     */
    abstract public function gettersAndSettersProvider(): array;

    /**
     * Convert strings to camel case
     *
     * @param string $string
     * @return string
     */
    protected final function camelCase(string $string): string
    {
        return preg_replace_callback(
            '/(?:^|_)(.?)/',
            function($str) {
                return strtoupper($str[1]);
            },
            $string);
    }
}
