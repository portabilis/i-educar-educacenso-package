<?php

namespace iEducar\Packages\Educacenso\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EducacensoImport;
use App\Process;
use Carbon\Carbon;
use iEducar\Packages\Educacenso\Exception\ImportException;
use iEducar\Packages\Educacenso\Http\Requests\ImportRegistrationRequest;
use iEducar\Packages\Educacenso\Services\HandleFileService;
use iEducar\Packages\Educacenso\Services\ImportServiceFactory;
use Illuminate\Support\Facades\Auth;

class ImportRegistrationController extends Controller
{
    public function index()
    {
        $this->breadcrumb('Historico de importações', [
            url('intranet/educar_index.php') => 'Escola',
        ]);

        $this->menu(Process::EDUCACENSO_IMPORT_HISTORY);

        $imports = EducacensoImport::orderBy('created_at', 'desc')->get();

        return view('educacenso::import.index', compact('imports'));
    }
    public function create()
    {
        $permission = new \clsPermissoes();
        $permission->permissao_cadastra(
            int_processo_ap: 9998849,
            int_idpes_usuario: auth()->user()->id,
            int_soma_nivel_acesso: 7,
            str_pagina_redirecionar: 'educar_index.php'
        );

        $this->breadcrumb('Importação educacenso', [
            url('intranet/educar_educacenso_index.php') => 'Educacenso',
        ]);

        $this->menu(9998849);

        return view('educacenso::import.create');
    }

    public function store(ImportRegistrationRequest $request)
    {
        $file = $request->file('file');

        try {
            $yearImportService = ImportServiceFactory::createImportService(
                $request->get('year'),
                Carbon::createFromFormat('Y-m-d', $request->get('date'))
            );

            $importFileService = new HandleFileService($yearImportService, Auth::user());

            $importFileService->handleFile($file);
        } catch (ImportException $exception) {
            return redirect('educacenso-import-registrations.index')->with('error', $exception->getMessage());
        }

        return redirect()->route('educacenso-import-registrations.index')->with('success', 'Importação realizada com sucesso!');
    }
}
