<?php

namespace App\Support\Enums;

enum NotificationType: string
{
    case WorkOrderCreated = 'work_order_created';
    case WorkOrderAssigned = 'work_order_assigned';
    case WorkOrderAccepted = 'work_order_accepted';
    case WorkOrderRejected = 'work_order_rejected';
    case WorkOrderOverdue = 'work_order_overdue';
    case WorkOrderCompleted = 'work_order_completed';
    case ScheduleChanged = 'schedule_changed';
    case MaintenanceDue = 'maintenance_due';
    case MaintenanceOverdue = 'maintenance_overdue';
    case ContractExpiring = 'contract_expiring';
    case BudgetSent = 'budget_sent';
    case BudgetApproved = 'budget_approved';
    case BudgetRejected = 'budget_rejected';
    case ReportReady = 'report_ready';
    case StockLow = 'stock_low';
    case SyncFailed = 'sync_failed';
    case PaymentReceived = 'payment_received';
    case InvoiceIssued = 'invoice_issued';
    case UserInvited = 'user_invited';
    case System = 'system';
}
