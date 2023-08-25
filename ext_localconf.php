<?php

defined('TYPO3') || die('Access denied.');


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'OpenAiApi',
    'ExampleOutput',
    [
        \Passionweb\OpenAiApi\Controller\AiController::class => 'index,sendPrompt'
    ],
    // non-cacheable actions
    [
        \Passionweb\OpenAiApi\Controller\AiController::class => 'index,sendPrompt'
    ]
);

// wizards
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    'mod {
        wizards.newContentElement.wizardItems.plugins {
            elements {
                exampleoutput {
                    iconIdentifier = openaiapi-example
                    title = LLL:EXT:open_ai_api/Resources/Private/Language/locallang_db.xlf:plugin_openaiapi_example.name
                    description = LLL:EXT:open_ai_api/Resources/Private/Language/locallang_db.xlf:plugin_openaiapi_example.description
                    tt_content_defValues {
                        CType = list
                        list_type = openaiapi_example
                    }
                }
            }
            show = *
        }
   }'
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'openaiapi-example',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:open_ai_api/Resources/Public/Icons/Extension.png']
);

