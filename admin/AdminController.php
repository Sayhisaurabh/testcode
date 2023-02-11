<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\Program;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }
     function index(){
        return view('admin.dashboard');
     }

#Setting
     function header_setting(){
        $settingInfo =  Setting::first();
        return view('admin.setting-header',compact('settingInfo'));
     }

     public function header_setting_action(Request $request){


        if(empty($request->setting_id)){

            if(isset($request->header_logo)){
                $headerLogo = time().'.'.$request->header_logo->extension();
                $request->header_logo->move(public_path('uploads/setting/'), $headerLogo);
            }

            if(isset($request->footer_logo)){
                $footer_logo = time().'.'.$request->footer_logo->extension();
                $request->footer_logo->move(public_path('uploads/setting/'), $footer_logo);
            }

            $setting = new Setting;
            $setting->email     = $request->email;

            if(!empty($headerLogo)){
              $setting->header_logo    = $headerLogo;
            }
            if(!empty($footer_logo)){
              $setting->footer_logo    = $footer_logo;
            }
            $setting->address     = $request->address;
            $setting->number     = $request->number;


            $setting->save();

            return redirect('/admin/header-setting')->with('success', 'You have successfully added!');

        }

        else{

            if(isset($request->header_logo)){
                $headerLogo = time().'.'.$request->header_logo->extension();
                $request->header_logo->move(public_path('uploads/setting/'), $headerLogo);
            }

            if(isset($request->footer_logo)){
                $footer_logo = time().'.'.$request->footer_logo->extension();
                $request->footer_logo->move(public_path('uploads/setting/'), $footer_logo);
            }
            $settingArray = array();
            $settingArray['email']     = $request->email;

            if(!empty($headerLogo)){
              $settingArray['header_logo']    = $headerLogo;
            }
            if(!empty($footer_logo)){
              $settingArray['footer_logo']    = $footer_logo;
            }


            $settingArray['address']     = $request->address;
            $settingArray['number']      = $request->number;


            Setting::where('id',$request->setting_id)->update($settingArray);
            return redirect('/admin/header-setting')->with('success', 'You have successfully updated!');
        }


    }

#program
function programs(){
    // $programs =  Program::all();
    $programs = Program::select('programs.*','degree.degree')->leftjoin('degree','degree.id','=','programs.degree_id')->get();
    // dd($programs);
    return view('admin.programs.list',compact('programs'));
}
function add_programs(){
     $degrees =  Degree::all();
      return view('admin.programs.add',compact('degrees'));
}
function add_program_action(Request $request){
    if(isset($request->image)){
        $image = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/programs/'), $image);
    }
    $program = new Program;
    if(!empty($image)){
        $program->image    = $image;
      }
    $program->degree_id = $request->degree_id;
    $program->duration = $request->duration;
    $program->title = $request->title;
    $program->description = $request->description;
    $program->save();
    return redirect('/admin/add-programs')->with('success', 'You have successfully added!');
}
function edit_program($id){
    $degrees = Degree::all();
    $program = Program::find($id);
    return view('admin.programs.edit',compact('program','degrees'));
}
function edit_program_action(Request $request){
    $program = array();
    if(isset($request->image)){

        $Info = Program::where('id',$request->update_id)->first();
        if(!empty($Info->image)){
            $getFilePath = public_path('uploads/programs/').$Info->image;
            if(file_exists($getFilePath)){
                unlink($getFilePath);
            }
        }
        $image = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/programs/'), $image);
        $program['image']    = $image;
    }

    $program['degree_id']     = $request->degree_id;
    $program['duration']     = $request->duration;
    $program['title']     = $request->title;
    $program['description']      = $request->description;
    Program::where('id',$request->update_id)->update($program);
    return redirect('/admin/programs')->with('success', 'You have successfully updated!');
}
function delete_program($id){
    Program::where('id',$id)->delete();
    return redirect('/admin/programs')->with('success', 'You have successfully deleted!');
}
#degree
function degree(){
    $degrees = Degree::all();
    return view('admin.degree.list',compact('degrees'));
}
function add_degree(){
    return view('admin.degree.add');
}
function add_degree_action(Request $request){
$degree = new Degree;
$degree->degree = $request->degree;
$degree->description = $request->description;
$degree->save();
return redirect('/admin/degree')->with('success', 'You have successfully added!');
}

function edit_degree($id){
    $degrees = Degree::find($id);
    return view('admin.degree.edit',compact('degrees'));
}
function edit_degree_action(Request $request){
    $degree = array();
    $degree['degree']     = $request->degree;
    $degree['description']      = $request->description;
    Degree::where('id',$request->degree_id)->update($degree);
    return redirect('/admin/degree')->with('success', 'You have successfully updated!');
}
function delete_degree($id){
    Degree::where('id',$id)->delete();
    return redirect('/admin/degree')->with('success', 'You have successfully deleted!');
}
}
