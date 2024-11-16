<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class UserManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Settings';
}
