<?php

/**
 * XForm
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_xform_integer extends rex_xform_abstract
{

    function enterObject()
    {
        if ($this->getValue() == '' && !$this->params['send']) {
            $this->setValue($this->getElement('default'));
        }

        if ('' === $this->getValue()) {
            $this->setValue(null);
        } else {
            $this->setValue((int) $this->getValue());
        }

        $this->params['form_output'][$this->getId()] = $this->parse(array('value.integer.tpl.php', 'value.text.tpl.php'));

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->getElement('no_db') != 'no_db') {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }

    }

    function getDescription()
    {
        return 'integer -> Beispiel: integer|name|label|defaultwert|[no_db]';
    }

    function getDefinitions()
    {
        return array(
            'type' => 'value',
            'name' => 'integer',
            'values' => array(
                'name'      => array( 'type' => 'name',    'label' => 'Feld' ),
                'label'     => array( 'type' => 'text',    'label' => 'Bezeichnung'),
                'default'   => array( 'type' => 'text',    'label' => 'Defaultwert'),
                'no_db'     => array( 'type' => 'no_db',   'label' => 'Datenbank',  'default' => 0),
            ),
            'description' => 'Ein Feld zur Eingabe von Integers',
            'dbtype' => 'int',
            'null' => true,
        );

    }

    public static function getSearchField($params)
    {
        $params['searchForm']->setValueField('text', array('name' => $params['field']->getName(), 'label' => $params['field']->getLabel()));
    }

    public static function getSearchFilter($params)
    {
        $value = $params['value'];
        $field =  mysql_real_escape_string($params['field']->getName());

        if ($value == '(empty)') {
            return ' (`' . $field . '` = "" or `' . $field . '` IS NULL) ';
        } elseif ($value == '!(empty)') {
            return ' (`' . $field . '` <> "" and `' . $field . '` IS NOT NULL) ';
        }

        if (preg_match('/^\s*(-?\d+)\s*\.\.\s*(-?\d+)\s*$/', $value, $match)) {
            $match[1] = (int) $match[1];
            $match[2] = (int) $match[2];
            return ' `' . $field . '` BETWEEN ' . $match[1] . ' AND ' . $match[2];
        } else {
            preg_match('/^\s*(<|<=|>|>=|<>|!=)?\s*(.*)$/', $value, $match);
            $comparator = $match[1] ?: '=';
            $value = (int) $match[2];
            return ' `' . $field . '` ' . $comparator . ' ' . $value;
        }
    }

}
