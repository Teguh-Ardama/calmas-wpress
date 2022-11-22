<?php
 namespace MailPoetVendor\Symfony\Component\DependencyInjection\Compiler; if (!defined('ABSPATH')) exit; use MailPoetVendor\Psr\Container\ContainerInterface; use MailPoetVendor\Symfony\Component\DependencyInjection\Definition; use MailPoetVendor\Symfony\Component\DependencyInjection\Reference; use MailPoetVendor\Symfony\Contracts\Service\ServiceProviderInterface; class ResolveServiceSubscribersPass extends AbstractRecursivePass { private $serviceLocator; protected function processValue($value, $isRoot = \false) { if ($value instanceof Reference && $this->serviceLocator && \in_array((string) $value, [ContainerInterface::class, ServiceProviderInterface::class], \true)) { return new Reference($this->serviceLocator); } if (!$value instanceof Definition) { return parent::processValue($value, $isRoot); } $serviceLocator = $this->serviceLocator; $this->serviceLocator = null; if ($value->hasTag('container.service_subscriber.locator')) { $this->serviceLocator = $value->getTag('container.service_subscriber.locator')[0]['id']; $value->clearTag('container.service_subscriber.locator'); } try { return parent::processValue($value); } finally { $this->serviceLocator = $serviceLocator; } } } 