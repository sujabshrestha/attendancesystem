<?php

namespace Candidate\Repositories\candidate;


interface CandidateInterface
{


    public function store($request, $id);

    public function update($request, $id);

    public function getCandidatesByCompany($id);


}
