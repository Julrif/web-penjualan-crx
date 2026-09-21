<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // ============================================
    // HALAMAN PROFILE UTAMA
    // ============================================
    public function index()
    {
        $user = Auth::user();
        $orders = \App\Models\Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.profile.index', compact('user', 'orders'));
    }

    // ============================================
    // HALAMAN SETTINGS
    // ============================================
    public function settings()
    {
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->get();
        return view('pages.profile.settings', compact('user', 'addresses'));
    }

    // ============================================
    // UPDATE PROFIL (My Profile)
    // ============================================
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'birthday' => 'nullable|date',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'birthday' => $request->birthday,
        ]);

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    // ============================================
    // GANTI PASSWORD
    // ============================================
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama salah!');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    // ============================================
    // TAMBAH ALAMAT
    // ============================================
    public function addAddress(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'label' => 'required|string|max:50',
            'address' => 'required|string|min:5',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'required|string|max:20',
            'is_default' => 'nullable|boolean'
        ]);

        $isFirst = Address::where('user_id', $user->id)->count() == 0;
        $isDefault = $request->has('is_default') ? true : $isFirst;

        if ($isDefault) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => $user->id,
            'label' => $request->label,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'is_default' => $isDefault
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    // ============================================
    // UPDATE ALAMAT
    // ============================================
    public function updateAddress(Request $request, $id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'label' => 'required|string|max:50',
            'address' => 'required|string|min:5',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'required|string|max:20',
            'is_default' => 'nullable|boolean'
        ]);

        $isDefault = $request->has('is_default') ? true : false;

        if ($isDefault) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address->update([
            'label' => $request->label,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'is_default' => $isDefault
        ]);

        return back()->with('success', 'Alamat berhasil diupdate!');
    }

    // ============================================
    // HAPUS ALAMAT
    // ============================================
    public function deleteAddress($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus!');
    }

    // ============================================
    // SET ALAMAT DEFAULT
    // ============================================
    public function setDefaultAddress($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Alamat default berhasil diubah!');
    }

    // ============================================
    // AMBIL DATA ALAMAT UNTUK EDIT (AJAX)
    // ============================================
    public function getAddress($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json($address);
    }
}