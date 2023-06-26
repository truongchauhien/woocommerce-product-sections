jQuery('.wps-accordion-entry-trigger').on('click', function(event) {
    const currentTrigger = jQuery(this);
    const currentContent = currentTrigger.siblings('.wps-accordion-entry-content').first();

    currentTrigger
        .closest('.wps-accordion-section')
        .find('.wps-accordion-entry-content')
        .not(currentContent)
        .css('display', 'none');

    currentTrigger
        .closest('.wps-accordion-section')
        .find('.wps-accordion-entry-trigger')
        .not(currentTrigger)
        .removeClass('wps-accordion-entry-trigger-active');

    if (currentContent.css('display') === 'block') {
        currentTrigger.removeClass('wps-accordion-entry-trigger-active');
        currentContent.css('display', 'none');
    } else {
        currentTrigger.addClass('wps-accordion-entry-trigger-active');
        currentContent.css('display', 'block');
    }

    return false;
});
