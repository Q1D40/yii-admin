<?php
return [ 
	'adminEmail' => 'admin@example.com',
	'qiniu' => [ 
		/*
		 * 'dev' => [ 'bucket' => 'nvtuan', 'accessKey' => 'fuoDaS8Hvp0P9JUdchm4V1RW4ySRFyYXJFqp0PZG', 'secretKey' => 'ka6R43WkwW8SFfBdZY6y4jVUo7T1eSa2EsJ1CRMk' ],
		 */
		'dev' => [ 
			'bucket' => 'nvtuan',
			'accessKey' => 'pSiqspWn9q-5ZNCTXdSUj0PnLuB-9iQTAw7-E4Yx',
			'secretKey' => 'AiHVSoJoMg7OmZct4vahkeIf79X9I5Va9BBBToxU' 
		],
		'pro' => [ 
			'bucket' => 'nvtuan',
			'accessKey' => 'pSiqspWn9q-5ZNCTXdSUj0PnLuB-9iQTAw7-E4Yx',
			'secretKey' => 'AiHVSoJoMg7OmZct4vahkeIf79X9I5Va9BBBToxU' 
		] 
	],
	'news_qiniu' => [ 
        'dev' => [ 
            'bucket' => 'liu-news',
            'accessKey' => 'pSiqspWn9q-5ZNCTXdSUj0PnLuB-9iQTAw7-E4Yx',
            'secretKey' => 'AiHVSoJoMg7OmZct4vahkeIf79X9I5Va9BBBToxU' 
        ],
		'pro' => [ 
			'bucket' => 'liu-news',
		    'accessKey' => 'pSiqspWn9q-5ZNCTXdSUj0PnLuB-9iQTAw7-E4Yx',
            'secretKey' => 'AiHVSoJoMg7OmZct4vahkeIf79X9I5Va9BBBToxU' 
		] 
	],
	'qiniuDomain' => [ 
		'dev' => 'http://7xj34v.com1.z0.glb.clouddn.com/',
		'pro' => 'http://7xj34v.com1.z0.glb.clouddn.com/' 
	],
	'stickerQiniu' => [ 
		'dev' => [ 
			'bucket' => 'sticker',
			'accessKey' => 'pSiqspWn9q-5ZNCTXdSUj0PnLuB-9iQTAw7-E4Yx',
			'secretKey' => 'AiHVSoJoMg7OmZct4vahkeIf79X9I5Va9BBBToxU' 
		],
		'pro' => [ 
			'bucket' => 'sticker',
			'accessKey' => 'pSiqspWn9q-5ZNCTXdSUj0PnLuB-9iQTAw7-E4Yx',
			'secretKey' => 'AiHVSoJoMg7OmZct4vahkeIf79X9I5Va9BBBToxU' 
		] 
	],
	'stickerQiniuDomain' => [ 
		'dev' => 'http://7xkt20.com1.z0.glb.clouddn.com/',
		'pro' => 'http://7xkt20.com1.z0.glb.clouddn.com/' 
	],
	'applog' => '/data/wwwlogs/applog',
	'pageSize' => 30 
];
