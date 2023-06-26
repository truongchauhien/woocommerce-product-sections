jQuery('.wps-table-entry-add').on('click', function() {
    const table = jQuery(this).closest('.wps-table-section');
    const template = table
        .find('.wps-table-entry-template')
        .first()
        .clone(true);
    const id = jQuery(template).data('id');
    jQuery(template).attr('class', 'wps-table-entry');
    jQuery(template).find('input').eq(0).attr('name', `wps-table-entry-name[${id}][]`);
    jQuery(template).find('input').eq(1).attr('name', `wps-table-entry-value[${id}][]`);

    table.find('tbody').first().append(template);
    return false;
});

jQuery('.wps-table-entry-delete').on('click', function() {
    jQuery(this).closest('.wps-table-entry').remove();
    return false;
});

jQuery('.wps-table-entries').sortable({
    cursor: 'move'
});
