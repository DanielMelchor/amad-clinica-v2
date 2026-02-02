@extends('adminlte::page')
@section('css')
	<style>
		.fc-day-today {
	      background-color: #D0E8F3; /* Amarillo, puedes cambiarlo a tu gusto */
	      color: #000; /* Color de texto del día actual (opcional) */
	    }

	    .fc-event .btn {
      		margin-left: 10px;
	      	padding: 5px 10px;
	      	background-color: #007bff;
	      	color: white;
	      	border: none;
	      	font-size: 12px;
	      	cursor: pointer;
	    }

	    .fc-event .btn:hover {
      		background-color: #0056b3;
	    }
	</style>
@endsection
@section('title', 'Agenda')
@section('content_header')
@endsection
@section('content')
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<div id='calendar'></div>
			<div id="event-toolbar" style="display: none; position: absolute; background: #fff; border: 1px solid #ccc; padding: 10px;">
			    <h4 id="event-title"></h4>
			    <p id="event-details"></p>
			</div>
		</div>
	</div>
@endsection
@section('js')
	<script src="{{ asset('plugins/fullcalendar-6.1.15/dist/index.global.min.js') }}"></script>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
    		var calendarEl = document.getElementById('calendar');
	        var calendar = new FullCalendar.Calendar(calendarEl, {
		        themeSystem: 'bootstrap',
		        // height: 100%,
		        locale: 'es',
		        initialView: 'timeGridDay',
		        slotMinTime:'07:00:00',
				slotMaxTime:'18:00:00',
				slotDuration: '00:45', // 2 hours
				slotLabelFormat: {
			    	hour: '2-digit',  // Formato de 2 dígitos para la hora (ej. 08)
				    minute: '2-digit', // Formato de 2 dígitos para los minutos (ej. 00)
				    hour12: false,     // Desactivar el formato de 12 horas
			  	},
			  	timeformat: {
		        	timeGridDay: 'hh(:mm) { - hh(:mm)}'
		        },
		        headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay',
		        },
		        eventColor: '#F4F6F7',
		        businessHours: [ // specify an array instead
			  		{
					    daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday, Tuesday, Wednesday
					    startTime: '07:00', // 8am
					    endTime: '13:00' // 6pm
				  	},
				  	{
					    daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday, Tuesday, Wednesday
					    startTime: '14:00', // 8am
					    endTime: '18:00' // 6pm
				  	}
				],
				buttonText: {
			        today: 'Hoy',
			        month: 'Mes',
			        week: 'Semana',
			        day: 'Día',
			        list: 'Lista'
		      	},
		      	events: [
		      		{
				        title: 'Daniel Alfonso Melchor Anleu',
				        start: '2025-01-31T10:00:00',
				        description: 'Tel 22891492, 58701923. \n Llamar para confirmar',
				        id: 1
			      	},
			      	{
				        title: 'Evento 2',
				        start: '2025-01-31T12:00:00',
				        description: 'Detalles del evento 2',
				        id: 2
			      	}
			    ],

			    eventMouseEnter: function(info) {
			      // Obtener los datos del evento
			      var event = info.event;
			      
			      // Mostrar el toolbar y actualizar su contenido
			      var toolbar = document.getElementById('event-toolbar');
			      var title = document.getElementById('event-title');
			      var details = document.getElementById('event-details');

			      title.innerText = event.title;
			      details.innerText = event.extendedProps.description;
			      
			      // Posicionar el toolbar cerca del evento
			      var rect = info.el.getBoundingClientRect();
			      toolbar.style.left = rect.left + 'px';
			      toolbar.style.top = rect.top + rect.height + 10 + 'px'; // Ubicando abajo del evento
			      toolbar.style.background = '#D0E8F3';
			      toolbar.style.display = 'block';
			    },
			    eventMouseLeave: function() {
			      // Ocultar el toolbar cuando el cursor deje el evento
			      document.getElementById('event-toolbar').style.display = 'none';
			    },
			    eventContent: function(arg) {
			      // Crear un botón personalizado para cada evento
			      var button = $('<button class="btn btn-primary">Detalles</button>');
			      button.on('click', function() {
			        alert('Detalles de: ' + arg.event.title);  // Puedes personalizar la acción del botón aquí
			      });
			      
			      // Devolver el contenido del evento con el botón agregado
			      var eventElement = $(arg.el);
			      eventElement.append(button);
			      
			      return { html: eventElement.html() };
			    },
	        });
	        calendar.render();
      	});
	</script>
@endsection