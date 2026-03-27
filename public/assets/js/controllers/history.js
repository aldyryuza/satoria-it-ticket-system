var TicketHistory = {
    ticketId: null,

    init: function (ticketId) {
        this.ticketId = ticketId;
        this.loadHistory();
    },

    loadHistory: function () {
        var self = this;
        $.ajax({
            url: '/api/tickets/' + this.ticketId + '/histories',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + Token.get()
            },
            success: function (response) {
                if (response.success && response.data.length > 0) {
                    self.renderHistory(response.data);
                } else {
                    $('#historyList').html('<div class="alert alert-info">No history records</div>');
                }
            },
            error: function () {
                toastr.error('Failed to load history');
            }
        });
    },

    renderHistory: function (data) {

        let html = `
        <table id="historyTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
    `;

        data.forEach(function (item) {

            let badge = '';
            if (item.action === 'created') {
                badge = '<span class="badge bg-success">Created</span>';
            } else if (item.action === 'updated') {
                badge = '<span class="badge bg-warning text-dark">Updated</span>';
            } else if (item.action === 'deleted') {
                badge = '<span class="badge bg-danger">Deleted</span>';
            } else {
                badge = '<span class="badge bg-secondary">' + item.action + '</span>';
            }

            let changesHtml = '';

            if (item.changes) {
                let changes = typeof item.changes === 'string'
                    ? JSON.parse(item.changes)
                    : item.changes;

                for (let field in changes) {
                    changesHtml += `
                    <div>
                        <strong>${field}</strong>:
                        <span class="text-danger">${changes[field].old ?? '-'}</span>
                        →
                        <span class="text-success">${changes[field].new ?? '-'}</span>
                    </div>
                `;
                }
            }

            html += `
            <tr>
                <td>${moment(item.created_at).format('DD MMM YYYY HH:mm')}</td>
                <td>${badge}</td>
                <td>
                    ${item.description ?? '-'}
                    <div class="small mt-1">${changesHtml}</div>
                </td>
                <td>${item.user ?? 'System'}</td>
            </tr>
        `;
        });

        html += `</tbody></table>`;

        $('#historyList').html(html);

        // INIT DATATABLE
        $('#historyTable').DataTable({
            pageLength: 5,
            // order: [[0, 'desc']],
            responsive: true
        });
    },

    getActionBadgeClass: function (action) {
        var classes = {
            'created': 'badge-success',
            'submitted': 'badge-info',
            'approved': 'badge-success',
            'rejected': 'badge-danger',
            'assigned': 'badge-primary',
            'in_progress': 'badge-warning',
            'done': 'badge-success',
            'closed': 'badge-secondary',
            'updated': 'badge-info',
            'commented': 'badge-light'
        };

        return classes[action] || 'badge-secondary';
    },

    getActionIcon: function (action) {
        var icons = {
            'created': 'bx bx-plus-circle',
            'submitted': 'bx bx-send',
            'approved': 'bx bx-check-circle',
            'rejected': 'bx bx-x-circle',
            'assigned': 'bx bx-user-check',
            'in_progress': 'bx bx-loader',
            'done': 'bx bx-check-double',
            'closed': 'bx bx-lock-alt',
            'updated': 'bx bx-edit',
            'commented': 'bx bx-comment-dots'
        };

        return icons[action] || 'bx bx-info-circle';
    }
};
