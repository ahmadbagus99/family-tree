<?php

namespace App\Console\Commands;

use App\Services\UserProvisioningService;
use Illuminate\Console\Command;

class SyncFamilyLoginUsers extends Command
{
    protected $signature = 'family:sync-login-users';

    protected $description = 'Buat/perbarui akun login untuk laki-laki yang sudah beristri (password default: admin untuk akun baru)';

    public function handle(UserProvisioningService $provisioning): int
    {
        $n = $provisioning->syncAllMarriedMales();
        $this->info("Sinkron selesai. Proses {$n} laki-laki berkeluarga.");

        return self::SUCCESS;
    }
}
