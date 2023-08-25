<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Log\LogManager;
use Psr\Log\LoggerInterface;
use Passionweb\OpenAiApi\Controller\AiController;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services->load('Passionweb\\OpenAiApi\\', __DIR__ . '/../Classes/')
        ->exclude([
            __DIR__ . '/../Classes/Domain/Model',
        ]);

    $services->set('ExtConf.openAiApi', 'array')
        ->factory([service(ExtensionConfiguration::class), 'get'])
        ->args([
            'open_ai_api'
        ]);

    $containerBuilder->register('Logger', LoggerInterface::class);
    $services->set('PsrLogInterface', 'Logger')
        ->factory([
            service(LogManager::class), 'getLogger'
        ]);

    $services->set(AiController::class)
        ->arg('$extConf', service('ExtConf.openAiApi'))
        ->arg('$logger', service('PsrLogInterface'))
        ->public();
};
