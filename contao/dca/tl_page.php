<?php

// Palettes
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('password_legend', 'routing_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addField('passwordProtected', 'password_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('regular', 'tl_page')
    ->applyToPalette('forward', 'tl_page')
    ->applyToPalette('redirect', 'tl_page')
;

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'passwordProtected';
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['passwordProtected'] = 'password,passwordPage';

// Fields
$GLOBALS['TL_DCA']['tl_page']['fields']['passwordProtected'] = [
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true, 'tl_class' => 'clr'],
    'sql' => "char(1) COLLATE ascii_bin NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['password'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['mandatory' => true, 'maxlength' => 64, 'decodeEntities' => true, 'tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_page']['fields']['passwordPage'] = [
    'exclude' => true,
    'inputType' => 'pageTree',
    'eval' => ['mandatory' => true, 'fieldType' => 'radio', 'tl_class' => 'clr'],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
];
