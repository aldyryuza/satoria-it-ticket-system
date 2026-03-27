<!-- Attachments Section -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">File Attachments</h5>
    </div>
    <div class="card-body">
        @canAccess('tickets/attachments', 'create')
        <div id="dropZone" class="drop-zone">
            <div class="drop-zone-content">
                <i class="bx bx-cloud-upload drop-zone-icon"></i>
                <p class="drop-zone-text">Drag and drop files here or click to browse</p>
                <input type="file" id="fileInput" multiple hidden>
            </div>
            <input type="hidden" name="can_create_attachment" value="1">
        </div>
        @endcanAccess

        <div id="attachmentsList" class="attachments-list mt-3">
            <!-- Attachments will be loaded here -->
        </div>
    </div>
</div>

<style>
    .drop-zone {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .drop-zone:hover,
    .drop-zone.dragover {
        border-color: #0d6efd;
        background-color: #e7f1ff;
    }

    .drop-zone-icon {
        font-size: 48px;
        color: #0d6efd;
    }

    .drop-zone-text {
        margin-top: 10px;
        color: #6c757d;
        font-size: 14px;
    }

    .attachment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-bottom: 10px;
        background-color: #fff;
    }

    .attachment-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
    }

    .attachment-info i {
        font-size: 24px;
        margin-right: 12px;
        color: #0d6efd;
    }

    .attachment-details {
        flex-grow: 1;
    }

    .attachment-name {
        margin: 0;
        font-weight: 500;
        color: #212529;
        word-break: break-word;
    }

    .attachment-actions {
        display: flex;
        gap: 5px;
    }

    .attachments-list {
        max-height: 300px;
        overflow-y: auto;
    }
</style>
