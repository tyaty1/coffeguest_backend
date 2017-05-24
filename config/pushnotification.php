<?php
if(env('APP_ENV')=='local'){
return [
  'gcm' => [
      'priority' => 'normal',
      'dry_run' => false,
      'apiKey' => 'My_ApiKey',
  ],
  'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
  ],
  
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/test',
      'passPhrase' => env('APN_PASS'), //Optional
  //  'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
      'dry_run' => true
  ]
 

  
];}
 elseif(env('APP_ENV'=='production')){
  return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'fcm' => [
          'priority' => 'normal',
          'dry_run' => false,
          'apiKey' => 'My_ApiKey',
    ],
      'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/',
        'passPhrase' => env('APN_PASS'), //Optional
    //  'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true
  ]

];}
