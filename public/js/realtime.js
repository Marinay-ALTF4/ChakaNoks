// Real-time Updates System
class RealTimeUpdates {
    constructor() {
        this.updateInterval = 5000; // Update every 5 seconds
        this.intervals = {};
    }

    // Start real-time updates for workflow stats
    startWorkflowStats(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;

        this.intervals['workflowStats'] = setInterval(() => {
            this.fetchWorkflowStats(element);
        }, this.updateInterval);

        // Initial load
        this.fetchWorkflowStats(element);
    }

    fetchWorkflowStats(container) {
        const baseUrl = window.location.origin;
        fetch(baseUrl + '/api/workflow-stats')
            .then(response => response.json())
            .then(data => {
                if (data.stats) {
                    this.updateStatsCards(container, data.stats);
                }
            })
            .catch(error => console.error('Error fetching workflow stats:', error));
    }

    updateStatsCards(container, stats) {
        // Update pending PRs
        const pendingPRs = container.querySelector('[data-stat="pendingPurchaseRequests"]');
        if (pendingPRs) {
            const currentCount = parseInt(pendingPRs.textContent.trim());
            const newCount = stats.pendingPurchaseRequests || 0;
            if (currentCount !== newCount) {
                pendingPRs.textContent = newCount;
                this.animateUpdate(pendingPRs);
            }
        }

        // Update supplier pending
        const supplierPending = container.querySelector('[data-stat="pendingSupplierOrders"]');
        if (supplierPending) {
            const currentCount = parseInt(supplierPending.textContent.trim());
            const newCount = stats.pendingSupplierOrders || 0;
            if (currentCount !== newCount) {
                supplierPending.textContent = newCount;
                this.animateUpdate(supplierPending);
            }
        }

        // Update ready for delivery
        const readyDelivery = container.querySelector('[data-stat="readyForDelivery"]');
        if (readyDelivery) {
            const currentCount = parseInt(readyDelivery.textContent.trim());
            const newCount = stats.readyForDelivery || 0;
            if (currentCount !== newCount) {
                readyDelivery.textContent = newCount;
                this.animateUpdate(readyDelivery);
            }
        }

        // Update scheduled deliveries
        const scheduled = container.querySelector('[data-stat="scheduledDeliveries"]');
        if (scheduled) {
            const currentCount = parseInt(scheduled.textContent.trim());
            const newCount = stats.scheduledDeliveries || 0;
            if (currentCount !== newCount) {
                scheduled.textContent = newCount;
                this.animateUpdate(scheduled);
            }
        }
    }

    // Start real-time updates for purchase requests
    startPurchaseRequests(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;

        this.intervals['purchaseRequests'] = setInterval(() => {
            this.fetchPurchaseRequests(element);
        }, this.updateInterval);

        this.fetchPurchaseRequests(element);
    }

    fetchPurchaseRequests(container) {
        const baseUrl = window.location.origin;
        fetch(baseUrl + '/api/purchase-requests')
            .then(response => response.json())
            .then(data => {
                if (data.requests) {
                    this.updatePurchaseRequestsList(container, data.requests);
                }
            })
            .catch(error => console.error('Error fetching purchase requests:', error));
    }

    updatePurchaseRequestsList(container, requests) {
        const listContainer = container.querySelector('.purchase-requests-list');
        if (!listContainer) return;

        // Store current HTML to compare
        const currentHTML = listContainer.innerHTML;
        
        let newHTML = '';
        if (requests.length > 0) {
            requests.slice(0, 3).forEach(request => {
                const baseUrl = window.location.origin;
                newHTML += `
                    <li class="small">
                        <strong>${this.escapeHtml(request.item_name)}</strong> (${request.quantity}) - Branch ${this.escapeHtml(request.branch_name || 'N/A')}
                        <div class="mt-1">
                            <a href="${baseUrl}/Central_AD/approvePurchaseRequest/${request.id}" class="btn btn-sm btn-success me-1">Approve</a>
                            <a href="${baseUrl}/Central_AD/rejectPurchaseRequest/${request.id}" class="btn btn-sm btn-danger">Reject</a>
                        </div>
                    </li>
                `;
            });
        } else {
            newHTML = '<li class="small text-muted">No pending requests</li>';
        }

        if (currentHTML !== newHTML) {
            listContainer.innerHTML = newHTML;
            this.showNotification('Purchase requests updated', 'info');
        }
    }

    // Start real-time updates for supplier orders
    startSupplierOrders(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;

        this.intervals['supplierOrders'] = setInterval(() => {
            this.fetchSupplierOrders(element);
        }, this.updateInterval);

        this.fetchSupplierOrders(element);
    }

    fetchSupplierOrders(container) {
        const baseUrl = window.location.origin;
        fetch(baseUrl + '/api/supplier-orders')
            .then(response => response.json())
            .then(data => {
                if (data.orders) {
                    this.updateSupplierOrdersTable(container, data.orders);
                }
            })
            .catch(error => console.error('Error fetching supplier orders:', error));
    }

