<?php
/*
	Plugin Agenda pour Mantis BugTracker :
	
	 - Affichage du temps passé sur les bugs dans un calendrier 
	  
	  Nécessite l'ajout des paramètres suivants dans votre fichier de configuration 
	  $g_time_tracking_enabled = ON
	  $g_due_date_update_threshold = DEVELOPER; 
	  $g_due_date_view_threshold = REPORTER;
	  
	 - Basé sur le script de Calendrier "FullCalendar" disponible à l'adresse suivante : http://arshaw.com/fullcalendar/download/

	 © Hennes Hervé - 2011-2016
*/

class AgendaPlugin extends MantisPlugin {

	function register() {
		$this->name        = 'AgendaPlugin';
		$this->description = 'Affichage du temps passé sur les bugs dans un calendrier';
		$this->version     = '0.3.0';
		$this->requires    = array('MantisCore'       => '1.2.0',);
		$this->author      = 'Hennes Hervé';
		$this->url         = 'http://www.h-hennes.fr/blog';
	}
	
	function init() {
		plugin_event_hook( 'EVENT_MENU_MAIN', 'agendamenu' );
	}
	
	function agendamenu() {
		return array('<a href="' . plugin_page('Agenda_page.php') . '">' .plugin_lang_get('see_agenda') . '</a>');
	}
        
        #Recuperation du code d'affichage de la locale pour fullcalendar
        public static function getFullCalendarLocaleCode( $user_language = null) {
               
            switch ( $user_language ) {
                
                case 'french':
                    $code = 'fr';
                    break;
                
                case 'german':
                    $code = 'de';
                    break;
                
                case 'spanish':
                    $code = 'es';
                    break;
                
                default:
                    $code = 'en-gb';
            }
            
            return $code;
            
        }	
}