<?php

namespace Nannyster\Controllers;

use Nannyster\Models\Skills;
use Nannyster\Models\Projects;
use Nannyster\Models\UsersProjects;

class NotificationsController extends ControllerBase
{

	public static function finder($user_id, $profile){

        $skillsToValide = 0;
        $projectToValide = 0;
        
        if($profile == 'Super Administrateur' || $profile == 'Administrateur'){
            //Renvoie le nb de compétences à valider
            $skillsToValide = Skills::find(array(array(
                'valide' => 'N')
            ));
            if(!$skillsToValide){
                $skillsToValide = 0;
            }
            else{
                $skillsToValide = count($skillsToValide);
            }

            //Renvoie le nb de projets à valider
            $projectToValide = Projects::find(array(array(
                'valide' => 'N')
            ));
            if(!$projectToValide){
                $projectToValide = 0;
            }
            else{
                $projectToValide = count($projectToValide);
            }
        }
        
        // Renvoi le nombre d'invitations à un projet
        $invitation = UsersProjects::find(array(array(
            'is_valid' => 'n',
            'user_id' => (string) $user_id
        )));
		if(!$invitation){
			$invitation = 0;
		}
		else{
			$invitation = count($invitation);
		}

		return array('total' => ($skillsToValide + $projectToValide + $invitation), 'skills' => $skillsToValide, 'projects' => $projectToValide, 'invitations' => $invitation);
	}
}