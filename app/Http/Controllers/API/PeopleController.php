<?php

namespace App\Http\Controllers\API;

use App\Models\People;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;


class PeopleController extends BaseController
{
    public function index(Request $request)
    {
        $query = People::orderBy('name', 'asc');

        ($request->has('page'))  ? $data = $query->paginate(10) : $data = $query->get();

        return $this->sendResponse($data);
    }

    public function show($id)
    {
        $person = People::findOrFail($id);

        return $this->sendResponse($person);
    }

    public function update(Request $request, $id)
    {
        $person = People::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules($request));

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors()->all(), 422);
        }

        DB::transaction(function () use ($request, $person) {
            $inputs = $request->all();

            $person->fill($inputs)->save();

            $people = People::find($person->id);
            $people->fill($inputs)->save();

        });

        return $this->sendResponse($person, "Registro atualizado com sucesso", 200);
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rules($request));

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors()->all(), 422);
        }


        $inputs = $request->all();

        $data = People::create($inputs);
        
        return $this->sendResponse($data, "Registro criado com sucesso", 201);
    
    }

    public function destroy($id)
    {
        $item = People::findOrFail($id);

        try {
            $item->delete();
            return $this->sendResponse([], 'Registro deletado com sucesso.');
        } catch (Exception $e) {
            return $this->sendError('Você não tem permissão para excluir esse registro.', [], 403);
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required'],
            'lastname' => ['required'],
            'email' => ['required'],
            'phone' => ['required','max:30'],
            'legalperson' => ['nullable','max:150'],
            'cpf' => ['max:11'],
            'cnpj' => ['max:14'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }

}
