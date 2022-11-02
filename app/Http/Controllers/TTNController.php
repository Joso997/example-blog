<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TTNController extends Controller
{
    private string $bearer = "NNSXS.IOV7PTHMS6CHQLODFD4RXQFDRFBNH3MBMV7KQLY.ZYBH2U6PYM3Q2PW5PSSCOGBGU7VUNPYUHOGTYYZCWQJGH625WLAA";
    private string $application = "koka";

    public function index(): Response
    {
        return response(Entity::orderBy('created_at', 'asc')->get()->pluck('json'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $arr = [];
        foreach ($request->all() as $stat ){
            $arr[$stat['Stats'][3]["Data"]] = $stat['Stats'][1]["Data"];
        }
        $deviceId = 'tri-m-' . $this->generateRandomHex(8);
        $devEui = $this->generateRandomHex(16);

        $data = [
            'end_device' =>
                [
                    'ids' =>
                        [
                            'device_id' => $deviceId,
                            'dev_eui' => $devEui,
                            'join_eui' => '0000000000000000',
                        ],
                    'join_server_address' => 'eu1.cloud.thethings.network',
                    'network_server_address' => 'eu1.cloud.thethings.network',
                    'application_server_address' => 'eu1.cloud.thethings.network',
                    "name" => $arr['Title'],
                    "description" => $arr['Description'],
                ],
            'field_mask' =>
                [
                    'paths' =>
                        [
                            "join_server_address",
                            "network_server_address",
                            "application_server_address",
                            "ids.join_eui",
                            "ids.device_id",
                            "ids.dev_eui",
                            "name",
                            "description"
                        ],
                ],
        ];
        $response = $this->callApiPOST($data, "https://eu1.cloud.thethings.network/api/v3/applications/".$this->application."/devices");
        $data = [
            "end_device" => [
                "ids" => [
                    'device_id' => $deviceId,
                    'dev_eui' => $devEui,
                    'join_eui' => '0000000000000000',
                ],
                "root_keys" => [
                    "app_key" => [
                        "key" => $this->generateRandomHex(32),
                        "kek_label" => "",
                        "encrypted_key" => ""
                    ]
                ]
            ],
            "field_mask" => [
                "paths" => [
                    "root_keys.app_key",
                    "ids.device_id",
                    "ids.dev_eui",
                    "ids.join_eui"
                ]
            ]
        ];
        $response = $this->callApiPOST($data, "https://eu1.cloud.thethings.network/api/v3/js/applications/".$this->application."/devices");
        $data = [
            "end_device" => [
                "ids" => [
                    "device_id" => $deviceId,
                    "dev_eui" => $devEui,
                    "join_eui" => "0000000000000000"
                ],
                "lorawan_phy_version" => "PHY_V1_0_3_REV_A",
                "frequency_plan_id" => "EU_863_870_TTN",
                "supports_join" => true,
                "lorawan_version" => "MAC_V1_0_3"
            ],
            "field_mask" => [
                "paths" => [
                    "supports_join",
                    "lorawan_version",
                    "ids.device_id",
                    "ids.dev_eui",
                    "ids.join_eui",
                    "lorawan_phy_version",
                    "frequency_plan_id"
                ]
            ]
        ];
        $response = $this->callApiPOST($data, "https://eu1.cloud.thethings.network/api/v3/ns/applications/".$this->application."/devices");
        return response($response);
    }

    private function callApiPOST($data, $url)
    {
        $ch = curl_init();
        $authorization = "Authorization: Bearer $this->bearer";
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json", "Content-Type: application/json", "Accept-Language: en_US", $authorization]);

        $result = curl_exec($ch);
        $err = curl_error($ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($result);
        }
    }

    private function callApiGET($url)
    {
        $ch = curl_init();
        $authorization = "Authorization: Bearer $this->bearer";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [ "Accept: application/json", "Content-Type: application/json", "Accept-Language: en_US", $authorization]);

        $result = curl_exec($ch);
        $err = curl_error($ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($result);
        }
    }

    private function generateRandomHex (int $size) : string {
        return implode( array_map( function() { return dechex( mt_rand( 0, 15 ) ); }, array_fill( 0, $size, null ) ) );
  }


}
