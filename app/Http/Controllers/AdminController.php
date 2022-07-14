<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Schema;
use Image;

use Auth;
use Exception;

use App\Models\User;
use App\Models\Admin;
use App\Models\Button;
use App\Models\Link;
use App\Models\Page;

class AdminController extends Controller
{
    //Statistics of the number of clicks and links 
    public function index()
    {
        $userId = Auth::user()->id;
        $littlelink_name = Auth::user()->littlelink_name;
        $links = Link::where('user_id', $userId)->select('link')->count();
        $clicks = Link::where('user_id', $userId)->sum('click_number');
       
        $userNumber = User::count();
        $siteLinks = Link::count();
        $siteClicks = Link::sum('click_number');

        return view('panel/index', ['littlelink_name' => $littlelink_name, 'links' => $links, 'clicks' => $clicks, 'siteLinks' => $siteLinks, 'siteClicks' => $siteClicks, 'userNumber' => $userNumber]);
    }

    //Get users by type
    public function users(request $request)
    {
        $usersType = $request->type;

        switch($usersType){
            case 'all':
                $data['users'] = User::select('id', 'name', 'email', 'littlelink_name', 'role', 'block', 'email_verified_at')->get();
                return view('panel/users', $data);
                break;
            case 'user':
                $data['users'] = User::where('role', 'email', 'user')->select('id', 'name', 'littlelink_name', 'role', 'block', 'email_verified_at')->get();
                return view('panel/users', $data);
                break;
            case 'vip':
                $data['users'] = User::where('role', 'email', 'vip')->select('id', 'name', 'littlelink_name', 'role', 'block', 'email_verified_at')->get();
                return view('panel/users', $data);
                break;     
            case 'admin':
                $data['users'] = User::where('role', 'email', 'admin')->select('id', 'name', 'littlelink_name', 'role', 'block', 'email_verified_at')->get();
                return view('panel/users', $data);
                break;
            }
    }

    //Search user by name
    public function searchUser(request $request)
    {
        $name = $request->name;
        $data['users'] = User::where('name', $name)->select('id', 'name', 'role', 'block')->get();
        return view('panel/users', $data);
    }

    //Block user
    public function blockUser(request $request)
    {
        $id = $request->id;
        $status = $request->block;

        if($status == 'yes'){
            $block = 'no';
        }elseif($status == 'no'){
            $block = 'yes';
        }

        User::where('id', $id)->update(['block' => $block]);

        return redirect('panel/users/all');
    }

    //Verify or un-verify users emails
    public function verifyUser(request $request)
    {
        $id = $request->id;
        $status = $request->verify;

        if($status == '-'){
            $verify = '0000-00-00 00:00:00';
        }else{
            $verify = NULL;
        }

        User::where('id', $id)->update(['email_verified_at' => $verify]);

        return redirect('panel/users/all');
    }
    
    //Create new user from the Admin Panel
    public function createNewUser()
    {

        function random_str(
            int $length = 64,
            string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ): string {
            if ($length < 1) {
                throw new \RangeException("Length must be a positive integer");
            }
            $pieces = [];
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $pieces []= $keyspace[random_int(0, $max)];
            }
            return implode('', $pieces);
        }

        $user = User::create([
            'name' => 'Admin-Created-' . random_str(8),
            'email' => random_str(8) . '@example.com',
            'password' => Hash::make(random_str(32)),
            'role' => 'user',
            'block' => 'no',
        ]);

