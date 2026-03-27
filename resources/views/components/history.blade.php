<!-- Ticket History Section -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Ticket History</h5>
    </div>
    <div class="card-body">
        <div id="historyList">
            <!-- History timeline will be loaded here -->
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 30px;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-item:last-child::after {
        display: none;
    }

    .timeline-item::after {
        content: '';
        position: absolute;
        left: 18px;
        top: 50px;
        bottom: -30px;
        width: 2px;
        background-color: #dee2e6;
    }

    .timeline-marker {
        min-width: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 20px;
        position: relative;
        z-index: 1;
    }

    .timeline-marker .badge {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .timeline-content {
        flex-grow: 1;
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 4px;
        border-left: 3px solid #dee2e6;
    }

    .timeline-title {
        margin: 0 0 5px 0;
        font-weight: 500;
        text-transform: capitalize;
    }

    .timeline-text {
        margin: 5px 0;
        color: #666;
        font-size: 14px;
    }

    .timeline-changes {
        margin: 10px 0 0 0;
        padding: 8px;
        background-color: #fff;
        border-radius: 3px;
        font-size: 13px;
    }

    .timeline-meta {
        margin-top: 8px;
        font-size: 12px;
        color: #999;
    }

    .badge-success {
        background-color: #198754;
    }

    .badge-info {
        background-color: #0dcaf0;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-primary {
        background-color: #0d6efd;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }

    .badge-secondary {
        background-color: #6c757d;
    }

    .badge-light {
        background-color: #f8f9fa;
        color: #212529;
        border: 1px solid #dee2e6;
    }
</style>