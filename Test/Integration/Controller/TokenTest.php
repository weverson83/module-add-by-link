<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Integration\Controller;

class TokenTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDataFixture Magento/Quote/_files/is_salable_product.php
     * @magentoDataFixture ../../../../app/code/Weverson83/AddByLink/Test/Integration/_fixtures/product_links.php
     */
    public function testProcessActionRedirectsToCheckout()
    {
        $this->getRequest()
            ->setMethod(\Zend\Http\Request::METHOD_GET)
            ->setParam('token', '8ed8677e6c79e68b94e61658bd756ea5');

        $this->dispatch('add_by_link/token/process');

        $this->assertRedirect($this->stringContains('checkout/cart'));
    }

    public function testProcessActionWithInvalidToken()
    {
        $this->getRequest()->setParam('token', 'RANDOMSTRING');
        $this->getRequest()->setMethod(\Zend\Http\Request::METHOD_GET);

        $this->dispatch('add_by_link/token/process');

        $this->assertRedirect();
        $this->assertSessionMessages(
            $this->equalTo(
                [
                    'The token is invalid. Correct it and try again.'
                ]
            )
        );
    }
}
