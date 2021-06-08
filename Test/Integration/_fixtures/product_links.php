<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$productRepository = $objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$product = $productRepository->getById(99);

/** @var \Weverson83\AddByLink\Model\Link $link */
$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setToken('8ed8677e6c79e68b94e61658bd756ea5')
    ->setTokenCreatedAt(date('Y-m-d H:i:s', strtotime('now -1 hour')))
    ->setExpirationPeriod(2);
$link->isObjectNew(true);
$link->save();

/** @var \Weverson83\AddByLink\Model\LinkProduct $linkProduct */
$linkProduct = $objectManager->create(\Weverson83\AddByLink\Model\LinkProduct::class);
$linkProduct->setLinkId($link->getId());
$linkProduct->setProductId($product->getId());
$linkProduct->isObjectNew(true);
$linkProduct->save();


$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setToken('8ed8677e6c79e68b94e61658bd756ea6')
    ->setTokenCreatedAt(date('Y-m-d H:i:s', strtotime('now -3 hour')))
    ->setExpirationPeriod(2);
$link->isObjectNew(true);
$link->save();

/** @var \Weverson83\AddByLink\Model\LinkProduct $linkProduct */
$linkProduct = $objectManager->create(\Weverson83\AddByLink\Model\LinkProduct::class);
$linkProduct->setLinkId($link->getId());
$linkProduct->setProductId($product->getId());
$linkProduct->isObjectNew(true);
$linkProduct->save();

$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setToken('8ed8677e6c79e68b94e61658bd756ea7')
    ->setTokenCreatedAt(date('Y-m-d H:i:s'))
    ->setExpirationPeriod(2);
$link->isObjectNew(true);
$link->save();

/** @var \Weverson83\AddByLink\Model\LinkProduct $linkProduct */
$linkProduct = $objectManager->create(\Weverson83\AddByLink\Model\LinkProduct::class);
$linkProduct->setLinkId($link->getId());
$linkProduct->setProductId($product->getId());
$linkProduct->isObjectNew(true);
$linkProduct->save();



