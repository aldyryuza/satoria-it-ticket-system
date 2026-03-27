# Ticket Attachments & History Documentation

## Overview

This guide explains how to integrate file attachments and ticket history tracking into your ticket system.

## Features

### 1. File Attachments

- Upload files to tickets (max 10MB per file)
- Download uploaded files
- Delete files (only by uploader or admin)
- Drag-and-drop support
- File type icons for different formats

### 2. Ticket History

- Automatic tracking of all ticket actions
- Timeline view of changes
- Shows who made changes and when
- Tracks old and new values for modifications
- Supports multiple action types (created, submitted, approved, assigned, etc.)

### 3. Comments (Bonus)

- Add comments to tickets
- View all comments in order
- Delete own comments (admins can delete any)

## API Endpoints

### Attachments

- **Upload**: `POST /api/tickets/{ticketId}/attachments/upload`
- **List**: `GET /api/tickets/{ticketId}/attachments`
- **Download**: `GET /api/attachments/{attachmentId}/download`
- **Delete**: `DELETE /api/attachments/{attachmentId}`

### Ticket History

- **Get History**: `GET /api/tickets/{ticketId}/histories`

### Comments

- **Get Comments**: `GET /api/tickets/{ticketId}/comments`
- **Post Comment**: `POST /api/tickets/{ticketId}/comments`
- **Delete Comment**: `DELETE /api/comments/{commentId}`

## Database Migrations

Two migrations have been created:

1. **2026_03_25_000001_add_columns_to_ticket_attachments_table.php**
    - Adds: file_name, file_size, mime_type columns

Run migrations:

```bash
php artisan migrate
```

## File Storage Configuration

Files are stored in `/storage/app/public/tickets/{ticketId}/`

Make sure your `.env` has:

```
FILESYSTEM_DISK=public
```

Create symbolic link:

```bash
php artisan storage:link
```

## Implementation in Views

### Basic Template Integration

```blade
<!-- Include Attachment Component -->
@include('components.attachments')

<!-- Include History Component -->
@include('components.history')

<!-- Include Scripts -->
<script src="{{ asset('assets/js/controllers/attachment.js') }}"></script>
<script src="{{ asset('assets/js/controllers/history.js') }}"></script>

<!-- Initialize in your script -->
<script>
$(document).ready(function() {
    const ticketId = {{ $ticket->id }};

    // Initialize attachments
    Attachment.init(ticketId);

    // Initialize history
    TicketHistory.init(ticketId);
});
</script>
```

## Creating History Records

Use the HistoryController to track changes:

```php
use App\Http\Controllers\api\TicketHistoryController;

$historyController = app(TicketHistoryController::class);

// Create a history record
$historyController->createHistory(
    ticketId: $ticket->id,
    action: 'assigned',
    description: 'Ticket assigned to John Doe',
    oldValue: null,
    newValue: ['assigned_to' => 'john@example.com']
);
```

Or directly using the model:

```php
use App\Models\TicketHistory;

TicketHistory::create([
    'ticket_id' => $ticket->id,
    'user_id' => auth()->id(),
    'action' => 'approved',
    'description' => 'Ticket approved',
    'old_value' => ['status' => 'pending'],
    'new_value' => ['status' => 'approved']
]);
```

## JavaScript Usage

### Attachment.js

```javascript
// Initialize
Attachment.init(ticketId);

// Upload files programmatically
var files = document.getElementById("fileInput").files;
Attachment.uploadFiles(files);

// Load/reload attachments
Attachment.loadAttachments();

// Delete attachment
Attachment.deleteAttachment(attachmentId);
```

### History.js

```javascript
// Initialize
TicketHistory.init(ticketId);

// Reload history
TicketHistory.loadHistory();
```

## Permission Controls

### In Blade Templates

```blade
<!-- Only show upload if user has create permission -->
@canAccess('tickets/attachments', 'create')
    <!-- Upload UI -->
@endcanAccess

<!-- Conditional delete button -->
@canAccess('tickets/attachments', 'delete')
    <button class="btn btn-danger">Delete</button>
@endcanAccess
```

## Custom Styling

### Dropzone Styling

```css
.drop-zone {
    border: 2px dashed #dee2e6;
    padding: 30px;
    text-align: center;
}

.drop-zone.dragover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}
```

### Timeline Styling

```css
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 30px;
}
```

## Events Tracking

Common history actions:

- `created` - Ticket created
- `submitted` - Submitted for approval
- `approved` - Approved by manager
- `rejected` - Rejected
- `assigned` - Assigned to worker
- `in_progress` - Work started
- `done` - Work completed
- `closed` - Ticket closed
- `updated` - Details updated
- `commented` - Comment added

## Error Handling

All API endpoints return JSON responses:

```json
{
    "success": true/false,
    "message": "Description of result",
    "data": {}
}
```

JavaScript error handling:

```javascript
$.ajax({
    // ... config
    success: function (response) {
        if (response.success) {
            toastr.success("Success!");
        } else {
            toastr.error(response.message);
        }
    },
    error: function (xhr) {
        toastr.error("An error occurred");
    },
});
```

## Troubleshooting

### Files not uploading

1. Check `FILESYSTEM_DISK=public` in `.env`
2. Run `php artisan storage:link`
3. Check folder permissions in `storage/app/public`

### History not showing

1. Verify `TicketHistory` records exist in database
2. Check user relationships are loaded
3. Verify ticket ID is correct

### Attachments not showing

1. Verify files exist in storage path
2. Check file paths in database
3. Verify `uploaded_by` user still exists

## Security Considerations

1. **File Upload Validation**
    - Max file size: 10MB
    - Validate MIME types
    - Store outside web root when possible

2. **Permission Checks**
    - Only show buttons if user has permission
    - API endpoints verify permissions
    - Delete requires ownership or admin role

3. **File Download Security**
    - Verify ticket access before allowing download
    - Log download history

## Next Steps

1. Configure filesystem permission settings
2. Test file upload with various file types
3. Set up history logging for all ticket actions
4. Customize styling to match your design
5. Configure email notifications for assigned tickets
