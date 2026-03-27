<style>
    #dropZone {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    #dropZone:hover {
        background-color: #f8f9fa;
    }

    #dropZone.bg-light {
        background-color: #f8f9fa !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .file-item {
        transition: all 0.2s ease;
    }

    .file-item:hover {
        background-color: #f8f9fa;
    }
</style>
@canAccess('create_tickets', 'update')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    Edit ticket request - {{ $ticket->ticket_number }}
                </p>

                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="can_update" value="1">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Ticket Number (Read-only)</label>
                                <input type="text" class="form-control" value="{{ $ticket->ticket_number }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Status (Read-only)</label>
                                <input type="text" class="form-control"
                                    value="{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Company</label>
                                @if ($session_company_id && !$is_holding)
                                    {{-- Locked: non-admin cannot change company --}}
                                    <input type="hidden" name="company_id" value="{{ $ticket->company_id }}">
                                    <input type="text" class="form-control" value="{{ session('company_name') }}"
                                        readonly style="background-color:#e9ecef; cursor:not-allowed;">
                                @else
                                    <select name="company_id" class="form-control select2" required>
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ $company->id == $ticket->company_id ? 'selected' : '' }}>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Division</label>
                                @if ($session_division_id && !$is_holding)
                                    {{-- Locked: non-admin cannot change division --}}
                                    <input type="hidden" name="division_id" value="{{ $ticket->division_id }}">
                                    <input type="text" class="form-control" value="{{ session('division_name') }}"
                                        readonly style="background-color:#e9ecef; cursor:not-allowed;">
                                @else
                                    <select name="division_id" class="form-control select2" required>
                                        <option value="">Select Division</option>
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->id }}"
                                                {{ $division->id == $ticket->division_id ? 'selected' : '' }}>
                                                {{ $division->division_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $ticket->title }}"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required>{{ $ticket->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Request Type</label>
                                <select name="request_type" id="request_type" class="form-control select2" required>
                                    <option value="">Select Type</option>
                                    @foreach ($ticketTypes as $type)
                                        <option value="{{ $type->ticket_type_id }}"
                                            @if ($type->ticket_type_id == $ticket->request_type) selected @endif>
                                            {{ $type->ticket_type_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Urgency Level</label>
                                <select name="urgency_level" class="form-control select2" required>
                                    <option value="low" @if ($ticket->urgency_level == 'low') selected @endif>Low
                                    </option>
                                    <option value="medium" @if ($ticket->urgency_level == 'medium') selected @endif>Medium
                                    </option>
                                    <option value="high" @if ($ticket->urgency_level == 'high') selected @endif>High
                                    </option>
                                    <option value="critical" @if ($ticket->urgency_level == 'critical') selected @endif>Critical
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="dynamic_fields">
                        @foreach ($fields as $field)
                            <div class="form-group mb-3">
                                <label>{{ $field->field_name }}</label>
                                <input type="text" name="custom_fields[{{ $field->field_name }}]"
                                    class="form-control" value="{{ $field->field_value }}" required>
                            </div>
                        @endforeach
                    </div>

                    <!-- File Attachments -->
                    <div class="form-group mb-3">
                        <label>Add Attachments (Optional)</label>
                        <div class="border rounded p-3" id="attachmentSection">
                            <div class="mb-3">
                                <input type="file" id="fileInput" class="form-control" multiple
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif"
                                    style="display: none;">
                                <div id="dropZone"
                                    class="text-center p-4 border-2 border-dashed border-primary rounded cursor-pointer">
                                    <i class="bx bx-cloud-upload bx-lg text-primary mb-2"></i>
                                    <p class="mb-1">Drag & drop files here or <span class="text-primary fw-bold"
                                            id="browseBtn">browse</span></p>
                                    <small class="text-muted">Supported formats: PDF, DOC, DOCX, XLS, XLSX, TXT, JPG,
                                        JPEG, PNG, GIF (Max 10MB each)</small>
                                </div>
                            </div>
                            <div id="fileList" class="mt-3"></div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Existing Attachments</label>
                        <div id="existingFileList" class="mt-2"></div>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">Update Ticket</button>
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        let selectedFiles = [];
        const maxFileSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif'];
        const ticketId = {{ $ticket->id }};
        loadExistingAttachments();

        // Load dynamic fields
        var requestType = $('#request_type').val();
        if (requestType) {
            loadDynamicFields(requestType);
        }

        $('#request_type').on('change', function() {
            loadDynamicFields($(this).val());
        });

        function loadDynamicFields(type) {
            if (!type) {
                $('#dynamic_fields').empty();
                return;
            }

            $.ajax({
                url: '/api/ticket-fields/' + type,
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                success: function(response) {
                    $('#dynamic_fields').html(response);

                    // Load existing field values
                    @foreach ($fields as $field)
                        $('input[name="{{ $field->field_name }}"]').val(
                            '{{ $field->field_value }}');
                    @endforeach
                },
                error: function() {
                    console.log('Error loading fields');
                }
            });
        }

        // ===== FILE ATTACHMENT HANDLERS =====
        // Drag and drop functionality
        $('#dropZone').on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('bg-light');
        });

        $('#dropZone').on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('bg-light');
        });

        $('#dropZone').on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('bg-light');

            const files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });

        // Browse button click
        $('#browseBtn').on('click', function() {
            $('#fileInput').click();
        });

        // File input change
        $('#fileInput').on('change', function() {
            const files = this.files;
            handleFiles(files);
        });

        function handleFiles(files) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Validate file size
                if (file.size > maxFileSize) {
                    toastr.error(`File "${file.name}" is too large. Maximum size is 10MB.`);
                    continue;
                }

                // Validate file type
                const fileExtension = file.name.split('.').pop().toLowerCase();
                if (!allowedTypes.includes(fileExtension)) {
                    toastr.error(`File type "${fileExtension}" is not allowed.`);
                    continue;
                }

                // Check if file already selected
                const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                if (isDuplicate) {
                    toastr.warning(`File "${file.name}" is already selected.`);
                    continue;
                }

                selectedFiles.push(file);
                displayFile(file);
            }
            $('#fileInput').val('');
        }

        function displayFile(file) {
            const fileId = Date.now() + Math.random();
            const fileSize = formatFileSize(file.size);
            const fileIcon = getFileIcon(file.type);

            const fileHtml = `
            <div class="file-item d-flex align-items-center justify-content-between p-2 border rounded mb-2" data-file-id="${fileId}">
                <div class="d-flex align-items-center">
                    <i class="${fileIcon} me-2 text-primary"></i>
                    <div>
                        <small class="fw-bold">${file.name}</small><br>
                        <small class="text-muted">${fileSize}</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-file" data-file-id="${fileId}">
                    <i class="bx bx-x"></i>
                </button>
            </div>
        `;

            $('#fileList').append(fileHtml);
            $(`[data-file-id="${fileId}"]`).attr('data-file-name', file.name).attr('data-file-size', file.size);
        }

        // Remove file
        $(document).on('click', '.remove-file', function() {
            const fileItem = $(this).closest('.file-item');
            const fileName = fileItem.data('file-name');
            const fileSize = fileItem.data('file-size');
            selectedFiles = selectedFiles.filter(file => !(file.name === fileName && file.size === fileSize));
            fileItem.remove();
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function getFileIcon(mimeType) {
            if (mimeType.includes('pdf')) return 'bx bx-file-blank';
            if (mimeType.includes('word') || mimeType.includes('document')) return 'bx bx-file';
            if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'bx bx-spreadsheet';
            if (mimeType.includes('image')) return 'bx bx-image';
            return 'bx bx-file';
        }

        function loadExistingAttachments() {
            $.ajax({
                url: `/api/tickets/${ticketId}/attachments`,
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        renderExistingAttachments(response.data);
                    } else {
                        $('#existingFileList').html('<p class="text-muted mb-0">No attachments</p>');
                    }
                },
                error: function() {
                    $('#existingFileList').html('<p class="text-danger mb-0">Failed to load attachments</p>');
                }
            });
        }

        function renderExistingAttachments(attachments) {
            let html = '';
            attachments.forEach(function(attachment) {
                const fileIcon = getFileIcon(attachment.mime_type || '');
                const fileSize = formatFileSize(attachment.file_size || 0);

                html += `
                    <div class="d-flex align-items-center justify-content-between p-2 border rounded mb-2">
                        <div class="d-flex align-items-center">
                            <i class="${fileIcon} me-2 text-primary"></i>
                            <div>
                                <small class="fw-bold">${attachment.file_name}</small><br>
                                <small class="text-muted">${fileSize}</small>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-download-existing me-1" data-id="${attachment.id}">
                                <i class="bx bx-download"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-existing" data-id="${attachment.id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            $('#existingFileList').html(html);
        }

        $(document).on('click', '.btn-delete-existing', function() {
            const attachmentId = $(this).data('id');
            if (!confirm('Delete this attachment?')) return;

            $.ajax({
                url: `/api/attachments/${attachmentId}`,
                type: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                success: function(response) {
                    if (response.success) {
                        message.sweetSuccess('Attachment deleted');
                        loadExistingAttachments();
                    } else {
                        message.sweetError(response.message || 'Failed to delete attachment');
                    }
                },
                error: function() {
                    message.sweetError('Failed to delete attachment');
                }
            });
        });

        $(document).on('click', '.btn-download-existing', function() {
            const attachmentId = $(this).data('id');

            $.ajax({
                url: `/api/attachments/${attachmentId}/download`,
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + Token.get()
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data, status, xhr) {
                    let filename = '';
                    const disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        const matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }
                    if (!filename) filename = 'attachment_' + attachmentId;

                    const blob = new Blob([data]);
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function() {
                    message.sweetError('Failed to download attachment');
                }
            });
        });

        // Modify form submission to include files
        $('form').on('submit', function(e) {
            if (selectedFiles.length > 0) {
                e.preventDefault();

                // Show loading
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).html(
                    '<i class="bx bx-loader-alt bx-spin"></i> Updating Ticket...');

                // First update the ticket
                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Ticket updated, now upload attachments
                        uploadAttachments();
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message ||
                            'Failed to update ticket';
                        toastr.error(error);
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            }
            // If no files selected, let form submit normally
        });

        function uploadAttachments() {
            if (selectedFiles.length === 0) {
                toastr.success('Ticket updated successfully');
                window.location.href = '/tickets/' + ticketId;
                return;
            }

            let uploadedCount = 0;
            const totalFiles = selectedFiles.length;

            selectedFiles.forEach(file => {
                const fileData = new FormData();
                fileData.append('file', file);

                $.ajax({
                    url: `/api/tickets/${ticketId}/attachments/upload`,
                    type: 'POST',
                    data: fileData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Authorization': 'Bearer ' + Token.get()
                    },
                    success: function(response) {
                        uploadedCount++;
                        if (uploadedCount === totalFiles) {
                            toastr.success('Ticket and attachments updated successfully');
                            window.location.href = '/tickets/' + ticketId;
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message ||
                            `Failed to upload ${file.name}`;
                        toastr.error(error);
                        uploadedCount++;
                        if (uploadedCount === totalFiles) {
                            // Still redirect even if some uploads failed
                            window.location.href = '/tickets/' + ticketId;
                        }
                    }
                });
            });
        }
        if ($('.select2').length) {
            $('.select2').select2({ width: '100%' });
        }
    });
</script>
@endsection
@else
@include('errors.no_akes')
@endcanAccess
