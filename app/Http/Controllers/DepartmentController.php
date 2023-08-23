<?php

namespace App\Http\Controllers;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;

class DepartmentController extends Controller
{
    use ResponseAPI;

    private $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;       
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        try { 
            $data =  $this->departmentRepository->allDepartments();
            return $this->success(
                "Data Berhasil ditemukan.",
                $data,200);

        } catch(\Exception $e) {
           
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        DB::beginTransaction();
        try { 
            $this->departmentRepository->storeDepartment($request);
            DB::commit();
            return $this->success(
                "Data Berhasil ditambahkan.",
                [],200);

        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try { 
            $data = $this->departmentRepository->findDepartment($id);
           
            return $this->success(
                "Data Berhasil ditemukan.",
                $data,200);

        } catch(\Exception $e) {
           
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        DB::beginTransaction();
        try { 
            $this->departmentRepository->updateDepartment($request, $id);
            DB::commit();
            return $this->success(
                "Data Berhasil dirubah.",
                [],200);

        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getdata()
    {
        try { 
          
            return $this->success(
                "Data Berhasil ditemukan.",
                [],200);

        } catch(\Exception $e) {
           
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
