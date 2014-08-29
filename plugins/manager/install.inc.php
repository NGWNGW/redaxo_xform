<?php

global $REX;

/**
 * XForm
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

$sql = rex_sql::factory();

$sql->setQuery('CREATE TABLE IF NOT EXISTS `' . $REX['TABLE_PREFIX'] . 'xform_table` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `status` tinyint(1) NOT NULL,
    `table_name` varchar(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text NOT NULL,
    `list_amount` tinyint(3) unsigned NOT NULL DEFAULT 50,
    `prio` int(11) NOT NULL,
    `search` tinyint(1) NOT NULL,
    `hidden` tinyint(1) NOT NULL,
    `export` tinyint(1) NOT NULL,
    `import` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE(`table_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'xform_table` CHANGE `prio` `prio` INT NOT NULL');
$sql->setQuery('
    ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'xform_table`
    ADD `list_sortfield` VARCHAR(255) NOT NULL DEFAULT "id" AFTER `list_amount`,
    ADD `list_sortorder` ENUM("ASC","DESC") NOT NULL DEFAULT "ASC" AFTER `list_sortfield`
');

$sql->setQuery('CREATE TABLE IF NOT EXISTS `' . $REX['TABLE_PREFIX'] . 'xform_field` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `table_name` varchar(100) NOT NULL,
    `prio` int(11) NOT NULL,
    `type_id` varchar(100) NOT NULL,
    `type_name` varchar(100) NOT NULL,
    `list_hidden` tinyint(1) NOT NULL,
    `search` tinyint(1) NOT NULL,
    `name` text NOT NULL,
    `label` text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'xform_field` CHANGE `prio` `prio` INT NOT NULL');
$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'xform_field` CHANGE `f1` `name` TEXT NOT NULL');
$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'xform_field` ADD `label` TEXT NOT NULL');
$sql->setQuery('UPDATE `' . $REX['TABLE_PREFIX'] . 'xform_field` SET label = f2, f2 = "" WHERE type_id = "value" AND label = ""');

$sql->setQuery('SELECT id FROM `' . $REX['TABLE_PREFIX'] . 'xform_field` WHERE type_id = "validate" AND type_name = "type" AND required != "" LIMIT 1');
if ($sql->getRows()) {
    $sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'xform_field` ADD `not_required` TEXT NOT NULL');
    $sql->setQuery('UPDATE `' . $REX['TABLE_PREFIX'] . 'xform_field` SET `not_required` = `required`, `required` = "" WHERE type_id = "validate" AND type_name = "type"');
}

$REX['ADDON']['install']['manager'] = 1;
