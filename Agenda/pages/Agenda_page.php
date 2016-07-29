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
#  © Hennes Hervé <contact@h-hennes.fr>
#    2015-2016
#  http://www.h-hennes.fr/blog/

html_page_top1( plugin_lang_get( 'see_agenda' ) );
html_page_top2();
print_summary_menu( 'summary_page.php' );
print_summary_submenu(); 
?>
<!-- Jquery Resources -->
<script type='text/javascript' src='<?php echo plugin_file('bower/moment/min/moment.min.js');?>'></script>
<!--<script type='text/javascript' src='plugins/Agenda/bower_components/jquery/dist/jquery.min.js'></script>-->
<!-- FullCalendar Resources -->
<link rel='stylesheet' type='text/css' href='<?php echo plugin_file('bower/fullcalendar/dist/fullcalendar.css');?>' />
<link rel='stylesheet' type='text/css' href='<?php echo plugin_file('bower/fullcalendar/dist/fullcalendar.print.css');?>' media='print' />
<script type='text/javascript' src='<?php echo plugin_file('bower/fullcalendar/dist/fullcalendar.min.js');?>'></script>
<?php
 // Récupération du projet et de l'utilisateur courant et de ses droits
 $t_user_id = auth_get_current_user_id();
 $t_user_access_level = current_user_get_access_level();
 $t_project_id =  helper_get_current_project();
 
 //Récupération du code d'affichage de la langue si l'utilisateur est identifié
$t_pref = user_pref_get( $t_user_id );
$codeLang = AgendaPlugin::getFullCalendarLocaleCode($t_pref->language);
?>
<script type='text/javascript' src='<?php echo plugin_file('bower/fullcalendar/dist/lang/'.$codeLang.'.js');?>'></script>
<!-- MantisAgenda Resources -->

<script type='text/javascript' src='<?php echo plugin_file('fullcalendar_init.js'); ?>'></script>
<link rel='stylesheet' type='text/css' href='<?php echo plugin_file('mantisagenda.css'); ?>' />

<body>
<input type="hidden" name="t_project_id" id="t_project_id" value="<?php echo $t_project_id; ?>" />
<input type="hidden" name="t_user_id" id="t_user_id" value="<?php echo $t_user_id; ?>" />
<input type="hidden" name="t_user_access_level" id="t_user_access_level" value="<?php echo $t_user_access_level; ?>" />

<?php #Gestion des traductions mantisbt 1.3 ?>
<input type="hidden" id="mantis_agenda_translate_actionStart" value="<?php echo plugin_lang_get('action_start');?>" />
<input type="hidden" id="mantis_agenda_translate_actionEnd" value="<?php echo plugin_lang_get('action_end');?>" />
<input type="hidden" id="mantis_agenda_translate_duration" value="<?php echo plugin_lang_get('duration');?>" />
<input type="hidden" id="mantis_agenda_translate_noteDetails" value="<?php echo plugin_lang_get('note_details');?>" />
<input type="hidden" id="mantis_agenda_translate_author" value="<?php echo plugin_lang_get('author');?>" />
<input type="hidden" id="mantis_agenda_translate_seeBugDescription" value="<?php echo plugin_lang_get('see_bug_description');?>" />
<input type="hidden" id="mantis_agenda_translate_dueDate" value="<?php echo plugin_lang_get('due_date');?>" />

<?php #Affichage d'un message d'erreur si les configurations nécessaires ne sont pas actives ?>
<?php if ( $g_time_tracking_enabled != ON || $g_due_date_update_threshold != DEVELOPER || $g_due_date_view_threshold != REPORTER ): ?>
<div id="plugin-agenda-warning">
    <p><?php echo plugin_lang_get('configuration_error'); ?></p>
    <ul>
        <li>$g_time_tracking_enabled = ON</li>
        <li>$g_due_date_update_threshold = DEVELOPER</li>
        <li>$g_due_date_view_threshold = REPORTER</li>
    </ul>
</div>
<?php endif; ?>

<div id='loading' style='display:none'><?php echo plugin_lang_get('loading_text');?>...</div>
<div id='calendar'></div>

<div id="event_details">
<p class="p_event_details_header"><a id="event_details_close" href="#event_details" name="event_details"><?php echo plugin_lang_get('close_details');?></a></p>
	<div id="event_details_content">
	</div>
</div>
<?php
html_page_bottom1( __FILE__ );
