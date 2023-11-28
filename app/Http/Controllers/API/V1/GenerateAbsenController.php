<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Models\GenerateAbsen;
use App\Http\Controllers\Controller;
use App\Http\Requests\{AbsenFromMobileRequest, GenerateAbsenRequest};
use App\Services\GenerateAbsen\GenerateAbsenServiceInterface;
use Carbon\Carbon;

class GenerateAbsenController extends Controller
{
    use ResponseAPI;

    private $generateAbsenService;

    public function __construct(GenerateAbsenServiceInterface $generateAbsenService)
    {
        $this->middleware('auth:api');
        $this->generateAbsenService = $generateAbsenService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period_1 = $request->input('period_1');
            $period_2 = $request->input('period_2');
            $unit = $request->input('unit');
            $generateabsens = $this->generateAbsenService->index($perPage, $search, $period_1, $period_2, $unit);
            return $this->success('Generate Absens retrieved successfully', $generateabsens);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(GenerateAbsenRequest $request)
    {
        try {
            $data = $request->validated();
            $generateabsen = $this->generateAbsenService->store($data);
            return $this->success('Generate Absen created successfully', $generateabsen, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $generateabsen = $this->generateAbsenService->show($id);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen retrieved successfully', $generateabsen);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(GenerateAbsenRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $generateabsen = $this->generateAbsenService->update($id, $data);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen updated successfully', $generateabsen, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $generateabsen = $this->generateAbsenService->destroy($id);
            if (!$generateabsen) {
                return $this->error('Generate Absen not found', 404);
            }
            return $this->success('Generate Absen deleted successfully, id : '.$generateabsen->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function executeStoredProcedure(GenerateAbsenRequest $request)
    {
        try {
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $generateAbsen = GenerateAbsen::executeStoredProcedure($period1, $period2);
            return $this->success('Generate Absen successfully', $generateAbsen, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function absenFromMobile(AbsenFromMobileRequest $request)
    {
        try {
            $data = $request->validated();
            // $generateabsen = $this->generateAbsenService->absenFromMobile($data);
            // return response()->json([
            //     'message' => 'Absen Berhasil!',
            //     'success' => 'true',
            //     'code' => 200,
            //     'data' => $generateabsen,
            // ]);
            $ipAddress = str_replace('.', '', $request->input('Ip_address'));
            $ipAddressServer = '17216';
            // Extract the first 5 characters from $ipAddress
            $firstFiveCharacters = substr($ipAddress, 0, 5);
            if ($firstFiveCharacters == substr($ipAddressServer, 0, 5)) {
                $generateabsen = $this->generateAbsenService->absenFromMobile($data);
                return response()->json([
                    'message' => $generateabsen['message'],
                    'success' => 'true',
                    'code' => 200,
                    'data' => $generateabsen['data'],
                ]);
            } else {
                return response()->json([
                    'message' => 'Anda tidak berada di jaringan yang ditentukan!',
                    'success' => 'false',
                    'code' => 404,
                    'data' => null,
                ]);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
