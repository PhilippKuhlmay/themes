<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Static templates
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('Themes', 'Configuration/TypoScript', 'Themes');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('Themes', 'Configuration/TypoScript/FluidStyledContent', 'Themes (For backward compatibility: Additional add this for using fluid_styled_content)');

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Themes',
        'system', // Main area
        'log', // Name of the module
        '', // Position of the module
        [
            // Allowed controller action combinations
            \TYPO3\CMS\Belog\Controller\BackendLogController::class => 'list,deleteMessage',
        ], [
            // Additional configuration
            'access' => 'admin',
            'icon' => 'EXT:belog/Resources/Public/Icons/module-belog.svg',
            'labels' => 'LLL:EXT:belog/Resources/Private/Language/locallang_mod.xlf',
            'labels' => 'LLL:EXT:belog/Resources/Private/Language/locallang_mod.xlf',
        ]
    );
    // Add some backend stylesheets and javascript
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][]
        = \KayStrobach\Themes\Hooks\PageRenderer::class . '->addJSCSS';
}

/*
 * add themes overlay
 */
if (!isset($GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayPriorities'])) {
    $GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayPriorities'] = array();
}
array_push($GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayPriorities'], 'themefound');
$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayNames']['themefound'] = 'extensions-themes-overlay-theme';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_themes_buttoncontent');

// register svg icons: identifier and filename
$iconsSvg = [
    'module-themes' => 'ext_icon.svg',
    'content-button' => 'Resources/Public/Icons/new_content_el_ButtonContent.svg',
    'switch-off' => 'Resources/Public/Icons/power_grey.svg',
    'switch-on' => 'Resources/Public/Icons/power_green.svg',
    'switch-disable' => 'Resources/Public/Icons/power_orange.svg',
    'overlay-theme' => 'Resources/Public/Icons/overlay_theme.svg',
    'contains-theme' => 'ext_icon.svg',
    'new_content_el_buttoncontent' => 'Resources/Public/Icons/new_content_el_ButtonContent.svg',
];
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
foreach ($iconsSvg as $identifier => $path) {
    $iconRegistry->registerIcon(
        $identifier, \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, ['source' => 'EXT:' . 'Themes' . '/' . $path]
    );
}

$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'LLL:EXT:themes/Resources/Private/Language/locallang.xlf:contains-theme',
    1 => 'themes',
    2 => 'extensions-themes-contains-theme',
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-themes'] = 'extensions-themes-contains-theme';
