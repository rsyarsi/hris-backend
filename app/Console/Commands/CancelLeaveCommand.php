<?php

namespace App\Console\Commands;

use App\Models\Leave;
use App\Models\CatatanCuti;
use App\Models\LeaveHistory;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelLeaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:cancel-leave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel leave if it has not been approved by the manager on the request date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction(); // Start transaction here
        try {
            $today = Carbon::today(); // Get today's date without the time part
            $leaves = Leave::where('leave_type_id', 1)
                            ->whereDate('from_date', '=', $today)
                            ->whereIn('leave_status_id', [1, 2, 3])
                            ->get();
            foreach ($leaves as $leave) {
                $catatanCuti = CatatanCuti::where('leave_id', $leave->id)
                                        ->latest()
                                        ->first();

                $newCatatanCuti = CatatanCuti::create([
                    'adjustment_cuti_id' => null,
                    'leave_id' => $catatanCuti->leave_id,
                    'employee_id' => $catatanCuti->employee_id,
                    'quantity_awal' => $catatanCuti->quantity_akhir,
                    'quantity_akhir' => (int)$catatanCuti->quantity_akhir + (int)$catatanCuti->quantity_out,
                    'quantity_in' => $catatanCuti->quantity_out,
                    'quantity_out' => 0,
                    'type' => 'LEAVE',
                    'description' => 'CANCEL BY SYSTEM',
                    'batal' => 1,
                    'year' => $catatanCuti->year,
                ]);
                $leave->update([
                    'leave_status_id' => 10,
                    'quantity_cuti_awal' => $newCatatanCuti->quantity_akhir,
                    'sisa_cuti' => $newCatatanCuti->quantity_akhir
                ]);
                $historyData = [
                    'leave_id' => $leave->id,
                    'user_id' => auth()->id(),
                    'description' => 'LEAVE STATUS CANCEL BY SYSTEM',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'comment' => 'CANCEL BY SYSTEM',
                ];
                LeaveHistory::create($historyData);
            }
            DB::commit(); // Commit transaction if successful
            $this->info("Updated leave status for {$leaves} leaves.");
            Log::info($leaves);
            Log::info('Command executed successfully on ' . now()->format('l, Y-m-d') . ' at ' . now()->format('H:i:s') . ', Qty data updated = ');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on errors
            $this->error('Failed to update leave status: ' . $e->getMessage());
            Log::info($e->getMessage());
            return Command::FAILURE;
        }
    }
}
