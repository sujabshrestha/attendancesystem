<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Candidate\Http\Requests\CandidateStoreRequest;

use Candidate\Http\Resources\CandidateResource;
use Candidate\Repositories\candidate\CandidateInterface;
use Employer\Models\Company;
use Illuminate\Support\Str;

class ApiCandidateController extends Controller
{
    protected $response, $candidate;

    public function __construct(ResponseService $response, CandidateInterface $candidate)
    {
        $this->response = $response;
        $this->candidate = $candidate;
    }

    public function store(CandidateStoreRequest $request, $id){
        try{
            $candidate = $this->candidate->store($request, $id);
            if($candidate){
                return $this->response->responseSuccessMsg("Successfully Created", 200);
            }

        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }


    public function update(Request $request, $id){
        try{
            // dd($request->all());
            $candidate = $this->candidate->update($request, $id);
            if($candidate){
                return $this->response->responseSuccessMsg("Successfully Created", 200);
            }

        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }


    public function getCandidatesByCompany($id){
        // try{

            $candidates = Candidate::with([ 'companies' =>  function($q)use($id){
                $q->where('company_id', $id);
            }, 'user', 'employer'])->get();
  
            if($candidates){
                $data = [
                    'candidate' => CandidateResource::collection($candidates)
                ];
                return $this->response->responseSuccess($data, "Successfully Created", 200);
            }

        // }catch(\Exception $e){
        //     return $this->response->responseError($e->getMessage());
        // }
    }


}
