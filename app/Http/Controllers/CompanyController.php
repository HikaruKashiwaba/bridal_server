<?php

namespace App\Http\Controllers;

use DB;
use App\Company;
use App\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$items = Company::all();
        $items = DB::select('select * from company left join member on member.company_id = company.id');

        //return response(Company::all());
        return response()->json(['data' => $items], 200);if (json.IsDefined("errors")) {
            dto.errors = ParseError(json);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        DB::beginTransaction();
        try {

            $company = new Company;
            $company->name = 'ugauga';
            $company->delete_flg = '1';

            $company->save();

            $result = [
                'code' => 'OK',
                'message' => 'OK'
            ];

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            $result = [
                'code' => 'NG',
                'message' => $e->getMessage()
            ];

        }
        return response()->json($result, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
        $params = json_decode(file_get_contents('php://input'), true);

        $company = new Company;
        $company->name = $params['name'];

        $company->save();

        $result = [
            'code' => 'OK',
            'message' => $params
        ];

        DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            $result = [
                'code' => 'NG',
                'message' => $e->getMessage()
            ];

        }
        return response()->json($result, 200);
    }

    public function deleteCompany(string $id) {

        $company =  Company::Find($id);

        if(!$company) {
            return response()->json(['code' => 'NG', 'message' => 'notFound'], 200);
        }

        DB::beginTransaction();
        try {
            $company->delete();

            DB::commit();

            $result = [
                'code' => 'OK',
                'message' => ''
            ];

        } catch(Exception $e) {
            DB::rollBack();
            $result = [
                'code' => 'NG',
                'message' => $e->getMessage()
            ];
        }
        return response()->json($result, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }

    function returnJson($resultArray){
        if(array_key_exists('callback', $_GET)){
            $json = $_GET['callback'] . "(" . json_encode($resultArray) . ");";
        }else{
            $json = json_encode($resultArray);
        }
        header('Content-Type: text/html; charset=utf-8');
        echo  $json;
        exit(0);
    }
}
