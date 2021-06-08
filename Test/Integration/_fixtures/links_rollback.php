<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Weverson83\AddByLink\Model\Link $link */
$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setId(1);
$link->delete();

$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setId(2);
$link->delete();

$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setId(3);
$link->delete();
