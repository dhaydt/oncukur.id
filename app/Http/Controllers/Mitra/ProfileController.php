<?php

namespace App\Http\Controllers\Mitra;

use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Mitra;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function view(Request $request)
    {
        $data = Mitra::where('id', auth('mitra')->id())->first();

        return view('mitra-views.profile.view', compact('data'));
    }

    public function edit($id)
    {
        if (auth('mitra')->id() != $id) {
            Toastr::warning(translate('you_can_not_change_others_profile'));

            return back();
        }
        $data = Mitra::where('id', auth('mitra')->id())->first();

        return view('mitra-views.profile.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $seller = Mitra::find(auth('mitra')->id());
        $seller->name = $request->name;
        $seller->phone = $request->phone;
        if ($request->image) {
            $seller->image = ImageManager::update('mitra/', $seller->image, 'png', $request->file('image'));
        }
        if ($request->ktp) {
            $seller->ktp = ImageManager::update('ktp/', $seller->ktp, 'png', $request->file('ktp'));
        }
        $seller->save();

        Toastr::info('Profile updated successfully!');

        return back();
    }

    public function settings_password_update(Request $request)
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);

        $seller = Mitra::find(auth('mitra')->id());
        $seller->password = bcrypt($request->password);
        $seller->save();
        Toastr::success('Mitra password updated successfully!');

        return back();
    }

    public function bank_update(Request $request, $id)
    {
        $bank = Mitra::find(auth('mitra')->id());
        $bank->bank_name = $request->bank_name;
        $bank->branch = $request->branch;
        $bank->holder_name = $request->holder_name;
        $bank->account_no = $request->account_no;
        $bank->save();
        Toastr::success('Bank Info updated');

        return redirect()->route('mitra.profile.view');
    }

    public function bank_edit($id)
    {
        if (auth('mitra')->id() != $id) {
            Toastr::warning(translate('you_can_not_change_others_info'));

            return back();
        }
        $data = Mitra::where('id', auth('mitra')->id())->first();

        return view('mitra-views.profile.bankEdit', compact('data'));
    }
}
