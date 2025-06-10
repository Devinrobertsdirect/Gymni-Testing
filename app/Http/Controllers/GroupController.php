<?php
namespace App\Http\Controllers;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->group    =  new Group;
        $this->user    =  new User;
    }

    public function index()
    {
        $where = [];
        //$group = $this->group->orderBy('id', 'desc')->latest()->paginate(10);
        $group = DB::table('groups')->orderBy('id', 'DESC')->get();
        return view('group.index', compact('group', 'group'));
    }

    public function show($id)
    {
        $id    = filter_var($id, FILTER_VALIDATE_INT);
        if ($id == false) {
            return abort(404);
        } else {
            $group        = Group::where('id', $id)->first();
            return view('group.show', compact('group'));
        }
    }

    public function destroy($id)
    {
        $group = $this->group->where('id', $id)->get()->first();
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully');
    }

    public function create()
    {
        $user = $this->user->select('id', 'name')->where('id', '!=', 1)->get()->toArray();
        return view('group.create', compact('user'));
    }


    public function store(Request $request)
    {
        $this->groupValidate($request);
        $data = $request->all();
        $insClient['group_name']            = filter_var($data['group_name'], FILTER_SANITIZE_STRING);
        $insClient['group_description']     = filter_var($data['group_description'], FILTER_SANITIZE_STRING);
        $insClient['created_by']            = filter_var($data['created_by'], FILTER_VALIDATE_INT);
        if (!empty($data['file'])) {
            $img  =   Group::uploadVideo($data['file'], 'group');
            $insClient['image'] =  $img;
        }
        if (!empty($data['members'])) {
            $insClient['members'] = implode(',', $data['members']);
        } else {
            $insClient['members']  = '';
        }
        $this->group->create($insClient);
        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function edit($id)
    {
        $group = $this->group->select('*')->where('id', $id)->get()->first();
        $memb = explode(',', $group->members);
        $user = $this->user->select('id', 'name')->where('id', '!=', 1)->get()->toArray();
        return view('group.edit', compact('group', 'user', 'memb'));
    }

    public function update(Request $request, $id)
    {
        $this->groupValidate($request, $id);
        $data = $request->all();
        $insClient['group_name']            = filter_var($data['group_name'], FILTER_SANITIZE_STRING);
        $insClient['group_description']     = filter_var($data['group_description'], FILTER_SANITIZE_STRING);
        $insClient['created_by']            = filter_var($data['created_by'], FILTER_VALIDATE_INT);
        if (!empty($data['file'])) {
            $img  =   Group::uploadVideo($data['file'], 'group');
            $insClient['image'] =  $img;
        }
        if (!empty($data['members'])) {
            $insClient['members'] = implode(',', $data['members']);
        }
        $this->group->where('id', $id)->update($insClient);
        return redirect()->route('groups.index')->with('success', 'Group data updated successfully.');
    }




    private function groupValidate($request, $id = null)
    {

        $validate['group_name']                 = 'required';
        $validate['group_description']          = 'required';
        //  $validate['created_by']                 = 'required';
        $validate['members']                    = 'required';

        if (!empty($id)) {
            $validate['file']            = 'nullable|mimes:jpeg,png,jpg,gif';
        } else {
            $validate['file']            = 'nullable|mimes:jpeg,png,jpg,gif';
        }

        $messages = [
            'group_name.required'            => __('Please Enter Group Name'),
            'group_description.required'     => __('Please Enter Group Description'),
            // 'created_by.required'            => __('Please Select Created By'),
            'members.required'               => __('Please Select Members')

        ];
        $request->validate($validate, $messages);
    }

    public function delImgGrp(Request $request)
    {
        $data = $request->all();
        $id = $data['Id'];
        $insClient['image'] = null;
        $this->group->where('id', $id)->update($insClient);
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
