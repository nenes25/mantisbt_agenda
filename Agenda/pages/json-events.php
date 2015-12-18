<?php
/**
 *	Plugin Agenda pour Mantis BugTracker :
 *	Version 0.2.1
 *	© Hennes Hervé - 2013
 */
	include('../../../core/constant_inc.php');
	include('../../../config_inc.php');
	
	try {
		$db = new PDO('mysql:host='.$g_hostname.';dbname='.$g_database_name,$g_db_username,$g_db_password);
	}
	catch (Exception $e) {
		die($e->getMessage());
	}
	
	#Récupération des paramètres de la requêtes
	$t_user_id= (int)$_GET['t_user_id'];
	$t_project_id= (int)$_GET['t_project_id'];
	$t_user_access_level= (int)$_GET['t_user_access_level'];
	
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
	$query = $db->query('SELECT mbt.time_tracking, mbt.date_submitted, mbtt.note, mbug.description,mbg.summary title, mbg.id bug_id, mbt.id, mpt.name, mut.realname
						FROM mantis_bug_table mbg 
						LEFT JOIN mantis_project_table mpt ON ( mpt.id = mbg.project_id)
						LEFT JOIN  mantis_bugnote_table mbt ON (mbt.bug_id = mbg.id)
						LEFT JOIN mantis_bugnote_text_table mbtt ON (mbtt.id = mbt.bugnote_text_id)
						LEFT JOIN mantis_user_table mut ON (mbt.reporter_id = mut.id) 
						LEFT JOIN mantis_bug_text_table mbug ON (mbug.id = mbt.bug_id)
						'.$t_conditions_notes_sql);
			  
	while ( $result = $query->fetch() ) {
		
		if ( $result['id'] != NULL ) {
		
			$date_deb = date('Y-m-d H:i:s',$result['date_submitted']);
			$temps_deb = date('d-m-Y à H:i:s',$result['date_submitted']);
			
			if ( $result['time_tracking'] != 0 ){ 
				$date_fin = date('Y-m-d H:i:s',mktime(substr($date_deb,11,2),($result['time_tracking']+substr($date_deb,14,2)),0,substr($date_deb,5,2),substr($date_deb,8,2),substr($date_deb,0,4)));
				$temps_fin = date('d-m-Y à H:i:s',mktime(substr($date_deb,11,2),(substr($date_deb,14,2)-$result['time_tracking']),0,substr($date_deb,5,2),substr($date_deb,8,2),substr($date_deb,0,4)));
				
			}
			
			$bug_link = dirname($_SERVER['REQUEST_URI']);
			$bug_link = str_replace('plugins/Agenda/pages','',$bug_link);
			
			$results[] = array(
								'id' => $result['id'],
								'title' => utf8_encode($result['name']).' : '.utf8_encode($result['title']),
								'start' => $date_deb,
								'end' => $date_fin,
								'temps_deb' => $temps_deb,
								'temps_fin' => $temps_fin,
								'auteur' => utf8_encode($result['realname']),
								'note' => utf8_encode($result['note']),
								'description' => utf8_encode($result['description']),
								'time_tracking' => $result['time_tracking'],
								'allDay' => false ,
								'className' => 'action',
								'bug_link' => $bug_link.'view.php?id='.$result['id'],
						);
		}
	}
	
	
	/**
	 * Listing des dates d'échéances des bugs
	 */
	$query_dl = $db->query("SELECT mbt.id, mbt.due_date, mbt.summary, mpt.name
							FROM mantis_bug_table mbt
							LEFT JOIN mantis_bug_text_table mbug ON (mbug.id = mbt.id)
							LEFT JOIN mantis_project_table mpt ON ( mpt.id = mbt.project_id)
							".$t_conditions_due_date_sql);
							
	while ( $result_dl = $query_dl->fetch() ) {

		if ( $result_dl['due_date'] != 1 ) {
			$results[] = array(
							'id' => $result_dl['id'],
							'title' => utf8_encode($result_dl['name']).' :'.utf8_encode($result_dl['summary']),
							'start' =>  date('Y-m-d H:i:s',$result_dl['due_date']),
							'echeance' => date('d-m-Y',$result_dl['due_date']),
							'color' => 'red',
							'allDay' => true,
							'className' => 'due_date'
							);

		}					
	}
	
	#Encodage des resultats en JSON pour afficher dans le calendrier
	echo json_encode($results);
	
	
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
