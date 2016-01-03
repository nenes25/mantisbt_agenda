$(document).ready(function () {

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
        events: {
            url: "plugins/Agenda/pages/json-events.php?t_user_id=" + t_user_id + '&t_project_id=' + t_project_id + '&t_user_access_level=' + t_user_access_level,
            cache: false
        },
        /*
         Affichage des informations au click :
         - On mets une div avec le détails 
         */
        eventClick: function (calEvent, jsEvent, view) {

            var content = '';

            //Actions
            if (calEvent.className == 'action') {

                //Contenu html du block
                var content = '<h2>' + calEvent.title + '</h2><p>'+ mantisAgendaTranslate.actionStart +' : ' + calEvent.temps_deb + '<br />'+ mantisAgendaTranslate.duration +' : ' + calEvent.time_tracking + ' min<br />'
                        + ' '+ mantisAgendaTranslate.actionEnd +' ' + calEvent.temps_fin + '</p>'
                        + '<b>'+ mantisAgendaTranslate.noteDetails +' :</b>' + calEvent.note + '<br />'
                        + ''+ mantisAgendaTranslate.author +' : ' + calEvent.auteur + '<br />'
                        + '<a href="' + calEvent.bug_link + '" id="event_details_description_bug_show">'+ mantisAgendaTranslate.seeBugDescription +' </a><br /><div id="event_details_description_bug">' + calEvent.description + '</div>';

            }
            //Date d'échéances
            else if (calEvent.className == 'due_date') {
                var content = '<h2>' + calEvent.title + '</h2>'
                        + ' '+ mantisAgendaTranslate.dueDate +' <strong> ' + calEvent.echeance + '</strong></p>';
                +'<p><a href="' + calEvent.bug_link + '" id="event_details_description_bug_show">Voir le descriptif du bug </a></p>';
            }
            //Rien
            else {
            }

            if (content != '') {

                //Position Y du block = Position Y de l'évenement - 50 px
                $('#event_details').css('top', parseInt(jsEvent.pageY) - 50);
                $('#event_details').css('display', 'block');
                $('#event_details_content').html('').html(content);
            }
        },
        /*
         Au survol on change l'apparence du bloc pour pouvoir tout consulter en mode semaine ou Jour
         On stocke les hauteurs et largeurs initiales dans les propriétés min-width et min-height pour pouvoir les réutiliser avec l'event MouseOut
         */
        eventMouseover: function (event, jsEvent, view) {

            if (view.name == 'agendaWeek' || view.name == 'agendaDay') {
                $(this).css({
                    'min-width': $(this).css('width'),
                    'width': 'auto',
                    'min-height': $(this).css('height'),
                    'height': 'auto',
                    'z-index': 40
                });
            }
        },
        /*
         A la fin du survol on réduit de nouveau l'affichage
         */
        eventMouseout: function (event, jsEvent, view) {

            if (view.name == 'agendaWeek' || view.name == 'agendaDay') {
                $(this).css({
                    'width': $(this).css('min-width'),
                    'height': $(this).css('min-height'),
                    'z-index': 10
                });
            }
        },
        loading: function (bool) {
            if (bool)
                $('#loading').show();
            else
                $('#loading').hide();
        }

    });

    // Bouton pour fermer le détails
    $('#event_details_close').on('click', function () {
        $('#event_details').css('display', 'none');
    });

    //Bouton pour afficher le détail du bug
    $('#event_details_description_bug_show').on('click', function () {
        $('#event_details_description_bug').toggle('slow');
    });


});