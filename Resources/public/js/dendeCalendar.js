$(document).ready(function() {

    /**
     * @param Moment start
     * @param Moment end
     */
    var newSelectionHandler = function(start, end) {
        var message = "Dodać nowe wydarzenie?\n\nPoczątek:\t"
            +start.format("YYYY.MM.DD [o] HH:mm")
            +"\nKoniec:\t"
            +end.format("YYYY.MM.DD [o] HH:mm");

        if(confirm(message)) {
            window.location.href = Routing.generate('dende_calendar_default_createevent', {
                startDate: start.format("YYYYMMDDHHmm"),
                endDate: end.format("YYYYMMDDHHmm")
            })
        }
    };

    $('#calendar').fullCalendar({
        defaultView: 'agendaWeek',
        allDaySlot: false,
        slotDuration: '00:30:00',
        minTime: '06:00:00',
        maxTime: '24:00:00',
        timeZone: dende_calendar_tz,
        slotLabelFormat: 'H:00',
        snapDuration: '00:15:00',
        selectable: true,
        select: newSelectionHandler,
        lang: dende_calendar_lang,
        eventClick: eventClickHandler,
        events: Routing.generate('dende_calendar_default_getevents')
    })
});