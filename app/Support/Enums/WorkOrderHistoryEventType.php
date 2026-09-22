<?php

namespace App\Support\Enums;

enum WorkOrderHistoryEventType: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Assigned = 'assigned';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Started = 'started';
    case EnRoute = 'en_route';
    case Arrived = 'arrived';
    case Paused = 'paused';
    case Resumed = 'resumed';
    case RequestedApproval = 'requested_approval';
    case Approved = 'approved';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Reopened = 'reopened';
    case MaterialAdded = 'material_added';
    case EvidenceAdded = 'evidence_added';
    case SignatureAdded = 'signature_added';
    case ChecklistStarted = 'checklist_started';
    case ChecklistCompleted = 'checklist_completed';
}
