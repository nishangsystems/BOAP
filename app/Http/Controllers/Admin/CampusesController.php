<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CampusesController extends Controller
{
    //

    public function index()
    {
        # code...
        $data['title'] = "Manage Campuses";
        if (request()->has('school_id')) {
            $data['campuses'] = \App\Models\School::find(request('school_id'))->campuses();
        }
        else {
            $data['campuses'] = \App\Models\Campus::all();
        }
        return view('admin.campuses.index', $data);
    }

    public function create()
    {
        # code...
        $data['title'] = "Add New Campus";
        if (request()->has('school_id')) {
            $data['campuses'] = \App\Models\School::find(request('school_id'))->campuses();
        }
        else {
            $data['campuses'] = \App\Models\Campus::all();
        }
        return view('admin.campuses.create', $data);
    }
    
    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'school_id'=>'required',
            'name'=>'required',
            'address'=>'required',
            'telephone'=>'required'
        ]);

        if ($validator->fails()) {
            # code...
            return back()->with('error', $validator->errors()->first());
        }

        try {
            //code...
            if (\App\Models\School::find($request->school_id)->campuses()->where('name', $request->name)->count() > 0) {
                return back()->with('error', 'A campus with name '.$request->name.' already exist');
            }
    
            (new \App\Models\Campus($request->all()))
                ->save();
            return back()->with('success', 'Campus created');
        } catch (\Throwable $th) {
            //throw $th;
            throw $th;
            return back()->with('error', $th->getMessage());
        }

    }

    public function edit($id)
    {
        $data['title'] = "edit campus";
        $data['campus'] = \App\Models\Campus::find($id);
        if (request()->has('school_id')) {
            $data['campuses'] = \App\Models\School::find(request('school_id'))->campuses();
        }
        else {
            $data['campuses'] = \App\Models\Campus::all();
        }
        return view('admin.campuses.edit', $data);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_id'=>'required',
            'name'=>'required',
            'address'=>'required',
            'relephone'=>'required'
        ]);

        try {
            // if name and/or contact already exist, reject update
            $campus = \App\Models\Campus::find($id);
            if ($campus->name != $request->name && \App\Models\Campus::where('name', $request->name)->count() > 0) {
                # code...
                return back()->with('error', 'Update rejected. The campus name '.$request->name.' already exist');
            }
            if (isset($request->telephone) && $campus->telephone != $request->telephone && \App\Models\Campus::where('telephone', $request->telephone)->count() > 0) {
                # code...
                return back()->with('error', 'Update rejected. The campus tel '.$request->telephone.' already exist');
            }
            $campus->fill($request->all());
            $campus->save();
            return back()->with('success', 'Campus updated');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    public function delete($id)
    {
        # code...
        if (\App\Models\Campus::find($id)->students()->count() > 0) {
            # code...
            return back()->with('error', 'Campus can not be deleted. Campus has students');
        }
        \App\Models\Campus::find($id)->delete();
    }

    public function programs($id)//$id for the campus_id
    {
        # code...
        $data['title'] = "Manage Programs Under ".\App\Models\Campus::find($id)->name;
        $data['programs'] = \App\Models\CampusProgram::where('campus_id', $id)->pluck('program_level_id')->toArray();
        return view('admin.campuses.programs', $data);
    }

    public function set_program_fee($id, $program_id)
    {
        # code...
    }

    public function add_program($id, $program_id)
    {
    # code...
        if (\App\Models\CampusProgram::where('campus_id', $id)->where('program_level_id', $program_id)->count()>0) {
            # code...
            return back()->with('error', 'Already exist in this campus');
        }
        $cp = new \App\Models\CampusProgram(['campus_id'=>$id, 'program_level_id'=>$program_id]);
        $cp->save();
        return back()->with('success', 'done');
    }

    public function drop_program($id, $program_id)
    {
        # code...
        if (\App\Models\CampusProgram::where('campus_id', $id)->where('program_level_id', $program_id)->count()==0) {
            # code...
            return back()->with('error', 'Does not exist in this campus');
        }

        \App\Models\CampusProgram::where('campus_id', $id)->where('program_level_id', $program_id)->first()->delete();
        return back()->with('success', 'done');

    }
}