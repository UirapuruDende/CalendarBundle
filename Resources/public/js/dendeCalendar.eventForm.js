// @todo: update to oop / jquery plugin for form

$(document).ready(function() {
    var TYPE_SINGLE = "single";
    var TYPE_WEEKLY = "weekly";

    var $eventTypeElement = $("select#create_event_type, select#update_event_type");
    var $repetitionCheckboxes = $("label[for^='create_event_repetitionDays_'], label[for^='update_event_repetitionDays_']");
    var $endDateElement = $("label[for='create_event_endDate'], label[for='update_event_endDate'], input#create_event_endDate, input#update_event_endDate");

    var typeChangeEventName = "change";

    // handlers

    var eventTypeChangeHandler = function(event) {
        var $target = $(event.target);
        var value = $target.val();

        if(value === TYPE_SINGLE) {
            $repetitionCheckboxes.hide();
            $endDateElement.hide();
        } else if (value === TYPE_WEEKLY)
        {
            $repetitionCheckboxes.show();
            $endDateElement.show();
        }
    };

    // events

    $eventTypeElement.off(typeChangeEventName).on(typeChangeEventName, eventTypeChangeHandler);
    $eventTypeElement.trigger(typeChangeEventName);
});