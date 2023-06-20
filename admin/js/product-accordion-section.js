jQuery('.wps-accordion-entry-content-edit').on('click', function(event) {
    const contentInput = jQuery(this).closest('.wps-accordion-entry').find('input').eq(1);
    const contentView = jQuery(this).closest('.wps-accordion-entry').find('.wps-accordion-entry-content').first();

    wps_openModal(contentInput.val(), function(content) {
        contentInput.val(content);
        contentView.html(content);
    });

    return false;
});

jQuery('.wps-accordion-entry-delete').on('click', function(event) {
    jQuery(this).closest('.wps-accordion-entry').remove();
    return false;
});

jQuery('.wps-accordion-entry-add').on('click', function(event) {
    const template = jQuery(this)
        .closest('.wps-accordion-section')
        .find('.wps-accordion-entry-template')
        .first()
        .clone(true);
    const id = jQuery(template).data('id');
    jQuery(template).attr('class', 'wps-accordion-entry');    
    jQuery(template).find('input').eq(0).attr('name', `wps-accordion-entry-title[${id}][]`);
    jQuery(template).find('input').eq(1).attr('name', `wps-accordion-entry-content[${id}][]`);
    jQuery(this).closest('.wps-accordion-section').append(template);
    return false;
});

jQuery('.wps-accordion-entry-trigger').on('click', function(event) {
    jQuery(this).closest('.wps-accordion-section').find('.wps-accordion-entry-body').css('display', 'none');
    jQuery(this).siblings('.wps-accordion-entry-body').first().css('display', 'block');

    jQuery(this).closest('.wps-accordion-section').find('.wps-accordion-entry-trigger').removeClass('wps-accordion-entry-trigger-active');
    jQuery(this).addClass('wps-accordion-entry-trigger-active');
    
    return false;
});
