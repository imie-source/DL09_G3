<?php

namespace Nannyster\Controllers;

use Nannyster\Models\Skills;
use Nannyster\Models\Projects;

class NotificationsController extends ControllerBase
{
	public function indexAction(){
		
	}

	public static function finder(){
		$skillsToValide = Skills::find(array(array(
			'valide' => 'N')
		));
		if(!$skillsToValide){
			$skillsToValide = 0;
		}
		else{
			$skillsToValide = count($skillsToValide);
		}
		$projectToValide = Projects::find(array(array(
			'valide' => 'N')
		));
		if(!$projectToValide){
			$projectToValide = 0;
		}
		else{
			$projectToValide = count($projectToValide);
		}

		return array('total' => $skillsToValide + $projectToValide, 'skills' => $skillsToValide, 'projects' => $projectToValide);
	}
}