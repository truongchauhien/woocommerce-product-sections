jQuery('.wps-accordion-entry-content-edit,.wps-accordion-entry-content').on('click', function (event) {
    const contentInput = jQuery(this).closest('.wps-accordion-entry').find('input').eq(1);
    const contentView = jQuery(this).closest('.wps-accordion-entry').find('.wps-accordion-entry-content').first();

    wps_openModal(contentInput.val(), function (content) {
        contentInput.val(content);
        contentView.html(content);
    });

    return false;
});

jQuery('.wps-accordion-entry-delete').on('click', function (event) {
    jQuery(this).closest('.wps-accordion-entry').remove();
    return false;
});

jQuery('.wps-accordion-entry-add').on('click', function (event) {
    const template = jQuery(this)
        .closest('.wps-accordion-section')
        .find('.wps-accordion-entry-template')
        .first()
        .clone(true);
    const id = jQuery(template).data('id');
    jQuery(template).attr('class', 'wps-accordion-entry');
    jQuery(template).find('input').eq(0).attr('name', `wps-accordion-entry-title[${id}][]`);
    jQuery(template).find('input').eq(1).attr('name', `wps-accordion-entry-content[${id}][]`);
    jQuery(this).closest('.wps-accordion-section').find('.wps-accordion-entries').first().append(template);
    return false;
});

jQuery('.wps-accordion-entry-trigger').on('click', function (event) {
    const currentBody = jQuery(this).siblings('.wps-accordion-entry-body').first();
    const currentTrigger = jQuery(this);

    jQuery(this).closest('.wps-accordion-section').find('.wps-accordion-entry-body').not(
        currentBody
    ).css('display', 'none');
    jQuery(this).closest('.wps-accordion-section').find('.wps-accordion-entry-trigger').not(
        currentTrigger
    ).removeClass('wps-accordion-entry-trigger-active');

    const computedStyle = window.getComputedStyle(
        currentBody.get(0)
    );

    if (computedStyle.getPropertyValue('display') === 'block') {
        currentBody.css('display', 'none');
        currentTrigger.removeClass('wps-accordion-entry-trigger-active');
    } else {
        currentBody.css('display', 'block');
        currentTrigger.addClass('wps-accordion-entry-trigger-active');
    }

    return false;
});

jQuery('.wps-accordion-entry input:nth-child(1)').on('input', function (event) {
    jQuery(this).parent().siblings('button').first().html(event.target.value);
});

jQuery('.wps-accordion-entries').sortable({
    cursor: 'move'
});
