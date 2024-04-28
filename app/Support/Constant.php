<?php

namespace App\Support;

final class Constant
{
    /**
     * ProviderCreatingEmployeeData Schema
     *
     * @var array
     */
    const ProviderEmployeeDataSchema = [
        'provider_1' => [

            'jobTitle' => 'role',
            'gender'   => 'sex',
            'birthday' => 'dob',
            'firstName' => 'first_name',
            'lastName' => 'last_name',
            'email'    => 'email_address',
            'tags'     => 'labels',
            'primaryPhone' => 'mobile_number'

        ],

        'provider_2' => [

            'jobTitle' => 'position',
            'gender'   => 'gender',
            'birthday' => 'DOB',
            'firstName' => 'name',
            'lastName' => 'surname',
            'email'    => 'emailAddress',
            'tags'     => 'badges',
            'primaryPhone' => 'phoneNumber'

        ],
    ];

    /**
     * ProviderEmployeeDoNotUpdate Schema
     */

    const ProviderEmployeeDoNotUpdateSchema = ['email'];
}
