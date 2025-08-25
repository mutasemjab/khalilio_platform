<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Field;
use App\Models\CategoryExam;
use App\Models\CategoryFile;
use App\Models\CategoryLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|regex:/^07[0-9]{8}$/|unique:users',
                'school_name' => 'required|string|max:255',
                'field_id' => 'required|exists:fields,id',
            ], [
                // Custom error messages in Arabic
                'phone.regex' => 'رقم الهاتف يجب أن يكون 10 أرقام ويبدأ بـ 07',
                'phone.unique' => 'رقم الهاتف مستخدم مسبقاً',
                'phone.required' => 'رقم الهاتف مطلوب',
                'name.required' => 'الاسم مطلوب',
                'school_name.required' => 'اسم المدرسة مطلوب',
                'field_id.required' => 'المجال الدراسي مطلوب',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            try {
                $user = User::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'school_name' => $request->school_name,
                    'field_id' => $request->field_id,
                ]);

                Session::put('user_id', $user->id);
                Session::put('user_name', $user->name);

                // Redirect to dashboard after successful registration
                return redirect()->route('dashboard')->with('success', 'تم إنشاء الحساب بنجاح');

            } catch (\Exception $e) {
                return back()->with('error', 'حدث خطأ أثناء إنشاء الحساب')->withInput();
            }
        }

        // GET request - show registration form
        $fields = Field::all();
        return view('sections.registration', compact('fields'));
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|regex:/^07[0-9]{8}$/',
            ], [
                'phone.regex' => 'رقم الهاتف يجب أن يكون 10 أرقام ويبدأ بـ 07',
                'phone.required' => 'رقم الهاتف مطلوب',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            try {
                // Find user by phone number
                $user = User::where('phone', $request->phone)->first();

                if (!$user) {
                    return back()->with('error', 'رقم الهاتف غير مسجل')->withInput();
                }

                // Login user
                Session::put('user_id', $user->id);
                Session::put('user_name', $user->name);

                // Redirect to dashboard after successful login
                return redirect()->route('dashboard')->with('success', 'تم تسجيل الدخول بنجاح');

            } catch (\Exception $e) {
                return back()->with('error', 'حدث خطأ أثناء تسجيل الدخول')->withInput();
            }
        }

        // GET request - show login form
        return view('sections.login');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
