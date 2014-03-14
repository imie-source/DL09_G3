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
		$project_name = new Text('project_name', array(
			'placeholder' => 'Nom de projet',
			'class' => 'form-control'
			));
		$project_name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Le nom du projet est requis'
            ))
        ));
			$this->add($project_name);

		//Chef de projet
		$project_master = new Text('project_master', array(
			'placeholder' => 'Chef de projet',
			'class' => 'form-control'
			));
		$project_master->addValidators(array(
            new PresenceOf(array(
                'message' => 'Le nom du chef de projet est requis'
            ))
        ));
			$this->add($project_master);

		//Nb personnes
		$nb_project_users = new Text('nb_project_users', array(
			'placeholder' => 'Nombre de participants',
			'class' => 'form-control',
			'id' => 'nbUsers'
			));
				$this->add($nb_project_users);

		//date de début du projet
		$project_start_date = new Text('project_start_date', array(
			'class' => 'form-control',
			'placeholder' => 'Date de début',
			'id' => 'date'
			));
		$project_start_date->addValidators(array(
            new PresenceOf(array(
                'message' => 'Une date de début de projet est requise'
            ))
        ));
        	$this->add($project_start_date);

		//date de fin du projet
		$project_end_date = new Text('project_end_date', array(
			'class' => 'form-control',
			'placeholder' => 'Date de fin',
			'id' => 'date1'
			));
			$this->add($project_end_date);

		//déscription du projet
		$project_description = new Textarea('project_description', array(
			'class' => 'form-control',
			'placeholder' => 'déscription du projet',
			));
			$this->add($project_description);

		$this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
}
}


 ?>