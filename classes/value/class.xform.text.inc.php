<?php

/**
 * XForm
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_xform_text extends rex_xform_abstract
{

    function enterObject()
    {

        $this->setValue((string) $this->getValue());

        if ($this->getValue() == '' && !$this->params['send']) {
            $this->setValue($this->getElement(3));
        }

        $this->params['form_output'][$this->getId()] = $this->parse('value.text.tpl.php');

        $this->params['value_pool']['email'][$this->getName()] = stripslashes($this->getValue());
        if ($this->getElement(4) != 'no_db') {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }

    }

    function getDescription()
    {
        return 'text -> Beispiel: text|name|label|defaultwert|[no_db]|cssclassname';
    }

    function getDefinitions()
    {
        return array(
            'type' => 'value',
            'name' => 'text',
            'values' => array(
                array( 'type' => 'name',   'label' => 'Feld' ),
                array( 'type' => 'text',    'label' => 'Bezeichnung'),
                array( 'type' => 'text',    'label' => 'Defaultwert'),
                array( 'type' => 'no_db',   'label' => 'Datenbank',  'default' => 0),
                array( 'type' => 'text',    'label' => 'cssclassname'),
            ),
            'description' => 'Ein einfaches Textfeld als Eingabe',
            'dbtype' => 'text',
            'famous' => true
        );

    }
}
