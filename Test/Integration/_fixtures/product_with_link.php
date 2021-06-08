<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Weverson83\AddByLink\Model\Link $link */
$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setToken('8ed8677e6c79e68b94e61658bd756ea5')
    ->setTokenCreatedAt(date('Y-m-d H:i:s', strtotime('now -1 hour')))
    ->setExpirationPeriod(2);
$link->isObjectNew(true);
$link->save();

/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(1)
    ->setAttributeSetId(4)
    ->setName('New Product')
    ->setSku('simple')
    ->setPrice(10)
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setDescription('description')
    ->setShortDescription('short desc')
    ->save();

$productRepository = $objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$productRepository->save($product);

/** @var \Weverson83\AddByLink\Model\LinkProduct $linkProduct */
$linkProduct = $objectManager->create(\Weverson83\AddByLink\Model\LinkProduct::class);
$linkProduct->setLinkId($link->getId());
$linkProduct->setProductId($product->getId());
$linkProduct->isObjectNew(true);
$linkProduct->save();

