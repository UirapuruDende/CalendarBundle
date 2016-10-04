// @todo: update to oop / jquery plugin for form

$(document).ready(function() {
    var TYPE_SINGLE = "single";
    var TYPE_WEEKLY = "weekly";

    var $eventTypeElement = $("select[id$='_type']");
    var $repetitionCheckboxes = $("div[id$='_repetitionDays']").parents('div.form-group');
    var $endDateElement = $("input[id$='_endDate']").parents('div.form-group');

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