<?php 
namespace Nannyster\Forms;


use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\div;
use Phalcon\Forms\Element\Textarea;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class CreateProjectForm extends Form
{

	public function initialize()
	{
		//Nom projet
		$name = new Text('name', array(
			'class' => 'col-xs-10 col-sm-5'
			));
		$name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Le nom du projet est requis'
            ))
        ));
			$this->add($name);

		//Nb personnes
		$nb_users_max = new Text('nb_users_max', array(
			'class' => 'input-mini spinner-input form-control',
			'id' => 'spinner2'
			));
				$this->add($nb_users_max);

		//date de début du projet
		$start_date = new Text('start_date', array(
			'class' => 'col-xs-10 col-sm-5',
			'id' => 'date'
			));
		$start_date->addValidators(array(
            new PresenceOf(array(
                'message' => 'Une date de début de projet est requise'
            ))
        ));
        	$this->add($start_date);

		//date de fin du projet
		$end_date = new Text('end_date', array(
			'class' => 'col-xs-10 col-sm-5',
			'id' => 'date1'
			));
			$this->add($end_date);

		//déscription du projet
		$description = new Textarea('description', array(
			'class' => 'col-xs-10 col-sm-5'
			));
			$this->add($description);

		$this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
}
}


 ?>