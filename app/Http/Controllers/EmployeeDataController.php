<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDataRequest;
use App\Services\TracktikEmployeeDataService;
use App\Support\Response;
use Illuminate\Http\Response as HttpResponse;

class EmployeeDataController extends Controller
{

    /**
     * EmployeeDataController constructor.
     * @param TracktikEmployeeDataService $service
     */
    public function __construct(private TracktikEmployeeDataService $service)
    {
    }


    /**
     * @param EmployeeDataRequest $request
     * @return HttpResponse
     */
    public function process(EmployeeDataRequest $request): HttpResponse
    {

        if ($request->filled('payload.employee_id')) {
            return Response::success($this->service->update($request->validated(), true));
        }

        return Response::success($this->service->create($request->validated()));

    }
}
