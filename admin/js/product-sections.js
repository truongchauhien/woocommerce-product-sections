jQuery('.wps-product-section-add').on('click', function() {
    const template = jQuery('.wps-product-section-template').first().clone(true);
    template.attr('class', 'wps-product-section');
    template.find('input').first().attr('name', 'wps-section-id[]');
    template.find('input').eq(1).attr('name', 'wps-section-title[]');
    template.find('select').first().attr('name', 'wps-section-type[]');
    jQuery('.wps-product-sections').append(template);
    return false;
});

jQuery('.wps-product-section-delete').on('click', function() {
    jQuery(this).closest('.wps-product-section').remove();
    return false;
});
