<?php
/*
	Plugin Agenda pour Mantis BugTracker :
	  - Page d'affichage du calendrier

	 Version 0.2.1
	 © Hennes Hervé - 2013
*/
html_page_top1( lang_get( 'see_agenda' ) );
html_page_top2();
print_summary_menu( 'summary_page.php' );
print_summary_submenu(); 
?>
<!-- Jquery Resources -->
<script type='text/javascript' src='plugins/Agenda/jquery/jquery-1.9.1.min.js'></script>
<script type='text/javascript' src='plugins/Agenda/jquery/jquery-ui-1.10.2.custom.min.js'></script>
<!-- FullCalendar Resources -->
<link rel='stylesheet' type='text/css' href='plugins/Agenda/fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='plugins/Agenda/fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='plugins/Agenda/fullcalendar/fullcalendar.min.js'></script>
<!-- MantisAgenda Resources -->
<script type='text/javascript' src='plugins/Agenda/jquery/fullcalendar_init.js'></script>
<link rel='stylesheet' type='text/css' href='plugins/Agenda/css/mantisagenda.css' />

<body>
<?php
 // Récupération du projet et de l'utilisateur courant et de ses droits
 $t_user_id = auth_get_current_user_id();
 $t_user_access_level = current_user_get_access_level();
 $t_project_id =  helper_get_current_project();
?>

<input type="hidden" name="t_project_id" id="t_project_id" value="<?php echo $t_project_id; ?>" />
<input type="hidden" name="t_user_id" id="t_user_id" value="<?php echo $t_user_id; ?>" />
<input type="hidden" name="t_user_access_level" id="t_user_access_level" value="<?php echo $t_user_access_level; ?>" />

<div id='loading' style='display:none'><?php echo plugin_lang_get('loading_text');?>...</div>
<div id='calendar'></div>

<div id="event_details">
<p class="p_event_details_header"><a id="event_details_close" href="#event_details" name="event_details"><?php echo plugin_lang_get('close_details');?></a></p>
	<div id="event_details_content">
	</div>
</div>
<?php
html_page_bottom1( __FILE__ );
