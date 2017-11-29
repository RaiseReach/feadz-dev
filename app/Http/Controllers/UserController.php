<?php namespace App\Http\Controllers;

use Hash;
use Auth;
use Input;
use App\User;
use App\Post;
use Validator;
use Response;
use Illuminate\Http\Request;

class UserController extends Controller {

    /*
    *--------------------------------------------------------------------------
    * User Controller
    *--------------------------------------------------------------------------
    *
    * This controller is designed to change and store personal user data
    *
    */

    /**
     * Store user personal settings
     * @param Request $request
     * @return Response
    */
    public function storeSettings(Request $request) {
        $user = Auth::user();
        $user->hide_upvotes   = $request->input('hide_upvotes') !== null ? 1 : 0;
        $user->real_name      = $request->input('real_name', '');
        $user->description    = $request->input('description', '');
        // Validation email
        $validator_email = Validator::make($request->input(), ['email_for_news' => 'required|email|max:255']);
        $user->email_for_news = !$validator_email->fails() ? $request->input('email_for_news') : '';
        // Validation user name
        $validator_name = Validator::make($request->input(), [
            'name' => 'required|min:3|max:255|unique:users|alpha_num|regex:/^[a-zA-Z0-9]+$/u|not_in:admin,create,upload,success,report,auth,user,ref,referrals,home,logout,login,disclaimer,channel,edit,category,search',
        ]);
        if(!$validator_name->fails()) {
            $user->name = $request->input('name');
            $user->posts()->update(['author_name' => $request->input('name')]);
            $user->notify(new \App\Notifications\notifyDB('You have successfully changed your nickname to ' . $request->name));
        }

        $validation_password = Validator::make($request->input(), [
            'password' => 'required|min:6|confirmed',
        ]);
        if(!$validation_password->fails()) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
            $user->notify(new \App\Notifications\notifyDB('You have successfully changed your password'));
            return redirect()->route('logout');
        }
        $user->save();
        return view('user.settings', ['body_class' => 'settings', 'user' => $user]);
    }


    /**
     * Saving the image sent by the user and
     * assigning this image as his photo.
     * @param file 'filedata'
     * @return Response
    */
	public function storePhoto() {
        if(Input::hasFile('filedata')) {
            if(Input::file('filedata')->isValid()) {
                $validator = Validator::make(Input::file(), [
                    'filedata' => 'dimensions:max_width=4000,max_height=4000,min_width=50,min_height=50|image',
                ]);
                if (!$validator->fails()) {
                    $destinationPath = public_path('files/uploads/');
                    $fileName = uniqid('feadz' , true) . '.jpg';
                    Input::file('filedata')->move($destinationPath, $fileName);
                    // changing photo in db
                    $this->changePhoto($fileName);
                    return Response::json(['success' => true, 'file' => $fileName]);
                }
                return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
            }
            return Response::json(['success' => false, 'errorText' => ['The file failed validation'] ]);
        }
        return Response::json(['success' => false, 'errorText' => ['It must be a file!'] ]);
	}

    /**
     * Assign a random photo to the user
     * @return Response
    */
    public function randomPhoto() {
        $directory = public_path('/files/uploads/');
        $scanned_directory = array_diff(scandir($directory), ['.', '..']);
        $fileName = $scanned_directory[array_rand($scanned_directory)];
        $this->changePhoto($fileName);
        return Response::json(['success' => true, 'file' => $fileName]);
    }

    /**
     * Mark all personal notifications of user as "read"
     * @return void
     */
    public function notifications() {
        Auth::user()->unreadNotifications->markAsRead();
    }

    /**
     * Change a user's photo
     * @param  string $filename
     * @return void
    */
    private function changePhoto($fileName) {
        $user = Auth::user();
        $user->photo = $fileName;
        $user->save();
    }
}
