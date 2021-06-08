<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Unit\Model\Link;

use Magento\Framework\Intl\DateTimeFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Model\Link\Validation;

class ValidationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    /**
     * @var Validation
     */
    protected $validate;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->validate = $this->objectManager->getObject(Validation::class, [
            'dateTimeFactory' => $this->objectManager->getObject(DateTimeFactory::class)
        ]);
    }

    public function testValidToken()
    {
        $link = $this->getMockForAbstractClass(LinkInterface::class);
        $link->method('getToken')
            ->willReturn('RANDOMSTRING');
        $link->method('getTokenCreatedAt')
            ->willReturn(date('Y-m-d H:i:s', strtotime('now -1 hour')));
        $link->method('getExpirationPeriod')
            ->willReturn(2);

        $this->assertTrue($this->validate->execute($link));
    }

    public function testEmptyTokenShouldThrowException()
    {
        $this->expectException(\Magento\Framework\Exception\InputException::class);
        $link = $this->getMockForAbstractClass(LinkInterface::class);
        $link->method('getToken')
            ->willReturn('');

        $this->validate->execute($link);
    }

    public function testEmptyCreatedAtShouldThrowException()
    {
        $this->expectException(\Magento\Framework\Exception\State\ExpiredException::class);
        $link = $this->getMockForAbstractClass(LinkInterface::class);
        $link->method('getToken')
            ->willReturn('RANDOMSTRING');
        $link->method('getTokenCreatedAt')
            ->willReturn('');

        $this->validate->execute($link);
    }

    public function testExpiredTokenThrowsException()
    {
        $this->expectException(\Magento\Framework\Exception\State\ExpiredException::class);
        $link = $this->getMockForAbstractClass(LinkInterface::class);
        $link->method('getToken')
            ->willReturn('RANDOMSTRING');
        $link->method('getTokenCreatedAt')
            ->willReturn(date('Y-m-d H:i:s', strtotime('now -3 hour')));
        $link->method('getExpirationPeriod')
            ->willReturn(2);

        $this->validate->execute($link);
    }
}
