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
			
			//Premier jour de la semaine : Lundi
			firstDay: 1,
			//Pas besoin d'afficher les weeks ends
			weekends : true,
			//Affichage différent selon les mois
			weekMode: 'variable',
			
			//Configuration des variables Jours et mois en FR
			monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
			monthNamesShort: ['Jan','Fev','Mar','Avr','Mai','Jun','Jul','Aou','Sep','Oct','Nov','Dec'],
			dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
			dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
			buttonText: {
				prev: '&nbsp;&#9668;&nbsp;',
				next: '&nbsp;&#9658;&nbsp;',
				prevYear: '&nbsp;&lt;&lt;&nbsp;',
				nextYear: '&nbsp;&gt;&gt;&nbsp;',
				today: 'Aujourd\'hui',
				month: 'Mois',
				week: 'Semaine',
				day: 'Jour'
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
					var content = '<h2>'+calEvent.title+'</h2><p>Début action : '+calEvent.temps_deb+'<br />Durée : '+calEvent.time_tracking+' min<br />'
					+'Fin de l\'action : '+calEvent.temps_fin+'</p>'
					+'<b>Détails de la note :</b>'+calEvent.note+'<br />'
					+'Auteur : '+calEvent.auteur+'<br />'
					+'<a href="'+calEvent.bug_link+'" id="event_details_description_bug_show">Voir le descriptif du bug </a><br /><div id="event_details_description_bug">'+calEvent.description+'</div>';	
				
				}
				//Date d'échéances
				else if ( calEvent.className == 'due_date') {
					var content = '<h2>'+calEvent.title+'</h2>'
					+'Date d\'objectif de correction du bug: <strong> '+calEvent.echeance+'</strong></p>';
					+'<p><a href="'+calEvent.bug_link+'" id="event_details_description_bug_show">Voir le descriptif du bug </a></p>';
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
				Au survol on change l'apparence du bloc pour pouvoir tout consulter en mode semaine ou Jour
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