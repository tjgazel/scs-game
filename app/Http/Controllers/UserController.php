<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$user = User::find($id);

		return view('profile', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int                      $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$data = $request->all();

		$user = User::find($id);

		if ($request->get('password')) {
			$request->validate([
				'name' => 'required|string|min:3|max:20',
				'email' => ['required', 'string', 'email', 'max:50', Rule::unique('users')->ignore($id)],
				'password' => 'required|string|min:6|confirmed'
			]);

			$data['password'] = Hash::make($request->get('password'));
		} else {
			$request->validate([
				'name' => 'required|string|min:3|max:20',
				'email' => ['required', 'string', 'email', 'max:50', Rule::unique('users')->ignore($id)]
			]);
			$data['password'] = $user->password;
		}

		$user->update($data);

		return redirect()->route('users.edit', ['id' => $id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
