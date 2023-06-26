function wps_openModal(content, okCallBack, cancelCallback, closeCallback) {
    const modal = jQuery('.wps-wp-editor-modal').first();

    const okButton = jQuery('.wps-wp-editor-modal-ok').first();
    const cancelButton = jQuery('.wps-wp-editor-modal-cancel').first();
    const closeButton = jQuery('.wps-wp-editor-modal-close').first();
    
    wp.editor.initialize('wps-wp-editor', {
        tinymce: {
            wpautop:true,
            height: 480,
            auto_focus: true
        },
        quicktags: true,
        mediaButtons: true,
    });

    const editor = tinymce.get('wps-wp-editor');
    editor.setContent(content);

    function closeModal() {
        modal.css('display', 'none');
        wp.editor.remove('wps-wp-editor');
        okButton.off('click');
        cancelButton.off('click');
        closeButton.off('click');
    }

    okButton.on('click', function(event) {
        const content = wp.editor.getContent('wps-wp-editor');
        okCallBack?.(content);
        closeModal();
    });

    cancelButton.on('click', function(event) {
        cancelCallback?.();
        closeModal();
    });

    closeButton.on('click', function(event) {
        closeCallback?.();
        closeModal();
    });

    editor.on('keydown', function(event) {
        if (event.keyCode === 27) {
            closeCallback?.();
            closeModal();
        }
    });

    modal.css('display', 'block');
}
