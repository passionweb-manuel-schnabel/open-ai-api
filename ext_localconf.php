<?php

defined('TYPO3') || die('Access denied.');


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'OpenAiApi',
    'PromptOutput',
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
                promptoutput {
                    iconIdentifier = openaiapi-promptoutput
                    title = LLL:EXT:open_ai_api/Resources/Private/Language/locallang_db.xlf:plugin_openaiapi_promptoutput.name
                    description = LLL:EXT:open_ai_api/Resources/Private/Language/locallang_db.xlf:plugin_openaiapi_promptoutput.description
                    tt_content_defValues {
                        CType = list
                        list_type = openaiapi_promptoutput
                    }
                }
            }
            show = *
        }
   }'
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'openaiapi-promptoutput',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:open_ai_api/Resources/Public/Icons/Extension.png']
);

