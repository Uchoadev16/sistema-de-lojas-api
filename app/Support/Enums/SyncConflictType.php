<?php

namespace App\Support\Enums;

enum SyncConflictType: string
{
    case VersionConflict = 'version_conflict';
    case DeletedRemotely = 'deleted_remotely';
    case UpdatedRemotely = 'updated_remotely';
    case InvalidReference = 'invalid_reference';
    case PermissionConflict = 'permission_conflict';
}
