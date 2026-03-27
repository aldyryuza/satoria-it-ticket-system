var Attachment = {
    ticketId: null,

    init: function (ticketId) {
        this.ticketId = ticketId;
        this.loadAttachments();
        this.bindEvents();
    },

    bindEvents: function () {
        var self = this;

        // Drag and drop
        $('#dropZone').on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        $('#dropZone').on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        $('#dropZone').on('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');

            var files = e.originalEvent.dataTransfer.files;
            self.uploadFiles(files);
        });

        // File input change
        $('#fileInput').on('change', function () {
            var files = this.files;
            self.uploadFiles(files);
        });

        // Delete attachment
        $(document).on('click', '.btn-delete-attachment', function () {
            var attachmentId = $(this).data('id');
            self.deleteAttachment(attachmentId);
        });

        // Download attachment
        $(document).on('click', '.btn-download-attachment', function () {
            var attachmentId = $(this).data('id');
            self.downloadAttachment(attachmentId);
        });
    },

    loadAttachments: function () {
        var self = this;
        $.ajax({
            url: '/api/tickets/' + this.ticketId + '/attachments',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + Token.get()
            },
            success: function (response) {
                if (response.success && response.data.length > 0) {
                    self.renderAttachments(response.data);
                } else {
                    $('#attachmentsList').html('<p class="text-muted">No attachments</p>');
                }
            },
            error: function () {
                message.sweetError('Failed to load attachments');
            }
        });
    },

    uploadFiles: function (files) {
        var self = this;

        if (files.length === 0) return;

        for (var i = 0; i < files.length; i++) {
            var formData = new FormData();
            formData.append('file', files[i]);

            $.ajax({
                url: '/api/tickets/' + this.ticketId + '/attachments/upload',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                success: function (response) {
                    if (response.success) {
                        message.sweetSuccess('File uploaded successfully');
                        self.loadAttachments();
                    } else {
                        message.sweetError(response.message || 'Upload failed');
                    }
                },
                error: function (xhr) {
                    var error = xhr.responseJSON?.message || 'Upload failed';
                    message.sweetError(error);
                }
            });
        }
    },

    deleteAttachment: function (attachmentId) {
        if (!confirm('Are you sure you want to delete this attachment?')) return;

        var self = this;
        $.ajax({
            url: '/api/attachments/' + attachmentId,
            type: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + Token.get()
            },
            success: function (response) {
                if (response.success) {
                    message.sweetSuccess('Attachment deleted successfully');
                    self.loadAttachments();
                } else {
                    message.sweetError(response.message || 'Delete failed');
                }
            },
            error: function () {
                message.sweetError('Failed to delete attachment');
            }
        });
    },

    downloadAttachment: function (attachmentId) {
        $.ajax({
            url: '/api/attachments/' + attachmentId + '/download',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + Token.get()
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data, status, xhr) {
                // Create blob link to download
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);

                // Get filename from response headers
                var filename = '';
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, '');
                    }
                }

                // Fallback to default filename if not found in headers
                if (!filename) {
                    filename = 'attachment_' + attachmentId;
                }

                link.download = filename;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                toastr.success('File downloaded successfully');
            },
            error: function (xhr) {
                var error = xhr.responseJSON?.message || 'Failed to download file';
                toastr.error(error);
            }
        });
    },

    renderAttachments: function (attachments) {
        var html = '';
        attachments.forEach(function (attachment) {
            var fileIcon = Attachment.getFileIcon(attachment.mime_type);
            var fileSize = Attachment.formatFileSize(attachment.file_size);

            html += '<div class="attachment-item">' +
                '<div class="attachment-info">' +
                '<i class="' + fileIcon + '"></i>' +
                '<div class="attachment-details">' +
                '<p class="attachment-name">' + attachment.file_name + '</p>' +
                '<small class="text-muted">' + fileSize + ' · Uploaded ' + attachment.created_at + '</small>' +
                '</div>' +
                '</div>' +
                '<div class="attachment-actions">' +
                '<button class="btn btn-sm btn-primary btn-download-attachment" data-id="' + attachment.id + '" title="Download">' +
                '<i class="bx bx-download"></i>' +
                '</button>' +
                '<button class="btn btn-sm btn-danger btn-delete-attachment" data-id="' + attachment.id + '" title="Delete">' +
                '<i class="bx bx-trash"></i>' +
                '</button>' +
                '</div>' +
                '</div>';
        });

        $('#attachmentsList').html(html);
    },

    getFileIcon: function (mimeType) {
        if (!mimeType) return 'bx bx-file';

        if (mimeType.includes('pdf')) return 'bx bx-file-pdf';
        if (mimeType.includes('image')) return 'bx bx-image';
        if (mimeType.includes('word') || mimeType.includes('document')) return 'bx bx-file-blank';
        if (mimeType.includes('sheet') || mimeType.includes('spreadsheet')) return 'bx bx-spreadsheet';
        if (mimeType.includes('zip') || mimeType.includes('compressed')) return 'bx bx-archive';

        return 'bx bx-file';
    },

    formatFileSize: function (bytes) {
        if (bytes === 0) return '0 Bytes';

        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
};
