<?php
# MantisBT - A PHP based bugtracking system
# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

#
#  Agenda Plugin for Mantis BugTracker :
#  - Display time passed on bug in a calendar
#  - Need the folowing option to work :
#   $g_time_tracking_enabled = ON
#   $g_due_date_update_threshold = DEVELOPER; 
#   $g_due_date_view_threshold = REPORTER;	  
#  
#  © Hennes Hervé <contact@h-hennes.fr>
#    2011-2016
#  http://www.h-hennes.fr/blog/

class AgendaPlugin extends MantisPlugin {

	function register() {
		$this->name        = 'AgendaPlugin';
		$this->description = 'Affichage du temps passé sur les bugs dans un calendrier';
		$this->version     = '0.4.0';
		$this->requires    = array('MantisCore'       => '1.3.0',);
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