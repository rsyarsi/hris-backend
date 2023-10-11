<?php
namespace App\Services\ShiftSchedule;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use App\Services\Leave\LeaveService;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Repositories\ShiftSchedule\ShiftScheduleRepositoryInterface;

class ShiftScheduleService implements ShiftScheduleServiceInterface
{
    private $repository;
    private $leaveService;

    public function __construct(ShiftScheduleRepositoryInterface $repository, LeaveService $leaveService)
    {
        $this->repository = $repository;
        $this->leaveService = $leaveService;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        // $id = $data['shift_exchange_id'];
        // $leave = $this->leaveService->show($id);
        // $data['late_note'] = $leave->leaveType->name;
        $data['created_user_id'] = auth()->id();
        $data['setup_user_id'] = auth()->id();
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function storeMultiple(array $data)
    {
        $createdUserId = auth()->id();
        $setupUserId = auth()->id();

        // Parse the start_date and end_date
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        $shiftSchedules = [];

        // Loop through the date range
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $ulid = Ulid::generate(); // Generate a ULID
            $shiftScheduleData = [
                'id' => Str::lower($ulid),
                'employee_id' => $data['employee_id'],
                'shift_id' => $data['shift_id'],
                'date' => $date->format('Y-m-d'),
                'time_in' => $date->format('Y-m-d') . ' ' . $data['time_in'],
                'time_out' => $date->format('Y-m-d') . ' ' . $data['time_out'],
                'late_note' => null,
                'shift_exchange_id' => null,
                'user_exchange_id' => null,
                'user_exchange_at' => null,
                'created_user_id' => $createdUserId,
                'updated_user_id' => null, // You may need to set this as per your requirements
                'setup_user_id' => $setupUserId,
                'setup_at' => now(), // You can customize the setup_at value
                'period' => $data['period'],
                'leave_note' => null,
                'holiday' => $data['holiday'],
                'night' => $data['night'],
                'national_holiday' => $data['national_holiday'],
            ];

            $shiftSchedules[] = $shiftScheduleData;
        }

        // Insert the shift schedules into the database
        $this->repository->storeMultiple($shiftSchedules);

        return $shiftSchedules; // Optionally, you can return the created records
    }
}
