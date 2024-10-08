<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Class_model;
use Response;
use App\Http\StudentController;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class classController extends Controller
{
   
    
    public function index()
    {
        return Class_model::all();
    }

   
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'class_name' => 'required|string',
            'class_year' => 'required|integer',
            'year' => 'required|integer'
        ]);

        $soma = $validatedData['class_name'] . $validatedData['class_year'] . $validatedData['year'];

       
        $registro = Class_model::create([
            'class_name' => $validatedData['class_name'],
            'class_year' => $validatedData['class_year'],
            'year' => $validatedData['year'],
            'class' => $soma,
            
        ]);

        return response()->json($registro, 201);
    }

    
    public function show(string $id)
    {
        $class = Class_model::find($id);
        if (!$class) {
            return response()->json([
                'message' => 'class não encontrada.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detalhes da class.',
            'data' => $class
        ]);   
    

    }

    
    public function update(Request $request, string $id)
    {
        
    
        
    }


    public function destroy(string $id)
    {
        $class = Class_model::find($id);

        if (!$class) {
            return response()->json([
                'message' => 'class não encontrada.'
            ], 404);
        }

        $class->delete();

        return response()->json([
            'message' => 'class deletada com sucesso.'
        ]);
    }
 public function searchByclass($id)
{
    
    $results = DB::table('studentclass')
        ->join('students', 'studentclass.id', '=', 'students.class_id')
        ->where('studentclass.class', $id)
        ->select('students.id', 'students.name', 'students.email' , 'students.created_at', 'students.updated_at') 
        ->get();
    
    
    return Response::json(['students' => $results]);
}

        
    
    public function searchByAluno(Request $request, $id)
    {
                                                                                     
        $alunos = User::where('id', 'LIKE', '%' . $id . '%')->get();

    
        $studentclass = DB::table('studentclass')
        ->join('students', 'students.class_id', '=', 'studentclass.id')
        ->where('class', 'LIKE', '%' . $id . '%') 
        ->select('studentclass.*')  
        ->get();
    

    if ($studentclass->isEmpty()) {
        return response()->json([
            'message' => 'Nenhuma class encontrada para o aluno especificado.'
        ], 404);
    }
    

    return response()->json([
        'message' => 'studentclass encontradas.',
        'data' => $studentclass,
        'aluno' => $alunos
        
    ]);
}

}
