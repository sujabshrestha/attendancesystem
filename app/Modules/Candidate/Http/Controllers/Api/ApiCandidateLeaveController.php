<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Candidate\Models\Leave;
use Candidate\Http\Resources\CandidateLeaveResource;
use Candidate\Repositories\candidate\CandidateInterface;
use Employer\Http\Resources\LeavetypeResource;
use Employer\Models\LeaveType;
use Files\Repositories\FileInterface;


class ApiCandidateLeaveController extends Controller
{
    protected $response, $candidate,$file;

    public function __construct(ResponseService $response, CandidateInterface $candidate, FileInterface $file)
    {
        $this->response = $response;
        $this->file = $file;
        $this->candidate = $candidate;
    }

    public function getLeaveTypes(){
        try{
            $leaveTypes = LeaveType::get();

            $data = [
                'leaveTypes' => LeavetypeResource::collection($leaveTypes)
            ];
            return $this->response->responseSuccess($data, "Successfully Retrieved", 200);
        
        } catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

    
    public function allCandidateLeave($company_id){
        try{
            $user_id = Auth()->id();
            $userLeaves = Leave::where('user_id',$user_id)->where('company_id',$company_id)->get();
            if($userLeaves){
                $data = [
                    'candidateLeaves' => CandidateLeaveResource::collection($userLeaves)
                ];
                return $this->response->responseSuccess($data, "Successfully Retrieved", 200);
            }

        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

    public function storeCandidateLeave(Request $request, $company_id){
        try{
            $user = Auth()->user();
            $leave = new Leave();
            $leave->candidate_id =$user->candidate->id ;
            $leave->user_id = $user->id;
            $leave->reason = $request->reason;
            $leave->approved =0;
            $leave->company_id = $company_id;
            if($request->has('document')){
                $uploadFile = $this->file->storeFile($request->document);
                if($uploadFile){
                    $leave->document_id = $uploadFile->id;
                }
            }
            if($leave->save() == true){
                return $this->response->responseSuccessMsg("Successfully Created", 200);
            }
            return $this->response->responseError("Something Went Wrong While Saving. Please Try Again.");

        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }


    public function updateCandidateLeave(Request $request, $company_id,$leave_id){
        try{
            // dd($request->all());
            $user = Auth()->user();
            // $leave = Leave::where('user_id',$user->id)->where('company_id',$company_id)->where('id',$id);
            $leave = Leave::where('company_id',$company_id)->where('id',$leave_id)->first();
            if($leave){
                if($leave->approved == 0){
                    $leave->candidate_id =$user->candidate->id ;
                    $leave->user_id = $user->id;
                    $leave->reason = $request->reason;
                    $leave->status =$request->status ;
                    $leave->company_id = $request->company_id;
                    if($request->has('document')){
                        $uploadFile = $this->file->storeFile($request->document);
                        if($uploadFile){
                            $leave->document_id = $uploadFile->id;
                        }
                    }
                    if($leave->update() == true){
                        return $this->response->responseSuccessMsg("Successfully Created", 200);
                    }
                    return $this->response->responseError("Something Went Wrong While Updateing. Please Try Again.");

                    }
                return $this->response->responseError("Can Not Update Approved Leave Request.",404);                
            }
            return $this->response->responseError("Leave Record Not Found",404);
          

        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }


  

    public function deleteCandidateLeave($company_id,$leave_id){
        try{
            $leave = Leave::where('company_id',$company_id)->where('id',$leave_id)->first();
            if($leave){
                $leave->delete();
                return $this->response->responseSuccessMsg("Successfully Deleted", 200);
            }
            return $this->response->responseError("Leave Record Not Found",404);
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }


}
