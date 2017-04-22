// @todo: update to oop / jquery plugin for form

$(document).ready(function() {
    var TYPE_SINGLE = "single";
    var TYPE_WEEKLY = "weekly";

    var $eventTypeElement = $("select[id$='_type']");
    var $repetitionCheckboxes = $("div[id$='_repetitions']").parents('div.form-group');
    var typeChangeEventName = "change";

    var $calendarElement = $("select[id$='_calendar']");
    var $newCalendarInput = $("input[id$='_newCalendarName']").parents('div.form-group');
    var calendarEventName = "change";

    var $selectMethod = $("select#update_event_method");
    var $eventDates = $("#update_event_eventDates").parents("div.form-group");
    var $occurrenceDates = $("#update_event_occurrenceDates").parents("div.form-group");
    var methodEventName = "change";

    // handlers

    var eventTypeChangeHandler = function(event) {
        var $target = $(event.target);
        var value = $target.val();

        if(value === TYPE_SINGLE) {
            $repetitionCheckboxes.hide();
        } else if (value === TYPE_WEEKLY) {
            $repetitionCheckboxes.show();
        }
    };

    var noCalendarSelectedHandler = function(event) {
        var $target = $(event.target);
        var value = $target.val();

        if(value != '') {
            $newCalendarInput.hide();
        } else
        {
            $newCalendarInput.show();
        }
    };

    var switchDatesHandler = function(event) {
        var $target = $(event.target);
        var value = $target.val();

        if(value === single) {
            $eventDates.hide();
            $occurrenceDates.show();
        } else {
            $eventDates.show();
            $occurrenceDates.hide();
        }
    }

    // events

    $eventTypeElement.off(typeChangeEventName).on(typeChangeEventName, eventTypeChangeHandler);
    $eventTypeElement.trigger(typeChangeEventName);

    $calendarElement.off(calendarEventName).on(calendarEventName, noCalendarSelectedHandler);
    $calendarElement.trigger(calendarEventName);

    $selectMethod.off(methodEventName).on(calendarEventName, switchDatesHandler);
    $selectMethod.trigger(methodEventName);
});
