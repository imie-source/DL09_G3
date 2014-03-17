<?php
namespace Nannyster\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;

class AddSkillForm extends Form
{

    public function initialize()
    {
        // Skill
        $skill = new Text('name', array(
            'placeholder' => 'Compétence',
            'class' => 'form-control'
        ));
        $skill->addValidators(array(
            new PresenceOf(array(
                'message' => 'La compétence est requise'
            ))
        ));
        $this->add($skill);

        // Description
        $description = new TextArea('description', array(
            'placeholder' => 'Description',
            'class' => 'form-control'
        ));
        $this->add($description);


        // Parent id
        $parent = new Hidden('parent_id');
        $this->add($parent);
    }
}
