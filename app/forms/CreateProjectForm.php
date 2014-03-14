<?php 
namespace Nannyster\Forms;


use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
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
			'placeholder' => 'Nom de projet',
			'class' => 'form-control'
			));
		$name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Le nom du projet est requis'
            ))
        ));
			$this->add($name);

		//Nb personnes
		$nb_users_max = new Text('nb_users_max', array(
			'placeholder' => 'Nombre de participants maximum',
			'class' => 'form-control',
			'id' => 'nbUsers'
			));
				$this->add($nb_users_max);

		//date de début du projet
		$start_date = new Text('start_date', array(
			'class' => 'form-control',
			'placeholder' => 'Date de début',
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
			'class' => 'form-control',
			'placeholder' => 'Date de fin',
			'id' => 'date1'
			));
			$this->add($end_date);

		//déscription du projet
		$description = new Textarea('description', array(
			'class' => 'form-control',
			'placeholder' => 'déscription du projet',
			));
			$this->add($description);

		$this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
}
}


 ?>