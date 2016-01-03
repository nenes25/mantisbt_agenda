<?php
/*
	Plugin Agenda pour Mantis BugTracker :
	  - Page d'affichage du calendrier

	 © Hennes Hervé - 2011-2016
*/
html_page_top1( plugin_lang_get( 'see_agenda' ) );
html_page_top2();
print_summary_menu( 'summary_page.php' );
print_summary_submenu(); 
?>
<!-- Jquery Resources -->
<script type='text/javascript' src='plugins/Agenda/bower_components/moment/min/moment.min.js'></script>
<script type='text/javascript' src='plugins/Agenda/bower_components/jquery/dist/jquery.min.js'></script>
<!-- FullCalendar Resources -->
<link rel='stylesheet' type='text/css' href='plugins/Agenda/bower_components/fullcalendar/dist/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='plugins/Agenda/bower_components/fullcalendar/dist/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='plugins/Agenda/bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
<?php
 // Récupération du projet et de l'utilisateur courant et de ses droits
 $t_user_id = auth_get_current_user_id();
 $t_user_access_level = current_user_get_access_level();
 $t_project_id =  helper_get_current_project();
 
 //Récupération du code d'affichage de la langue si l'utilisateur est identifié
$t_pref = user_pref_get( $t_user_id );
$codeLang = AgendaPlugin::getFullCalendarLocaleCode($t_pref->language);
?>
<script type='text/javascript' src='plugins/Agenda/bower_components/fullcalendar/dist/lang/<?php echo $codeLang;?>.js'></script>
<!-- MantisAgenda Resources -->
<script type='text/javascript'>
 var mantisAgendaTranslate = {
     actionStart : '<?php echo plugin_lang_get('action_start');?>',
     actionEnd : '<?php echo plugin_lang_get('action_end');?>',
     duration : '<?php echo plugin_lang_get('duration');?>', 
     noteDetails : '<?php echo plugin_lang_get('note_details');?>',
     author: '<?php echo plugin_lang_get('author');?>',
     seeBugDescription: '<?php echo plugin_lang_get('see_bug_description');?>',
     dueDate: '<?php echo plugin_lang_get('due_date');?>'
 };   
</script>
<script type='text/javascript' src='plugins/Agenda/js/fullcalendar_init.js'></script>
<link rel='stylesheet' type='text/css' href='plugins/Agenda/css/mantisagenda.css' />

<body>
<input type="hidden" name="t_project_id" id="t_project_id" value="<?php echo $t_project_id; ?>" />
<input type="hidden" name="t_user_id" id="t_user_id" value="<?php echo $t_user_id; ?>" />
<input type="hidden" name="t_user_access_level" id="t_user_access_level" value="<?php echo $t_user_access_level; ?>" />

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
