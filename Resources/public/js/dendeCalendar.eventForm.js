// @todo: update to oop / jquery plugin for form

$(document).ready(function() {
    var TYPE_SINGLE = "single";
    var TYPE_WEEKLY = "weekly";

    var $eventTypeElement = $("select[id$='_type']");
    var $repetitionCheckboxesDiv = $("div[id$='_repetitions']").parents('div.form-group');
    var $repetitionCheckboxesInputs = $("input:checkbox[id*=_repetitions]");
    var typeChangeEventName = "change";

    var $calendarElement = $("select[id$='_calendar']");
    var $newCalendarInput = $("input[id$='_newCalendarName']").parents('div.form-group');
    var calendarEventName = "change";

    var $selectMethod = $("select#update_event_method");
    var $eventDates = $("#update_event_eventDates").parents("div.form-group");
    var $occurrenceDates = $("#update_event_occurrenceDates").parents("div.form-group");
    var methodEventName = "change";

    var $titleInput = $("input[id$='_title']");

    // handlers

    var eventTypeChangeHandler = function(event) {
        var $target = $(event.target);
        var value = $target.val();

        if(value === TYPE_SINGLE) {
            $repetitionCheckboxesDiv.hide();
        } else if (value === TYPE_WEEKLY) {
            $repetitionCheckboxesDiv.show();
        }
    };

    var noCalendarSelectedHandler = function(event) {
        var $target = $(event.target);
        var value = $target.val();

        if(value !== '') {
            $newCalendarInput.hide();
        } else
        {
            $newCalendarInput.show();
        }
    };

    var switchMethodHandler = function(event) {
        var $target = $(event.target);
        var value = $target.val();

        if(value === 'single') {
            $eventDates.hide();
            $occurrenceDates.show();
            $repetitionCheckboxesInputs.attr("readonly", "READONLY").attr("disabled", "DISABLED");
        } else {
            $eventDates.show();
            $occurrenceDates.hide();
            $repetitionCheckboxesInputs.removeAttr("readonly").removeAttr("disabled");
        }

        if(value === 'allinclusive') {
            $titleInput.removeAttr("readonly").removeAttr("disabled");
        } else {
            $titleInput.attr("readonly", "READONLY").attr("disabled", "DISABLED");
        }
    };

    // events

    $eventTypeElement.off(typeChangeEventName).on(typeChangeEventName, eventTypeChangeHandler);
    $eventTypeElement.trigger(typeChangeEventName);

    $calendarElement.off(calendarEventName).on(calendarEventName, noCalendarSelectedHandler);
    $calendarElement.trigger(calendarEventName);

    $selectMethod.off(methodEventName).on(calendarEventName, switchMethodHandler);
    $selectMethod.trigger(methodEventName);
});
