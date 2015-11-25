<?
return [
	'modules' => [
        'api' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
	'request' => [
		'parsers' => [
			'application/json' => 'yii\web\JsonParser',
		]
	],
	'components'=>[
		'urlManager' => [
				'enablePrettyUrl' => true,
				'showScriptName' => false,
				'rules' => [
					[
						'class' => 'yii\rest\UrlRule',
						'controller' => 'api/user',
						'only'=>['login','check'],
						'extraPatterns' => [
							'login' => 'login',
							'check' => 'check',
						],
					],
					[
						'class' => 'yii\rest\UrlRule',
						'tokens'=>[
							'{id}' => '<id:\w*>',
						],
						'extraPatterns' => [
							'countries' => 'countries',
							'cities' => 'cities',
						],
						'controller' => 'api/proxy',
						'only'=>['index', 'view', 'countries', 'cities'],
					],
					'api/user'=>'site/error',
					'api/proxy'=>'site/error'
				],
			],
	]
]