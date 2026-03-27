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
@canAccess('create_tickets','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $data_page['title'] }}</h5>
                <p class="card-text">
                    Create a new ticket request.
                </p>

                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Company</label>
                                @if ($session_company_id && !$is_holding)
                                    {{-- Locked: auto-selected from session --}}
                                    <input type="hidden" name="company_id" value="{{ $session_company_id }}">
                                    <input type="text" class="form-control" value="{{ session('company_name') }}"
                                        readonly style="background-color:#e9ecef; cursor:not-allowed;">
                                @else
                                    <select name="company_id" class="form-control" required>
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ $session_company_id == $company->id ? 'selected' : '' }}>
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
                                    {{-- Locked: auto-selected from session --}}
                                    <input type="hidden" name="division_id" value="{{ $session_division_id }}">
                                    <input type="text" class="form-control" value="{{ session('division_name') }}"
                                        readonly style="background-color:#e9ecef; cursor:not-allowed;">
                                @else
                                    <select name="division_id" class="form-control" required>
                                        <option value="">Select Division</option>
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->id }}"
                                                {{ $session_division_id == $division->id ? 'selected' : '' }}>
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
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Request Type</label>
                                <select name="request_type" id="request_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach ($ticketTypes as $type)
                                        <option value="{{ $type->ticket_type_id }}">{{ $type->ticket_type_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Urgency Level</label>
                                <select name="urgency_level" class="form-control" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="dynamic_fields"></div>

                    <!-- File Attachments -->
                    <div class="form-group mb-3">
                        <label>Attachments (Optional)</label>
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

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('tickets.history') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess


@section('scripts')
    <script>
        $(document).ready(function() {
            let selectedFiles = [];
            const maxFileSize = 10 * 1024 * 1024; // 10MB
            const allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif'];

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
                        message.sweetError(`File "${file.name}" is too large. Maximum size is 10MB.`);
                        continue;
                    }

                    // Validate file type
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    if (!allowedTypes.includes(fileExtension)) {
                        message.sweetError(`File type "${fileExtension}" is not allowed.`);
                        continue;
                    }

                    // Check if file already selected
                    const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (isDuplicate) {
                        message.infoMessage(`File "${file.name}" is already selected.`);
                        continue;
                    }

                    selectedFiles.push(file);
                    displayFile(file);
                }
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
            }

            // Remove file
            $(document).on('click', '.remove-file', function() {
                const fileId = $(this).data('file-id');
                selectedFiles = selectedFiles.filter(file => {
                    const itemFileId = $(this).closest('.file-item').data('file-id');
                    return itemFileId !== fileId;
                });
                $(this).closest('.file-item').remove();
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

            // Modify form submission to include files
            $('form').on('submit', function(e) {
                e.preventDefault();

                if (selectedFiles.length > 0) {
                    // Show loading
                    const submitBtn = $(this).find('button[type="submit"]');
                    const originalText = submitBtn.text();
                    submitBtn.prop('disabled', true).html(
                        '<i class="bx bx-loader-alt bx-spin"></i> Creating Ticket...');

                    // First create the ticket
                    const formData = new FormData(this);

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                // Ticket created, now upload attachments
                                uploadAttachments(response.data.id);
                            } else {
                                message.sweetError(response.message ||
                                    'Failed to create ticket');
                                submitBtn.prop('disabled', false).text(originalText);
                            }
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON?.message ||
                                'Failed to create ticket';
                            message.sweetError(error);
                            submitBtn.prop('disabled', false).text(originalText);
                        }
                    });
                } else {
                    // No files, submit normally
                    this.submit();
                }
            });

            function uploadAttachments(ticketId) {
                if (selectedFiles.length === 0) {
                    message.sweetSuccess('Ticket created successfully');
                    window.location.href = '{{ route('tickets.history') }}';
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
                            'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
                        },
                        success: function(response) {
                            uploadedCount++;
                            if (uploadedCount === totalFiles) {
                                message.sweetSuccess(
                                    'Ticket and attachments created successfully');
                                window.location.href = '{{ route('tickets.history') }}';
                            }
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON?.message ||
                                `Failed to upload ${file.name}`;
                            message.sweetError(error);
                            uploadedCount++;
                            if (uploadedCount === totalFiles) {
                                // Still redirect even if some uploads failed
                                window.location.href = '{{ route('tickets.history') }}';
                            }
                        }
                    });
                });
            }
        });
    </script>
@endsection