       return redirect('panel/edit-user/'. $user->id);
    }

    //Delete existing user
    public function deleteUser(request $request)
    {
        $id = $request->id;

        $user = User::find($id);  

        Schema::disableForeignKeyConstraints();
        $user->forceDelete();
        Schema::enableForeignKeyConstraints();

        return redirect('panel/users/all');
    }

    //Show user to edit
    public function showUser(request $request)
    {
        $id = $request->id;

        $data['user'] = User::where('id', $id)->get();
       
        return view('panel/edit-user', $data);

    }

    //Show link, click number, up link in links page
    public function showLinksUser(request $request)
    {
        $id = $request->id;
        
        $data['user'] = User::where('id', $id)->get();

        $data['links'] = Link::select('id', 'link', 'title', 'order', 'click_number', 'up_link', 'links.button_id')->where('user_id', $id)->orderBy('up_link', 'asc')->orderBy('order', 'asc')->paginate(10);
        return view('panel/links', $data);
    }

    //Delete link
    public function deleteLinkUser(request $request)
    {
        $linkId = $request->id;

        Link::where('id', $linkId)->delete();
        
        return back();
    }

    //Save user edit
    public function editUser(request $request)
    {
    	$request->validate([
            'name' => '',
            'email' => '',
            'password' => '',
            'littlelink_name' => '',
        ]);
    
        $id = $request->id;
        $name = $request->name;
        $email = $request->email;
        $password = Hash::make($request->password);
        $profilePhoto = $request->file('image');
        $littlelink_name = $request->littlelink_name;
        $littlelink_description = $request->littlelink_description;
        $role = $request->role;
        
        if($request->password == '' ) {
        User::where('id', $id)->update(['name' => $name, 'email' => $email, 'littlelink_name' => $littlelink_name, 'littlelink_description' => $littlelink_description, 'role' => $role]);
          } else {
        User::where('id', $id)->update(['name' => $name, 'email' => $email, 'password' => $password, 'littlelink_name' => $littlelink_name, 'littlelink_description' => $littlelink_description, 'role' => $role]);
        }
        if(!empty($profilePhoto)){
            $profilePhoto->move(base_path('/img'), $littlelink_name . ".png");
        }

        return redirect('panel/users/all');
    }

    //Show site pages to edit
    public function showSitePage()
    {
        $data['pages'] = Page::select('terms', 'privacy', 'contact', 'register')->get();
        return view('panel/pages', $data);
    }

    //Save site pages
    public function editSitePage(request $request)
    {
        $terms = $request->terms;
        $privacy = $request->privacy;
        $contact = $request->contact;
        $register = $request->register;

        Page::first()->update(['terms' => $terms, 'privacy' => $privacy, 'contact' => $contact, 'register' => $register]);

        return back();
    }

    //Show home message for edit
    public function showSite()
    {
        $message = Page::select('home_message')->first();
        return view('panel/site', $message);
    }

    //Save home message and logo
    public function editSite(request $request)
    {
        $message = $request->message;
        $logo = $request->file('image');

        Page::first()->update(['home_message' => $message]);

        if(!empty($logo)){
            
            $imgFile = Image::make($logo->getRealPath());
            $imgFile->resize(128, 128, function ($constraint) {
                $constraint->aspectRatio();
            })->save(base_path('/littlelink/images/')."avatar.png");
            $imgFile->resize(256, 256, function ($constraint) {
                $constraint->aspectRatio();
            })->save(base_path('/littlelink/images/')."avatar@2x.png");
            }

        return back();
    }

    //View any of the pages: contact, terms, privacy
    public function pages(request $request)
    {
        $name = $request->name;

        try {
            $data['page'] = Page::select($name)->first();
        } catch (Exception $e) {
            return abort(404);
        }

        return view('pages', ['data' => $data, 'name' => $name]);
    }

    //Statistics of the number of clicks and links 
    public function phpinfo()
    {
        return view('panel/phpinfo');
    }

    //Shows config file editor page
    public function showFileEditor(request $request)
    {
        return view('/panel/config');
    }

    //Saves advanced config
    public function editAC(request $request)
    {
        $AdvancedConfig = $request->AdvancedConfig;

        file_put_contents('config/advanced-config.php', $AdvancedConfig);

        return view('/panel/config');
    }

    //Saves .env config
    public function editENV(request $request)
    {
        $AdvancedConfig = $request->AdvancedConfig;

        file_put_contents('.env', $AdvancedConfig);

        return view('/panel/config');
    }

    //Shows config file editor page
    public function showBackups(request $request)
    {
        return view('/panel/backups');
    }

    //Delete custom theme
    public function deleteTheme(request $request)
    {

        $del = $request->deltheme;

        if (empty($del)) {
            echo '<script type="text/javascript">'; 
            echo 'alert("No themes to delete!");'; 
            echo 'window.location.href = "../studio/theme";';
            echo '</script>';
        } else {

        $folderName = base_path() . '/themes/' . $del;
        
           
        
        function removeFolder($folderName) {
        
                 if (is_dir($folderName))
        
                   $folderHandle = opendir($folderName);
        
          
        
                 if (!$folderHandle)
        
                      return false;
        
          
        
                 while($file = readdir($folderHandle)) {
        
                       if ($file != "." && $file != "..") {
        
                            if (!is_dir($folderName."/".$file))
        
                                 unlink($folderName."/".$file);
        
                            else
        
                                 removeFolder($folderName.'/'.$file);
        
                       }
        
                 }
        
          
        
                 closedir($folderHandle);
        
                 rmdir($folderName);
    
        
        }

        removeFolder($folderName);

        return Redirect('/panel/theme');
    }}

    //Shows config file editor page
    public function showThemes(request $request)
    {
        return view('/panel/theme');
    }

}
