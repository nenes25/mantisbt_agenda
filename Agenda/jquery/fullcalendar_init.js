$(document).ready(function() {


		// Récupération des variables du contexte utilisateur et du projet
		var t_user_id = $('#t_user_id').val();
		var t_project_id = $('#t_project_id').val();
		var t_user_access_level = $('#t_user_access_level').val();
		
		$('#calendar').fullCalendar({
		
			editable: true,
			
			//En tête 
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			
			//Format des colonnes
			columnFormat: {
				month: 'ddd',
				week: 'ddd d/M',
				day: 'dddd d/M'
			},
			
			//Premier jour de la seMayone : Lundi
			firstDay: 1,
			//Pas besoin d'afficher les weeks ends
			weekends : true,
			//Affichage différent selon les mois
			weekMode: 'variable',
			
			//@emmanuel1979: Variable configuration Days and months in SPANISH
			monthNames: ['Enero','Febrero','Martes','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			dayNamesShort: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
			buttonText: {
				prev: '&nbsp;&#9668;&nbsp;',
				next: '&nbsp;&#9658;&nbsp;',
				prevYear: '&nbsp;&lt;&lt;&nbsp;',
				nextYear: '&nbsp;&gt;&gt;&nbsp;',
				today: 'Hoy',
				month: 'Mes',
				week: 'Semana',
				day: 'Dia'
			},
			
			events: "plugins/Agenda/pages/json-events.php?t_user_id="+t_user_id+'&t_project_id='+t_project_id+'&t_user_access_level='+t_user_access_level,
			
			/*
				Affichage des informations au click :
				- On mets une div avec le détails 
			*/
			eventClick: function(calEvent, jsEvent, view) {
			 				
				var content = '';
				
				//Actions
				if ( calEvent.className == 'action' ) {
				
					//Contenu html du block
					//@emmanuel1979: The text was translated into Spanish
					var content = '<h2>'+calEvent.title+'</h2><p>Fecha Inicio : '+calEvent.temps_deb+'<br />Duración : '+calEvent.time_tracking+' min<br />'
					+'Fecha Fin : '+calEvent.temps_fin+'</p>'
					+'<b>Detalle de la nota :</b>'+calEvent.note+'<br />'
					+'Autor : '+calEvent.auteur+'<br />'
					+'<a href="'+calEvent.bug_link+'" id="event_details_description_bug_show">Ver Incidencias reportada </a><br /><div id="event_details_description_bug">'+calEvent.description+'</div>';	
				
				}
				//Date d'échéances
				else if ( calEvent.className == 'due_date') {
					var content = '<h2>'+calEvent.title+'</h2>'
					+'Fecha límite : <strong> '+calEvent.echeance+'</strong></p>';
					+'<p><a href="'+calEvent.bug_link+'" id="event_details_description_bug_show">Ver Incidencias reportada </a></p>';
				}
				//Rien
				else {}
				
				if ( content != '' ) {
				
					//Position Y du block = Position Y de l'évenement - 50 px
					$('#event_details').css('top',parseInt(jsEvent.pageY) - 50);
					$('#event_details').css('display','block');
					$('#event_details_content').html('').html(content);
				}
			},
			
			/*
				Au survol on change l'apparence du bloc pour pouvoir tout consulter en mode seMayone ou Jour
				On stocke les hauteurs et largeurs initiales dans les propriétés min-width et min-height pour pouvoir les réutiliser avec l'event MouseOut
			*/
			eventMouseover: function( event, jsEvent, view ) {
		
				if ( view.name == 'agendaWeek' || view.name == 'agendaDay' ) {
					$(this).css({
						'min-width':$(this).css('width'),
						'width':'auto',
						'min-height': $(this).css('height'),
						'height':'auto',
						'z-index':40
					});
				}
			},
			
			/*
				A la fin du survol on réduit de nouveau l'affichage
			*/
			eventMouseout: function( event, jsEvent, view ) {
		
				if ( view.name == 'agendaWeek' || view.name == 'agendaDay' ) {
					$(this).css({
						'width':$(this).css('min-width'),
						'height':$(this).css('min-height'),
						'z-index':10
					});
				}
			},
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
			
		});
		
		// Bouton pour fermer le détails
		$('#event_details_close').on('click',function(){
			$('#event_details').css('display','none');
		});
		
		//Bouton pour afficher le détail du bug
		$('#event_details_description_bug_show').on('click',function() {
			$('#event_details_description_bug').toggle('slow');
});
	
	
	});
