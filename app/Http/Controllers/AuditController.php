<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use OwenIt\Auditing\Models\Audit;



class AuditController extends Controller
{
    public function audit_page (){
        $models = [];
        $path = app_path('Models');

        foreach (File::allFiles($path) as $file) {
            $modelName = str_replace('.php', '', $file->getFilename());
            $models[] = $modelName;
        }


         $audits = Audit::orderBy('id', 'desc')->take(500)->get();


        return view('audit',compact('models','audits'));
    }
    public function getAudits(Request $request)
    {
        $modelName = 'App\\Models\\' . $request->model;

        if (!class_exists($modelName)) {
            return response()->json(['error' => 'Model not found'], 404);
        }

        $audits = Audit::with('performed_by')->where('auditable_type', $modelName)->get();
        // return $audits[0];->
        return response()->json($audits);
    }
}
