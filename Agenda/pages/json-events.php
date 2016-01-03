<?php
/**
 *	Plugin Agenda pour Mantis BugTracker :
 *	
 *	© Hennes Hervé - 2011-2016
 */
	#require_once( dirname(__FILE__) . '/../../../core.php' );
	#Dev 
	require_once( '/var/www/public/mantisbt/mantisbt-1.2.19/core.php' );
	
	#Récupération des paramètres de la requêtes
	$t_user_id= gpc_get_int('t_user_id');
	$t_project_id= gpc_get_int('t_project_id');
	$t_user_access_level= gpc_get_int('t_user_access_level');
	
	#initialisation des variables de conditions de requêtes
	$t_conditions_notes = array();
	$t_conditions_due_date = array();
	
	#Si un project en particulier est selectionné
	if ( $t_project_id != 0 ) {
		$t_conditions_notes[] = 'mbg.project_id = '.$t_project_id;
		$t_conditions_due_date[] = 'mbug.id ='.$t_project_id;
	}
	
	
	#Gestion de l'affichage en fonction des droits de l'utilisateur
	switch ( $t_user_access_level ) {
		
		#Administrateur : On affiche l'ensemble des actions et des échéances (Pas de paramètres supplémentaires nécessaires)
		case 90:
		break;
		
		#40:updater,55:developer,70:manager : On affiche les actions et les échéances de l'utilisateur
		case 40:
		case 55:
		case 70:
		$t_conditions_notes[] = 'mbg.handler_id ='.$t_user_id;
		$t_conditions_due_date[] = 'mbt.handler_id ='.$t_user_id;
		break;
		
		#Pour les autres cas on affiche rien
		default:
		return false;
		break;
	
	}
	
	#construction de la condition des requetes
	$t_conditions_notes_sql = get_conditions_sql($t_conditions_notes);
	$t_conditions_due_date_sql = get_conditions_sql($t_conditions_due_date);
	
	/**
	 * Listing des actions sur les bugs
	 */
	$t_actions = db_query('SELECT mbt.time_tracking, mbt.date_submitted, mbtt.note, mbug.description,mbg.summary title, mbg.id bug_id, mbt.id, mpt.name, mut.realname
						FROM mantis_bug_table mbg 
						LEFT JOIN mantis_project_table mpt ON ( mpt.id = mbg.project_id)
						LEFT JOIN  mantis_bugnote_table mbt ON (mbt.bug_id = mbg.id)
						LEFT JOIN mantis_bugnote_text_table mbtt ON (mbtt.id = mbt.bugnote_text_id)
						LEFT JOIN mantis_user_table mut ON (mbt.reporter_id = mut.id) 
						LEFT JOIN mantis_bug_text_table mbug ON (mbug.id = mbt.bug_id)
						'.$t_conditions_notes_sql);
			  
	while ( $t_result = db_fetch_array($t_actions) ) {
		
		if ( $t_result['id'] != NULL ) {
		
			$t_date_deb = date('Y-m-d H:i:s',$t_result['date_submitted']);
			$t_temps_deb = date('d-m-Y à H:i:s',$t_result['date_submitted']);
			
			if ( $t_result['time_tracking'] != 0 ){ 
				$date_fin = date('Y-m-d H:i:s',mktime(substr($t_date_deb,11,2),($t_result['time_tracking']+substr($t_date_deb,14,2)),0,substr($$t_date_deb,5,2),substr($t_date_deb,8,2),substr($t_date_deb,0,4)));
				$temps_fin = date('d-m-Y à H:i:s',mktime(substr($t_date_deb,11,2),(substr($t_date_deb,14,2)-$t_result['time_tracking']),0,substr($t_date_deb,5,2),substr($t_date_deb,8,2),substr($t_date_deb,0,4)));
				
			}
			
			$bug_link = dirname($_SERVER['REQUEST_URI']);
			$bug_link = str_replace('plugins/Agenda/pages','',$bug_link);
			
			$t_results[] = array(
								'id' => $t_result['id'],
								'title' => $t_result['name'].' : '.$t_result['title'],
								'start' => $t_date_deb,
								'end' => $date_fin,
								'temps_deb' => $t_temps_deb,
								'temps_fin' => $temps_fin,
								'auteur' =>$t_result['realname'],
								'note' => $t_result['note'],
								'description' => $t_result['description'],
								'time_tracking' => $t_result['time_tracking'],
								'allDay' => false ,
								'className' => 'action',
								'bug_link' => $bug_link.'view.php?id='.$t_result['bug_id'],
						);
		}
	}
	
	
	/**
	 * Listing des dates d'échéances des bugs
	 */

	$t_query_dl = db_query("SELECT mbt.id, mbt.due_date, mbt.summary, mpt.name
							FROM mantis_bug_table mbt
							LEFT JOIN mantis_bug_text_table mbug ON (mbug.id = mbt.id)
							LEFT JOIN mantis_project_table mpt ON ( mpt.id = mbt.project_id)
							".$t_conditions_due_date_sql);
							
	while ( $t_result_dl = db_fetch_array($t_query_dl) ) {

		if ( $t_result_dl['due_date'] != 1 ) {
			$t_results[] = array(
							'id' => $t_result_dl['id'],
							'title' => $t_result_dl['name'].' :'.$t_result_dl['summary'],
							'start' =>  date('Y-m-d H:i:s',$t_result_dl['due_date']),
							'echeance' => date('d-m-Y',$t_result_dl['due_date']),
							'color' => 'red',
							'allDay' => true,
							'className' => 'due_date'
							);

		}					
	}
	
	#Encodage des resultats en JSON pour afficher dans le calendrier
	 echo json_encode($t_results);
	
	
	/**
	 * Transformation d'un tableau de conditions en un string de conditions SQL 
	 * @param array $t_conditions_array = tableau de conditions sql
	 * @return string $t_conditions_sql = string de conditions sql
	 */
	function get_conditions_sql($t_conditions_array) {
	
		$t_conditions_sql = '';
		$t_sizeof_conditions_array = sizeof($t_conditions_array);
	
		if ( !sizeof($t_conditions_array) )
			return $t_conditions_sql;
			
		for ( $i = 0 ; $i < $t_sizeof_conditions_array ; $i++ ) {
		
				if ( $i == 0 ) {
					$t_conditions_sql .= ' WHERE '.$t_conditions_array[$i].' ';
				} else {
					$t_conditions_sql .= ' AND '.$t_conditions_array[$i].' ';
				}
		}
	
		return $t_conditions_sql;
	}
	
?>
