<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetUsersDataFromDocebo;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\GetMemberDetailFromNovi;
use App\Http\Integrations\Novi\Requests\GetUsersDataFromNovi;
use App\Http\Integrations\Novi\Requests\GetUsersSimpleDataFromNovi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*$doceboConnector = new DoceboConnector();
        $doceboUsersPaginator = $doceboConnector->paginate(new GetUsersDataFromDocebo);
        $doceboUsers = [];
        foreach($doceboUsersPaginator as $pg){
            $data = $pg->dto();
            $doceboUsers = array_merge($doceboUsers, $data);
        }*/
        $doceboUsers = null;
        return view('home', compact('doceboUsers'));
    }

    public function empty(){
        $doceboConnector = new DoceboConnector();
        $noviConnector = new NoviConnector();

        $doceboUsersPaginator = $doceboConnector->paginate(new GetUsersDataFromDocebo);
        $doceboUsers = [];
        foreach($doceboUsersPaginator as $pg){
            $data = $pg->dto();
            $doceboUsers = array_merge($doceboUsers, $data);
        }

        $noviConnector = new NoviConnector();
        $noviUsersSimpleDataResponse = $noviConnector->send(new GetUsersSimpleDataFromNovi);
        $noviUsers = $noviUsersSimpleDataResponse->dto();

        $noviDoceboUsers = [];

        foreach ($doceboUsers as $user) {
            foreach ($noviUsers as $matchedUser) {
                if ($user['fullname'] === $matchedUser['fullname']) {
                    $user['status'] = 'exist';
                    $user['noviUuid'] = $matchedUser['noviUuid'];
                    $noviDoceboUsers[] = $user;
                    break;
                }
            }
        }

        foreach($noviDoceboUsers as $user){
            $userFields = config('userfields');
            $data = array_map(function ($value) {
                return null;
            }, $userFields);
            $doceboConnector->send(new UpdateUserFiledsData($user['docebo_id'], $data));
        }
        return view('home', compact('doceboUsers'));
    }

    public function verify()
    {
        $doceboConnector = new DoceboConnector();
        $doceboUsersPaginator = $doceboConnector->paginate(new GetUsersDataFromDocebo);
        $doceboUsers = [];
        foreach($doceboUsersPaginator as $pg){
            $data = $pg->dto();
            $doceboUsers = array_merge($doceboUsers, $data);
        }

        $noviConnector = new NoviConnector();
        $noviUsersSimpleDataResponse = $noviConnector->send(new GetUsersSimpleDataFromNovi);
        $noviUsers = $noviUsersSimpleDataResponse->dto();

        $noviDoceboUsers = [];

        foreach ($doceboUsers as $user) {
            $userExists = false;
            foreach ($noviUsers as $matchedUser) {
                if ($user['fullname'] === $matchedUser['fullname']) {
                    $userExists = true;
                    $user['status'] = 'exist';
                    $user['noviUuid'] = $matchedUser['noviUuid'];
                    break;
                }
            }
            if (!$userExists) {
                $user['status'] = 'inexist';
                $user['noviUuid'] = null;
            }
            $noviDoceboUsers[] = $user;
        }
        return view('home', compact('doceboUsers' , 'noviDoceboUsers'));
    }

    public function sync()
    {
        $doceboConnector = new DoceboConnector();
        $noviConnector = new NoviConnector();

        $doceboUsersPaginator = $doceboConnector->paginate(new GetUsersDataFromDocebo);
        $doceboUsers = [];
        foreach($doceboUsersPaginator as $pg){
            $data = $pg->dto();
            $doceboUsers = array_merge($doceboUsers, $data);
        }

        $noviConnector = new NoviConnector();
        $noviUsersSimpleDataResponse = $noviConnector->send(new GetUsersSimpleDataFromNovi);
        $noviUsers = $noviUsersSimpleDataResponse->dto();

        $noviDoceboUsers = [];

        foreach ($doceboUsers as $user) {
            foreach ($noviUsers as $matchedUser) {
                if ($user['fullname'] === $matchedUser['fullname']) {
                    $user['status'] = 'exist';
                    $user['noviUuid'] = $matchedUser['noviUuid'];
                    $noviDoceboUsers[] = $user;
                    break;
                }
            }
        }

        foreach($noviDoceboUsers as &$user){
            $memberDataResponse = $noviConnector->send( new GetMemberDetailFromNovi($user['noviUuid']));
            $data = $memberDataResponse->dto();
            $result = ($doceboConnector->send(new UpdateUserFiledsData($user['docebo_id'], $data)));
            if($result->dto() == true){
               $user['status'] = "done";
            }
        }
        unset($user);
        return view('home', compact('doceboUsers' , 'noviDoceboUsers'));
    }


}
