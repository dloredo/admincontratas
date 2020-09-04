@extends('layouts.layout')
@section('main')
@if($message = Session::get('message'))
<div class="alert {{ (Session::get('estatus'))? 'alert-success' : 'alert-danger' }} alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">Correcto</h3>
    <p class="mb-0">{{ $message }}</p>
</div>
@endif
<h2 class="content-heading">Fechas no laborales</h2>
<div class="block">
    <div class="block-content">
        <div class="row items-push">
            <div class="col-xl-9">
                <!-- Calendar Container -->
                <div id="calendar" class="js-calendar"></div>
            </div>
            <div class="col-xl-3 d-none d-xl-block">
                <!-- Add Event Form -->
                <form class="js-form-add-event mb-30" action="be_comp_calendar.html" method="post">
                    <div class="input-group">
                        <select class="bg-info-light" name="" id="eventColor">
                            <option class="bg-info-light" value="bg-info-light"></option>
                            <option class="bg-success-light" value="bg-success-light"></option>
                            <option class="bg-danger-light" value="bg-danger-light"></option>
                            <option class="bg-warning-light" value="bg-warning-light"></option>
                        </select>
                        <input type="text" class="js-add-event form-control" placeholder="Agrega un evento">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <!-- END Add Event Form -->

                <!-- Event List -->
                <ul class="js-events list list-events">
                </ul>
                <div class="text-center">
                    <em class="font-size-xs text-muted"><i class="fa fa-arrows"></i>Crea una fecha y arrastralo al calendario*</em>
                </div>
                <!-- END Event List -->
            </div>
        </div>
    </div>
</div>


@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('assets/js/plugins/fullcalendar/fullcalendar.min.css')}}">

@endsection


@section('scripts')
<script src="{{asset('assets/js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/fullcalendar/fullcalendar.min.js')}}"></script>



<script>
    $(document).ready(() => {

        $('.js-form-add-event').on('submit', e => {
            e.preventDefault();
            let eventInput = $('.js-add-event');
            let eventInputVal = eventInput.val();
           
            if ( eventInputVal ) {
                
                let li_lement = $("<li>");
                li_lement.html(eventInputVal)
                li_lement.addClass($("#eventColor").val());
                $('.js-events').prepend(li_lement);
                eventInput.val('');

                setDragToElement(li_lement);
                
            }

            return false;
        });

        $("#eventColor").on("change",function(){
            $(this).removeClass();
            $(this).addClass($(this).val());
        })

        $('#calendar').fullCalendar({
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
            firstDay: 1,
            editable: true,
            droppable: true,
            header: {
                left: 'title',
                right: 'prev,next'
            },
            events:[],
            drop: (date, jsEvent, ui, resourceId) => {

                let event = $(ui.helper);
                let originalEventObject = event.data('eventObject');
                let copiedEventObject = $.extend({}, originalEventObject);
                copiedEventObject.start = date;
                $('.js-calendar').fullCalendar('renderEvent', copiedEventObject, true);
                event.remove();
            },
            viewDisplay: function (element) {
                console.log(element)
            }
        });
    })

    function setDragToElement(element){
        let eventObject = {
            title: $.trim(element.html()),
            color: element.css('background-color')
        };
        element.data('eventObject', eventObject);
        element.draggable({
            zIndex: 999,
            revert: true,
            revertDuration: 0
        });
    }

</script>
 
@endsection
