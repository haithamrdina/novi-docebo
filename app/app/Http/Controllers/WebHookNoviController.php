<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class WebHookNoviController extends Controller
{
     /**
    * @param Request $request
    * @return json
    */
    public function noviUpdateHandle(Request $request)
    {
        $configFilePath = config_path('webhook.php');
        $configContent = '<?php' . PHP_EOL . 'return [' . PHP_EOL;

        $configContent .= "    'test' => null, //test" . PHP_EOL;

        $configContent .= '];' . PHP_EOL;

        // Écrire dans le fichier de configuration
        File::put($configFilePath, $configContent);
        $payload = $request->getContent();
        Log::info('Webhook Payload: ' . $payload);
        return response()->json(['status' => 'success']);
    }
}
