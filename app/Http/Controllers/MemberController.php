<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    private array $members = [
        ['id' => 1, 'nama' => 'Bany', 'nrp' => '3125600108', 'email' => 'Bany@student.pens.ac.id', 'nomor_telepon' => '081234567890', 'alamat' => 'Jl. Raya ITS, Sukolilo, Surabaya', 'status' => 'aktif'],
        ['id' => 2, 'nama' => 'Hawa', 'nrp' => '3125600109', 'email' => 'Hawa@student.pens.ac.id', 'nomor_telepon' => '082198765432', 'alamat' => 'Jl. Keputih Tegal, Surabaya', 'status' => 'aktif'],
        ['id' => 3, 'nama' => 'Abi', 'nrp' => '3125600110', 'email' => 'Abi@student.pens.ac.id', 'nomor_telepon' => null, 'alamat' => 'Jl. Gebang Putih, Surabaya', 'status' => 'nonaktif'],
    ];

    public function index()
    {
        $members = $this->members;

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        return redirect()->route('members.index')
            ->with('success', "Anggota \"{$validated['nama']}\" berhasil ditambahkan (data dummy, belum tersimpan ke database).");
    }

    public function show(string $id)
    {
        return "MemberController@show, id: {$id}";
    }

    public function edit(string $id)
    {
        return "MemberController@edit, id: {$id}";
    }

    public function update(Request $request, string $id)
    {
        return "MemberController@update, id: {$id}";
    }

    public function destroy(string $id)
    {
        return "MemberController@destroy, id: {$id}";
    }
}