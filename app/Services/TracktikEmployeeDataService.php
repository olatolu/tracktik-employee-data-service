<?php

namespace App\Services;

use App\Support\Constant;
use App\Traits\HttpClient;
use App\Enums\HttpMethodsEnum;
use App\Support\GeneralException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\EmployeeDataRequest;

class TracktikEmployeeDataService extends Service
{
    use HttpClient, GeneralException;

    /**
     * Declaring pravte variables
     * @var $isUpdating
     *
     */
    private $isUpdating;
    private $tracktik_api_base;
    private $tracktik_api_headers;

    /**
     * TracktikEmployeeDataService constructor.
     * @param EmployeeDataRequest $service
     */
    public function __construct(private EmployeeDataRequest $service)
    {
        $this->tracktik_api_base = Config::get('app.tracktik_api_base');
        $this->tracktik_api_headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . self::get_token(),
            'Accept-Language' => 'en_us'
        ];
    }

    /**
     * @param array $data
     * @return array
     */


    public function create(array $data)
    {

        return $this->sync_data_to_tracktik(
            $this->mapDataToUnifiedSchema($data)
        );

    }

    /**
     * @param array $data
     * @param bool $updating
     * @return array
     */

    public function update(array $data, bool $updating)
    {
        return $this->sync_data_to_tracktik(
            array_merge($this->mapDataToUnifiedSchema($data, $updating),
                ['employee_id' => $data['payload']['employee_id']]
            ),
            $updating
        );

    }

    // Function to map data to unified schema
    private function mapDataToUnifiedSchema(array $data, bool $updating = false): array
    {
        $mapData = [];
        if (!empty($data) && !empty($data['payload']['provider'])) {

            $providerEmployeeDataSchema = Constant::ProviderEmployeeDataSchema[$data['payload']['provider']];

            $providerData = ($updating) ? Arr::except($providerEmployeeDataSchema, Constant::ProviderEmployeeDoNotUpdateSchema) : $providerEmployeeDataSchema;

            foreach ($providerData as $key => $value) {

                if (!empty($data['payload']['data'][$value])) {

                    $mapData[$key] = $data['payload']['data'][$value];

                }

            }
        }

        return $mapData;
    }

    /**
     * @param array $mapData
     * @param bool $updating
     * @return array
     */


    private function sync_data_to_tracktik(array $mapData, bool $updating = false)
    {

        return $this->makeRequest(
            ($updating) ? HttpMethodsEnum::PUT : HttpMethodsEnum::POST,
            ($updating) ? (string)$this->tracktik_api_base . '/employees/' . $mapData['employee_id'] : (string)$this->tracktik_api_base . '/employees',
            (array)$mapData,
            $this->tracktik_api_headers,
            true
        );


    }


}
