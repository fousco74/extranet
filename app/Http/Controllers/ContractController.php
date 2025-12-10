<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractArticle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ContractController extends Controller
{

    public function index(Request $request)
    {

        $query = Contract::query()->with('assignedUser');

        if ($request->filled('status') && in_array($request->status, ['signed', 'unsigned'])) {
            $query->where('signe', $request->status === 'signed');
        }
        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('assigned_to', $request->integer('user_id'));
        }

        $contracts = $query->latest('id')->paginate(10)->withQueryString();
        $users = User::query()->select('id', 'first_name', 'last_name', 'poste', 'profile_link')->orderBy('first_name')->get();

        return Inertia::render('Contracts/index', [
            'contracts' => $contracts,
            'users'     => $users,
        ]);
    }


    public function create()
    {
        $users = User::query()->select('id', 'first_name', 'last_name', 'poste', 'profile_link', 'birth_date', 'birth_place', 'nationality', 'marital_status', 'address', 'phone_number')->orderBy('first_name')->get();

        return Inertia::render('Contracts/create', [
            'users' => $users,
        ]);
    }



public function store(Request $request)
{


    $data = $request->validate([
        'contract_type'         => ['required', 'string', 'max:255'],
        'title'                  => ['nullable', 'string', 'max:255'],
        'description'            => ['nullable', 'string'],
        'effective_date'         => ['required', 'date'],
        'expiration_date'        => ['required', 'date', 'after:effective_date'],
        'assigned_to'            => ['required', 'integer', 'exists:users,id'],
        'internship_supervisor'  => ['nullable', 'integer', 'exists:users,id'],
        'company_name'           => ['nullable', 'string', 'max:255'],
        'company_address'        => ['nullable', 'string', 'max:255'],
        'company_rcs'            => ['nullable', 'string', 'max:255'],
        'legal_representative'   => ['nullable', 'string', 'max:255'],
        'contract_duration'      => ['nullable', 'integer', 'min:0'],
        'salary'                 => ['nullable', 'string', 'max:255'],
        'work_location'          => ['nullable', 'string', 'max:255'],
        'hr_representative'      => ['nullable', 'string', 'max:255'],
        'hr_position'            => ['nullable', 'string', 'max:255'],
        'hr_contact'             => ['nullable', 'string', 'max:255'],
        'articles'               => ['required', 'array', 'min:1'],
        'articles.*.title'       => ['required', 'string', 'max:255'],
        'articles.*.contents'    => ['required', 'string'],
    ]);


    if ($data['contract_type'] === 'CONTRAT DE STAGE DE QUALIFICATION PROFESSIONNELLE' && empty($data['internship_supervisor'])) {
        return back()->with('message', "Vous devez assigner un maître de stage.")->withInput();
    }


    $data['contract_date'] = Carbon::now()->toDateString();

    $contractPending = Contract::where('assigned_to', $data['assigned_to'])
        ->where('expiration_date', '>=', $data['contract_date'])
        ->first();

    if ($contractPending) {
        return back()->with('message', "Vous avez déjà un contrat en cours.")->withInput();
    }


    $contract = Contract::create($data);

    foreach ($data['articles'] as $i => $article) {
        ContractArticle::create([
            'contract_id' => $contract->id,
            'title'       => $article['title'],
            'contents'    => $article['contents'],
            'position'    => $i,
        ]);
    }

    return Inertia::location(route('contracts.index', $contract));
}


    public function show(Contract $contract)
    {
        $contract->load(['assignedUser', 'internshipSupervisor', 'articles']);
        return Inertia::render('Contracts/show', [
            'contract' => $contract,
        ]);
    }

    public function update(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'title'           => ['nullable', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'effective_date'  => ['nullable', 'date'],
            'expiration_date' => ['nullable', 'date', 'after:effective_date'],
            'assigned_to'     => ['nullable', 'integer', 'exists:users,id'],
            'internship_supervisor' => ['nullable', 'integer', 'exists:users,id'],
            'salary'          => ['nullable', 'string', 'max:255'],
            'work_location'   => ['nullable', 'string', 'max:255'],
            'archived'        => ['nullable', 'boolean'],
        ]);

        $contract->update($data);

        return back()->with('success', 'Contrat mis à jour.');
    }

    public function sign(Request $request)
    {

        $request->validate([
            'contract_id' => ['required', 'integer', 'exists:contracts,id'],
            'signature'   => ['required', 'string'],
        ]);





        /** @var Contract $contract */
        $contract = Contract::query()->findOrFail($request->integer('contract_id'));


        $raw = $request->string('signature')->toString();

        $mime = 'image/png';
        $ext  = 'png';
        $payload = $raw;

        if (preg_match('/^data:image\/(\w+);base64,/', $raw, $m)) {
            $ext  = strtolower($m[1]);
            $mime = 'image/'.$ext;
            $payload = substr($raw, strpos($raw, ',') + 1);
        }

        $binary = base64_decode($payload, true);
        if ($binary === false) {
            return back()->with('message', 'Signature invalide.');
        }

        $filename = 'contracts/signatures/' . $contract->id . '-' . Str::uuid() . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);



        $contract->update([
            'signe'          => true,
            'signature'      => $raw,
            'signature_path' => $filename,
            'signature_mime' => $mime,
            'signature_date' => now(),
        ]);

        return back()->with('success', 'Signature enregistrée.');
    }

    public function download($id)
    {

        $contract = Contract::with(['assignedUser', 'articles'])->findOrFail($id);

        $storedPdf = "contracts/pdfs/contract-{$contract->id}.pdf";
        if (Storage::disk('public')->exists($storedPdf)) {
            return Storage::disk('public')->download($storedPdf, "contrat-{$contract->assignedUser->first_name}.pdf");
        }


        return response()->view('contracts.print', [
            'contract' => $contract,
        ]);
    }


    public function destroy(Contract $contract)
    {
        $contract->update(['archived' => true]);
        return back()->with('success', 'Contrat archivé.');
    }
}
