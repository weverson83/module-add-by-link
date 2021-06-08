<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Weverson83\AddByLink\Model\Link $link */
$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setToken('8ed8677e6c79e68b94e61658bd756ea5')
    ->setTokenCreatedAt(date('Y-m-d H:i:s', strtotime('now -1 hour')))
    ->setExpirationPeriod(2);
$link->isObjectNew(true);
$link->save();


$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setToken('8ed8677e6c79e68b94e61658bd756ea5')
    ->setTokenCreatedAt(date('Y-m-d H:i:s', strtotime('now -3 hour')))
    ->setExpirationPeriod(2);
$link->isObjectNew(true);
$link->save();

$link = $objectManager->create(\Weverson83\AddByLink\Model\Link::class);
$link->setToken('8ed8677e6c79e68b94e61658bd756ea5')
    ->setTokenCreatedAt(date('Y-m-d H:i:s', strtotime('now +1 hour')))
    ->setExpirationPeriod(2);
$link->isObjectNew(true);
$link->save();
