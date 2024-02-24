<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Rules\DateSmallerThan;
use Illuminate\Support\Facades\Validator;
use App\Services\OrderOvertime\OrderOvertimeServiceInterface;

class OrderOvertimeController extends Controller
{
    use ResponseAPI;

    private $orderOvertimeService;

    public function __construct(OrderOvertimeServiceInterface $orderOvertimeService)
    {
        $this->middleware('auth:api');
        $this->orderOvertimeService = $orderOvertimeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $unit = $request->input('unit');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $status = $request->input('status');
            $orderOvertimes = $this->orderOvertimeService->index($perPage, $search, $period_1, $period_2, $unit, $status);
            return $this->success('Order overtime retrieved successfully', $orderOvertimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function indexSubOrdinate(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $unit = $request->input('unit');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $status = $request->input('status');
            $orderOvertimes = $this->orderOvertimeService->indexSubOrdinate($perPage, $search, $period_1, $period_2, $unit, $status);
            return $this->success('Order overtime subordinate retrieved successfully', $orderOvertimes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function indexSubOrdinateMobile(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            $orderOvertimes = $this->orderOvertimeService->indexSubOrdinateMobile($employeeId);
            return response()->json([
                'message' => 'Order overtime subordinate mobile retrieved successfully!',
                'success' => true,
                'code' => 200,
                'data' => $orderOvertimes
            ], 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $employee = Employee::find($request->employee_id);
            $unitId = $employee->unit_id ?? null;
            $rules = [
                'employee_staff_id' => 'required|exists:employees,id',
                'employee_entry_id' => 'required|exists:employees,id',
                'from_date' => [
                    'required',
                    'date',
                    new DateSmallerThan('to_date')
                ],
                'to_date' => 'required|date',
                'note_order' => 'required|string',
                'note_overtime' => 'required|string',
                'type' => 'required|string|max:255',
                'holiday' => 'required|in:0,1',
                'status' => 'required|in:OPEN,APPROVE,REJECT',
            ];
            if ($request->type === 'ONCALL' && $unitId !== 19 || $request->type === 'CITO' && $unitId !== 19) {
                return response()->json([
                    'message' => 'Validation Error!',
                    'error' => false,
                    'code' => 422, // Use a more appropriate HTTP status code
                    'data' => [
                        'type' => [
                            'Type ONCALL/CITO hanya untuk unit HEMODIALISA.'
                        ]
                    ],
                ], 422);
            }
            // if ($request->isMethod('post')) {
            //     $rules['from_date'][] = new UniqueOvertimeDateRange();
            // }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'error' => false,
                    'code' => 422, // Use a more appropriate HTTP status code
                    'data' => $validator->errors(),
                ], 422);
            }
            $overtime = $this->orderOvertimeService->store();
            return response()->json([
                'message' => $overtime['message'],
                'error' => $overtime['error'],
                'code' => $overtime['code'],
                'data' => $overtime['data']
            ], $overtime['code']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function storeMobile(Request $request)
    {
        try {
            $employee = Employee::find($request->employee_id);
            $unitId = $employee->unit_id ?? null;
            $rules = [
                'employee_staff_id' => 'required|exists:employees,id',
                'employee_entry_id' => 'required|exists:employees,id',
                'from_date' => [
                    'required',
                    'date',
                    new DateSmallerThan('to_date')
                ],
                'to_date' => 'required|date',
                'note_order' => 'required|string',
                'note_overtime' => 'required|string',
                'type' => 'required|string|max:255',
                'holiday' => 'required|in:0,1',
                'status' => 'required|in:OPEN,APPROVE,REJECT',
            ];
            if ($request->type === 'ONCALL' && $unitId !== 19 || $request->type === 'CITO' && $unitId !== 19) {
                return response()->json([
                    'message' => 'Type ONCALL/CITO hanya untuk unit HEMODIALISA.',
                    'success' => false,
                    'code' => 200, // Use a more appropriate HTTP status code
                    'data' => 'Type ONCALL/CITO hanya untuk unit HEMODIALISA.',
                ], 200);
            }
            // if ($request->isMethod('post')) {
            //     $rules['from_date'][] = new UniqueOvertimeDateRange();
            // }
            $validator = Validator::make($request->all(), $rules);
            $errorMessages = collect($validator->errors()->all())->implode(', '); // Collect and join errors with commas
            if ($validator->fails()) {
                return response()->json([
                    'message' => $errorMessages,
                    'success' => false,
                    'code' => 200, // Use a more appropriate HTTP status code
                    'data' => $errorMessages,
                ], 200);
            }
            $overtime = $this->orderOvertimeService->storeMobile($request->all());
            return response()->json([
                'message' => $overtime['message'],
                'success' => $overtime['success'],
                'code' => $overtime['code'],
                'data' => $overtime['data']
            ], $overtime['code']);
            // return $this->success('Overtime created successfully', $overtime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
                'code' => 200,
                'data' => $e->getMessage()
            ], 200);
        }
    }

    public function show($id)
    {
        try {
            $overtime = $this->orderOvertimeService->show($id);
            if (!$overtime) {
                return $this->error('Order Overtime not found', 404);
            }
            return $this->success('Order Overtime retrieved successfully', $overtime);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::find($request->employee_id);
            $unitId = $employee->unit_id ?? null;
            $rules = [
                'employee_staff_id' => 'required|exists:employees,id',
                'employee_entry_id' => 'required|exists:employees,id',
                'from_date' => [
                    'required',
                    'date',
                    new DateSmallerThan('to_date')
                ],
                'to_date' => 'required|date',
                'note_order' => 'required|string',
                'note_overtime' => 'required|string',
                'type' => 'required|string|max:255',
                'holiday' => 'required|in:0,1',
                'status' => 'required|in:OPEN,APPROVE,REJECT',
            ];
            if ($request->type === 'ONCALL' && $unitId !== 19 || $request->type === 'CITO' && $unitId !== 19) {
                return response()->json([
                    'message' => 'Validation Error!',
                    'error' => false,
                    'code' => 422, // Use a more appropriate HTTP status code
                    'data' => [
                        'type' => [
                            'Type ONCALL/CITO hanya untuk unit HEMODIALISA.'
                        ]
                    ],
                ], 422);
            }
            // if ($request->isMethod('post')) {
            //     $rules['from_date'][] = new UniqueOvertimeDateRange();
            // }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'error' => false,
                    'code' => 422, // Use a more appropriate HTTP status code
                    'data' => $validator->errors(),
                ], 422);
            }
            $orderOvertime = $this->orderOvertimeService->update($id, $request->all());
            if (!$orderOvertime) {
                return response()->json([
                    'message' => $orderOvertime['message'],
                    'error' => $orderOvertime['error'],
                    'code' => $orderOvertime['code'],
                    'data' => $orderOvertime['data']
                ], $orderOvertime['code']);
            }
            return response()->json([
                'message' => $orderOvertime['message'],
                'error' => $orderOvertime['error'],
                'code' => $orderOvertime['code'],
                'data' => $orderOvertime['data']
            ], $orderOvertime['code']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $overtime = $this->orderOvertimeService->destroy($id);
            if (!$overtime) {
                return $this->error('Order Overtime not found', 404);
            }
            return $this->success('Order Overtime deleted successfully, id : '.$overtime->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
