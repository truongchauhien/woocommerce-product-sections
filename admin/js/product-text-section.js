jQuery('.wps-text-section-edit').on('click', function(event) {
    const container = jQuery(this).closest('.wps-text-section')
    const contentInput = container.find('input').first();
    const contentViewer = container.find('.wps-text-section-content').first();

    wps_openModal(contentInput.val(), function(content) {
        contentInput.val(content);
        contentViewer.html(content);
    });

    return false;
});
