$(document).ready(function () {

    // Récupération des variables du contexte utilisateur et du projet
    var t_user_id = $('#t_user_id').val();
    var t_project_id = $('#t_project_id').val();
    var t_user_access_level = $('#t_user_access_level').val();
    
    //Gestion des traduction (compatible 1.3 )
    var mantisAgendaTranslate = {
     actionStart : $('#mantis_agenda_translate_actionStart').val(),
     actionEnd : $('#mantis_agenda_translate_actionEnd').val(),
     duration : $('#mantis_agenda_translate_duration').val(), 
     noteDetails : $('#mantis_agenda_translate_noteDetails').val(),
     author: $('#mantis_agenda_translate_author').val(),
     seeBugDescription: $('#mantis_agenda_translate_seeBugDescription').val(),
     dueDate: $('#mantis_agenda_translate_dueDate').val()
    };   

    $('#calendar').fullCalendar({
        editable: true,
        //En tête 
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: {
            url: "plugin.php?page=Agenda/json-events.php&t_user_id=" + t_user_id + '&t_project_id=' + t_project_id + '&t_user_access_level=' + t_user_access_level,
            cache: false
        },
        /*
         Affichage des informations au click :
         - On mets une div avec le détails 
         */
        eventClick: function (calEvent, jsEvent, view) {

            console.log(calEvent);

            var content = '';

            //Actions
            if (calEvent.className == 'action') {

                //Contenu html du block
                var content = '<h4>' + calEvent.title + '</h4><p>'+ mantisAgendaTranslate.actionStart +' : ' + calEvent.temps_deb + '<br />'+ mantisAgendaTranslate.duration +' : ' + calEvent.time_tracking + ' min<br />'
                        + ' '+ mantisAgendaTranslate.actionEnd +' ' + calEvent.temps_fin + '</p>'
                        + '<b>'+ mantisAgendaTranslate.noteDetails +' :</b>' + calEvent.note + '<br />'
                        + ''+ mantisAgendaTranslate.author +' : ' + calEvent.auteur + '<br />'
                        + '<a href="' + calEvent.bug_link + '" id="event_details_description_bug_show">'+ mantisAgendaTranslate.seeBugDescription +' </a><br /><div id="event_details_description_bug">' + calEvent.description + '</div>';

            }
            //Date d'échéances
            else if (calEvent.className == 'due_date') {
                var content = '<h4>' + calEvent.title + '</h4>'
                        + ' '+ mantisAgendaTranslate.dueDate +' <strong> ' + calEvent.echeance + '</strong></p>'
                +'<p><a href="' + calEvent.bug_link + '" id="event_details_description_bug_show">'+ mantisAgendaTranslate.seeBugDescription +'</a></p>';
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