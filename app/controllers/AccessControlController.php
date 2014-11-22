<?php

class AccessControlController extends Controller
{

    /**
     * @var
     */
    private $accessLogRepository;
    /**
     * @var \BB\Repo\EquipmentRepository
     */
    private $equipmentRepository;
    /**
     * @var \BB\Repo\EquipmentLogRepository
     */
    private $equipmentLogRepository;

    function __construct(\BB\Repo\AccessLogRepository $accessLogRepository, \BB\Repo\EquipmentRepository $equipmentRepository, \BB\Repo\EquipmentLogRepository $equipmentLogRepository)
    {
        $this->accessLogRepository = $accessLogRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
    }

    public function mainDoor()
    {
        $failed = false;

        $message = null;
        $isDelayed = false;
        $keyId = trim(Input::get('data'));

        Log::debug("New System. Entry message received: ".$keyId);

        if (strpos($keyId, '|') !== false) {
            $keyParts = explode('|', $keyId);
            $keyId    = $keyParts[0];
            $message  = $keyParts[1];
            if ($message == 'delayed') {
                $isDelayed = true;
            }
        }

        try {
            $keyFob = $this->lookupKeyFob($keyId);
        } catch (Exception $e) {
            //return Response::make(json_encode(['valid'=>'0', 'reason'=>'Not found']), 200);
            $responseBody = json_encode(['valid'=>'0', 'msg'=>'Not found']);
            $failed = true;
            //Log::debug("New System: Keyfob code not found ".$keyId);
        }

        if (!$failed) {
            $user = $keyFob->user()->first();


            $log               = [];
            $log['key_fob_id'] = $keyFob->id;
            $log['user_id']    = $user->id;
            $log['service']    = 'main-door';
            $log['delayed']    = $isDelayed;

            if ($user->active && $user->key_holder) {
                //OK
                $log['response'] = 200;
                $this->accessLogRepository->logAccessAttempt($log);
                //return Response::make(json_encode(['valid'=>'1', 'reason'=>'', 'name'=>$user->name]), 200);
                $userName = substr($user->given_name, 0, 20);
                $responseBody = json_encode(['valid' => '1', 'msg' => $userName]);
            } elseif ($user->active) {
                //Not a keyholder
                $log['response'] = 403;
                $this->accessLogRepository->logAccessAttempt($log);
                //return Response::make(json_encode(['valid'=>'0', 'reason'=>'Not a keyholder', 'name'=>$user->name]), 200);
                $responseBody = json_encode(['valid' => '0', 'msg' => 'Not a keyholder']);
            } else {
                //bad
                $log['response'] = 402;
                $this->accessLogRepository->logAccessAttempt($log);
                //return Response::make(json_encode(['valid'=>'0', 'reason'=>'Not active', 'name'=>$user->name]), 200);
                $responseBody = json_encode(['valid' => '0', 'msg' => 'Not active']);
            }
        }

        $response = Response::make($responseBody.PHP_EOL, 200);
        $response->headers->set('Content-Length', strlen($response->getContent()));
        return $response;
    }


    public function status()
    {
        $keyId = Input::get('data');
        try {
            $keyFob = $this->lookupKeyFob($keyId);
        } catch (Exception $e) {

            return Response::make(json_encode(['valid'=>'0']), 200);
        }
        $user = $keyFob->user()->first();

        $log = new AccessLog();
        $log->key_fob_id = $keyFob->id;
        $log->user_id = $user->id;
        $log->service = 'status';
        $log->save();
        $statusString = $user->status;
        return Response::make(json_encode(['valid'=>'1', 'name'=>$user->name, 'status'=>$statusString]), 200);
    }


    public function legacy()
    {
        $keyId = Input::get('data');
        $keyParts = explode(':', $keyId);


        //Verify the key fob code has been extracted
        if (!is_array($keyParts) || count($keyParts) != 3)
        {
            return Response::make("NOTFOUND", 200);
        }
        $keyId = $keyParts[0];


        //Lookup the fob id and the user
        try {
            $keyFob = $this->lookupKeyFob($keyId);
        } catch (Exception $e) {
            Log::debug("Keyfob code not found ".$keyId);
            return Response::make("NOTFOUND", 200);
        }
        $user = $keyFob->user()->first();


        //Log this request
        $log = new AccessLog();
        $log->key_fob_id = $keyFob->id;
        $log->user_id = $user->id;
        $log->service = 'main-door';


        //Return a status based on the users status
        if ($user->keyholderStatus()) {
            $log->response = 200;
            $log->save();
            return Response::make("OK:8F00:".$user->name, 200);
        } elseif ($user->active) {
            $log->response = 403;
            $log->save();
            return Response::make("NOTFOUND", 200);
        } else {
            $log->response = 402;
            $log->save();
            return Response::make("NOTFOUND", 200);
        }
    }

    private function lookupKeyFob($keyId)
    {
        try {
            $keyFob = KeyFob::lookup($keyId);
            return $keyFob;
        } catch (Exception $e) {
            $keyId = substr('BB'.$keyId, 0, 12);
            $keyFob = KeyFob::lookup($keyId);
            return $keyFob;
        }
    }

} 