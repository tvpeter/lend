<?php

namespace App\Console\Commands;

use App\Services\BambooService;
use App\User;
use Illuminate\Console\Command;

class SyncBambooStaffs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bamboo:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync bamboo staffs with staff loan user database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(BambooService $bambooService)
    {
        $start = now();

        $this->info('Syncing users from bamboo...');

        $employees = $bambooService->employeesDirectory();

        $supervisors = [];

        foreach ($employees as $employee) {
            if ($employee['workEmail']) {

                if ($employee['supervisor'] && !in_array($employee['supervisor'], $supervisors)) {
                    $supervisors[] = $employee['supervisor'];
                }

                $user = User::firstOrCreate([
                    'email' => $employee['workEmail'],
                ], [
                    'name'            => $employee['displayName'],
                    'branch_location' => $employee['location'],
                    'password'        => 'password',
                    'bamboo_id'       => $employee['id'],
                    'department'      => $employee['department'],
                ]);

                if ($user->wasRecentlyCreated) {
                    $this->info("Creating {$employee['displayName']}");
                }
            }
        }

        $this->info("Found " . count($supervisors) . " supervisors");

        User::where('email', '!=', 'superadmin@renmoney.com')->with('roles')->get()->each(function ($user) use ($employees, $supervisors) {

            $bambooUser = $employees->where('smallEmail', strtolower($user->email))->first();

            if ($bambooUser) {
                if (!$user->active || !$user->bamboo_id || $user->email != $bambooUser['workEmail'] || $user->name != $bambooUser['displayName'] || $user->branch_location != $bambooUser['location'] || $user->department != $bambooUser['department']) {

                    $this->info("Updated $user->name");

                    $user->update([
                        'active'          => true,
                        'bamboo_id'       => $bambooUser['id'],
                        'email'           => $bambooUser['workEmail'],
                        'name'            => $bambooUser['displayName'],
                        'branch_location' => $bambooUser['location'],
                        'department'      => $bambooUser['department'],
                    ]);
                }

                if (in_array($user->name, $supervisors) && $user->roles->count() == 0) {

                    $this->info("Assigned line manager to $user->name");

                    $user->assignRole('line-manager');
                }

                if ($bambooUser['supervisor'] && !$user->line_manager_id) {

                    $supervisor = $employees->where('displayName', $bambooUser['supervisor'])->first();

                    $supervisor = User::where('email', $supervisor['workEmail'])->first();

                    if ($supervisor) {

                        $user->update([
                            'line_manager_id' => $supervisor->id,
                        ]);

                        $this->info("Updated line manager of $user->name");
                    }
                }

            } else {
                if ($user->active) {

                    $this->info("Deactivated $user->name");

                    $user->update(['active' => false]);
                }
            }
        });

        $this->info("Done in " . now()->diffInSeconds($start) . " seconds");
    }
}