    updateSupplierOrdersTable(container, orders) {
        const tbody = container.querySelector('tbody');
        if (!tbody) return;

        const currentHTML = tbody.innerHTML;
        let newHTML = '';

        if (orders.length > 0) {
            orders.forEach(order => {
                newHTML += `
                    <tr>
                        <td>#${order.id}</td>
                        <td>${this.escapeHtml(order.item_name)}</td>
                        <td>${order.quantity} ${this.escapeHtml(order.unit || 'units')}</td>
                        <td>₱${parseFloat(order.unit_price || 0).toFixed(2)}</td>
                        <td><strong>₱${parseFloat(order.total_price || 0).toFixed(2)}</strong></td>
                        <td>${this.escapeHtml(order.branch_name)}</td>
                        <td>${this.formatDate(order.order_date)}</td>
                        <td>
                            <a href="${window.location.origin}/supplier/confirm-order/${order.id}" class="btn btn-sm btn-success">Confirm</a>
                        </td>
                    </tr>
                `;
            });
        } else {
            newHTML = '<tr><td colspan="8" class="text-center text-muted">No pending orders for supplier confirmation.</td></tr>';
        }

        if (currentHTML !== newHTML) {
            tbody.innerHTML = newHTML;
            this.showNotification('Supplier orders updated', 'info');
        }
    }

    // Start real-time updates for ready for delivery orders
    startReadyForDelivery(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;

        this.intervals['readyForDelivery'] = setInterval(() => {
            this.fetchReadyForDelivery(element);
        }, this.updateInterval);

        this.fetchReadyForDelivery(element);
    }

    fetchReadyForDelivery(container) {
        const baseUrl = window.location.origin;
        fetch(baseUrl + '/api/ready-for-delivery')
            .then(response => response.json())
            .then(data => {
                if (data.orders) {
                    this.updateReadyOrdersTable(container, data.orders);
                }
            })
            .catch(error => console.error('Error fetching ready orders:', error));
    }

    updateReadyOrdersTable(container, orders) {
        const tbody = container.querySelector('tbody');
        if (!tbody) return;

        const currentHTML = tbody.innerHTML;
        let newHTML = '';

        if (orders.length > 0) {
            orders.forEach(order => {
                newHTML += `
                    <tr>
                        <td>#${order.id}</td>
                        <td>${this.escapeHtml(order.item_name)}</td>
                        <td>${order.quantity} ${this.escapeHtml(order.unit || 'units')}</td>
                        <td>${this.escapeHtml(order.supplier_name)}</td>
                        <td>${this.escapeHtml(order.branch_name)}</td>
                        <td>₱${parseFloat(order.total_price || 0).toFixed(2)}</td>
                        <td>${order.prepared_at ? this.formatDate(order.prepared_at) : 'N/A'}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal${order.id}">
                                Schedule
                            </button>
                        </td>
                    </tr>
                `;
            });
        } else {
            newHTML = '<tr><td colspan="8" class="text-center text-muted">No orders ready for delivery.</td></tr>';
        }

        if (currentHTML !== newHTML) {
            tbody.innerHTML = newHTML;
            this.showNotification('Ready orders updated', 'info');
        }
    }

    // Utility functions
    animateUpdate(element) {
        element.style.transition = 'all 0.3s ease';
        element.style.transform = 'scale(1.2)';
        element.style.color = '#28a745';
        
        setTimeout(() => {
            element.style.transform = 'scale(1)';
            element.style.color = '';
        }, 300);
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    // Stop all intervals
    stopAll() {
        Object.values(this.intervals).forEach(interval => clearInterval(interval));
        this.intervals = {};
    }
}

// Initialize real-time updates
const realTime = new RealTimeUpdates();

// Auto-start updates when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Show real-time indicator
    const indicator = document.getElementById('realtime-indicator');
    if (indicator) {
        indicator.style.display = 'inline-block';
    }

    // Start workflow stats updates if element exists
    if (document.getElementById('workflow-stats-container')) {
        realTime.startWorkflowStats('workflow-stats-container');
    }

    // Start purchase requests updates if element exists
    if (document.getElementById('purchase-requests-container')) {
        realTime.startPurchaseRequests('purchase-requests-container');
    }

    // Start supplier orders updates if element exists
    if (document.getElementById('supplier-orders-container')) {
        realTime.startSupplierOrders('supplier-orders-container');
    }

    // Start ready for delivery updates if element exists
    if (document.getElementById('ready-for-delivery-container')) {
        realTime.startReadyForDelivery('ready-for-delivery-container');
    }
});

// Stop updates when page is hidden (to save resources)
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        realTime.stopAll();
    } else {
        // Restart when page becomes visible
        location.reload();
    }
});

